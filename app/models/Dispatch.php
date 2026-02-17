<?php

namespace app\models;

use flight\database\PdoWrapper;

class Dispatch
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $sql = "SELECT dp.*, 
                       d.type as don_type, d.quantite as don_quantite, d.date_don,
                       b.type_besoin, b.prix_unitaire,
                       v.nom as ville_nom, r.nom as region_nom
                FROM dispatch dp
                JOIN dons d ON dp.don_id = d.id
                JOIN besoins b ON dp.besoin_id = b.id
                JOIN villes v ON b.ville_id = v.id
                JOIN regions r ON v.region_id = r.id
                ORDER BY dp.date_dispatch DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT dp.*, 
                       d.type as don_type, d.quantite as don_quantite, d.date_don,
                       b.type_besoin, b.prix_unitaire,
                       v.nom as ville_nom, r.nom as region_nom
                FROM dispatch dp
                JOIN dons d ON dp.don_id = d.id
                JOIN besoins b ON dp.besoin_id = b.id
                JOIN villes v ON b.ville_id = v.id
                JOIN regions r ON v.region_id = r.id
                WHERE dp.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $data['don_id'],
            $data['besoin_id'],
            $data['quantite_attribuee']
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Simule le dispatch automatique des dons par ordre de date
     * Retourne un tableau des attributions effectuées
     */
    public function simulerDispatch(): array
    {
        $donModel = new Don($this->db);
        $besoinModel = new Besoin($this->db);
        
        $attributions = [];
        
        // Récupère les dons non dispatchés par ordre de date
        $dons = $donModel->getDonsNonDispatches();
        
        foreach ($dons as $don) {
            // Dispatch le don selon son type
            $this->dispatchDon($don, $attributions, $besoinModel, $donModel);
        }
        
        return $attributions;
    }

    private function dispatchDon(array $don, array &$attributions, Besoin $besoinModel, Don $donModel): void
    {
        $quantiteRestante = $don['quantite'];
        $typeDon = $don['type'];
        
        // Récupère les besoins non satisfaits pour ce type
        $besoins = $besoinModel->getBesoinsNonSatisfaits($typeDon);
        
        foreach ($besoins as $besoin) {
            if ($quantiteRestante <= 0) break;
            
            $quantiteNecessaire = $besoin['quantite_restante'];
            $quantiteAttribuee = min($quantiteRestante, $quantiteNecessaire);
            
            // Créer l'attribution
            $this->create([
                'don_id' => $don['id'],
                'besoin_id' => $besoin['id'],
                'quantite_attribuee' => $quantiteAttribuee
            ]);
            
            // Mettre à jour la quantité reçue du besoin
            $besoinModel->updateQuantiteRecue($besoin['id'], $quantiteAttribuee);
            
            $attributions[] = [
                'don_id' => $don['id'],
                'type' => $typeDon,
                'quantite_attribuee' => $quantiteAttribuee,
                'ville' => $besoin['ville_nom'],
                'region' => $besoin['region_nom']
            ];
            
            $quantiteRestante -= $quantiteAttribuee;
        }
        
        // Marquer le don comme dispatché (même partiellement)
        $donModel->markAsDispatched($don['id']);
    }

    /**
     * Récupère l'historique des dispatches par ville
     */
    public function getHistoriqueParVille(int $villeId): array
    {
        $sql = "SELECT dp.*, 
                       d.type as don_type, d.date_don,
                       b.type_besoin, b.prix_unitaire
                FROM dispatch dp
                JOIN dons d ON dp.don_id = d.id
                JOIN besoins b ON dp.besoin_id = b.id
                WHERE b.ville_id = ?
                ORDER BY dp.date_dispatch DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques des dispatches
     */
    public function getStatistiques(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_dispatches,
                    SUM(dp.quantite_attribuee * b.prix_unitaire) as valeur_totale_dispatchee,
                    COUNT(DISTINCT d.id) as nb_dons_dispatches,
                    COUNT(DISTINCT b.ville_id) as nb_villes_beneficiaires
                FROM dispatch dp
                JOIN dons d ON dp.don_id = d.id
                JOIN besoins b ON dp.besoin_id = b.id";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
