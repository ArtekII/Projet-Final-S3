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
                       d.type_don as don_type, d.quantite as don_quantite, d.montant as don_montant, d.date_don,
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
                       d.type_don as don_type, d.quantite as don_quantite, d.montant as don_montant, d.date_don,
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
     * Simule le dispatch SANS persister en base.
     * Retourne un aperçu des attributions qui seraient effectuées.
     */
    public function simulerDispatch(): array
    {
        $donModel = new Don($this->db);
        $besoinModel = new Besoin($this->db);
        
        $attributions = [];
        
        // Récupère les dons non dispatchés par ordre de date (nature/materiaux seulement)
        $dons = $donModel->getDonsNonDispatches();
        
        // Copie locale des besoins pour simulation sans modifier la BD
        $besoins = $besoinModel->getBesoinsNonSatisfaits();
        $besoinsMap = [];
        foreach ($besoins as $b) {
            $besoinsMap[$b['id']] = $b;
        }

        foreach ($dons as $don) {
            $quantiteRestante = (float) $don['restant'];
            
            foreach ($besoinsMap as &$besoin) {
                if ($quantiteRestante <= 0) break;
                
                $quantiteNecessaire = (float) $besoin['quantite_restante'];
                if ($quantiteNecessaire <= 0) continue;
                
                $quantiteAttribuee = min($quantiteRestante, $quantiteNecessaire);
                
                $attributions[] = [
                    'don_id' => $don['id'],
                    'type_don' => $don['type_don'],
                    'quantite_attribuee' => $quantiteAttribuee,
                    'besoin_id' => $besoin['id'],
                    'type_besoin' => $besoin['type_besoin'],
                    'prix_unitaire' => $besoin['prix_unitaire'],
                    'ville' => $besoin['ville_nom'],
                    'region' => $besoin['region_nom']
                ];
                
                // Décrémenter localement (pas en BD)
                $besoin['quantite_restante'] -= $quantiteAttribuee;
                $quantiteRestante -= $quantiteAttribuee;
            }
            unset($besoin);
        }
        
        return $attributions;
    }

    /**
     * Valide et persiste réellement le dispatch en base de données.
     */
    public function validerDispatch(): array
    {
        $donModel = new Don($this->db);
        $besoinModel = new Besoin($this->db);
        
        $attributions = [];
        
        $dons = $donModel->getDonsNonDispatches();
        
        foreach ($dons as $don) {
            $quantiteRestante = (float) $don['restant'];
            
            $besoins = $besoinModel->getBesoinsNonSatisfaits();
            
            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) break;
                
                $quantiteNecessaire = (float) $besoin['quantite_restante'];
                if ($quantiteNecessaire <= 0) continue;
                
                $quantiteAttribuee = min($quantiteRestante, $quantiteNecessaire);
                
                // Persister l'attribution
                $this->create([
                    'don_id' => $don['id'],
                    'besoin_id' => $besoin['id'],
                    'quantite_attribuee' => $quantiteAttribuee
                ]);
                
                // Mettre à jour la quantité reçue du besoin
                $besoinModel->updateQuantiteRecue($besoin['id'], $quantiteAttribuee);
                
                $attributions[] = [
                    'don_id' => $don['id'],
                    'type_don' => $don['type_don'],
                    'quantite_attribuee' => $quantiteAttribuee,
                    'type_besoin' => $besoin['type_besoin'],
                    'prix_unitaire' => $besoin['prix_unitaire'],
                    'ville' => $besoin['ville_nom'],
                    'region' => $besoin['region_nom']
                ];
                
                $quantiteRestante -= $quantiteAttribuee;
            }
            
            // Mettre à jour le restant du don et marquer comme dispatché
            $donModel->updateRestant($don['id'], (float) $don['restant'] - $quantiteRestante);
            $donModel->markAsDispatched($don['id']);
        }
        
        return $attributions;
    }

    /**
     * Récupère l'historique des dispatches par ville
     */
    public function getHistoriqueParVille(int $villeId): array
    {
        $sql = "SELECT dp.*, 
                       d.type_don as don_type, d.date_don,
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
