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
     * @param string $mode Mode de distribution : 'date', 'priorite', 'proportionnel'
     */
    public function simulerDispatch(string $mode = 'date'): array
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

        // Trier les besoins selon le mode de distribution
        $besoinsMap = $this->trierBesoinsParMode($besoinsMap, $mode);

        if ($mode === 'proportionnel') {
            return $this->distribuerProportionnel($dons, $besoinsMap);
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
     * @param string $mode Mode de distribution : 'date', 'priorite', 'proportionnel'
     */
    public function validerDispatch(string $mode = 'date'): array
    {
        $donModel = new Don($this->db);
        $besoinModel = new Besoin($this->db);
        
        $attributions = [];
        
        $dons = $donModel->getDonsNonDispatches();
        $besoins = $besoinModel->getBesoinsNonSatisfaits();
        $besoinsMap = [];
        foreach ($besoins as $b) {
            $besoinsMap[$b['id']] = $b;
        }

        // Trier les besoins selon le mode
        $besoinsMap = $this->trierBesoinsParMode($besoinsMap, $mode);

        if ($mode === 'proportionnel') {
            $attribs = $this->distribuerProportionnel($dons, $besoinsMap);
            // Persister chaque attribution
            foreach ($attribs as $attr) {
                $this->create([
                    'don_id' => $attr['don_id'],
                    'besoin_id' => $attr['besoin_id'],
                    'quantite_attribuee' => $attr['quantite_attribuee']
                ]);
                $besoinModel->updateQuantiteRecue($attr['besoin_id'], $attr['quantite_attribuee']);
            }
            // Mettre à jour les dons
            foreach ($dons as $don) {
                $totalUtilise = 0;
                foreach ($attribs as $attr) {
                    if ($attr['don_id'] === $don['id']) {
                        $totalUtilise += $attr['quantite_attribuee'];
                    }
                }
                if ($totalUtilise > 0) {
                    $donModel->updateRestant($don['id'], $totalUtilise);
                    if ($totalUtilise >= (float) $don['restant']) {
                        $donModel->markAsDispatched($don['id']);
                    }
                }
            }
            return $attribs;
        }
        
        foreach ($dons as $don) {
            $quantiteRestante = (float) $don['restant'];
            
            foreach ($besoinsMap as $besoinId => &$besoin) {
                if ($quantiteRestante <= 0) break;
                
                $quantiteNecessaire = (float) $besoin['quantite_restante'];
                if ($quantiteNecessaire <= 0) continue;
                
                $quantiteAttribuee = min($quantiteRestante, $quantiteNecessaire);
                
                // Persister l'attribution
                $this->create([
                    'don_id' => $don['id'],
                    'besoin_id' => $besoinId,
                    'quantite_attribuee' => $quantiteAttribuee
                ]);
                
                // Mettre à jour la quantité reçue du besoin
                $besoinModel->updateQuantiteRecue($besoinId, $quantiteAttribuee);
                
                $attributions[] = [
                    'don_id' => $don['id'],
                    'type_don' => $don['type_don'],
                    'quantite_attribuee' => $quantiteAttribuee,
                    'besoin_id' => $besoinId,
                    'type_besoin' => $besoin['type_besoin'],
                    'prix_unitaire' => $besoin['prix_unitaire'],
                    'ville' => $besoin['ville_nom'],
                    'region' => $besoin['region_nom']
                ];
                
                $besoin['quantite_restante'] -= $quantiteAttribuee;
                $quantiteRestante -= $quantiteAttribuee;
            }
            unset($besoin);
            
            // Mettre à jour le restant du don et marquer comme dispatché
            $totalUtilise = (float) $don['restant'] - $quantiteRestante;
            $donModel->updateRestant($don['id'], $totalUtilise);
            if ($quantiteRestante <= 0) {
                $donModel->markAsDispatched($don['id']);
            }
        }
        
        return $attributions;
    }

    /**
     * Trie les besoins selon le mode de distribution choisi
     */
    private function trierBesoinsParMode(array $besoinsMap, string $mode): array
    {
        switch ($mode) {
            case 'priorite':
                // Par valeur totale restante décroissante (les plus gros besoins en premier)
                uasort($besoinsMap, function ($a, $b) {
                    $valA = (float) $a['quantite_restante'] * (float) $a['prix_unitaire'];
                    $valB = (float) $b['quantite_restante'] * (float) $b['prix_unitaire'];
                    return $valB <=> $valA;
                });
                break;
            case 'proportionnel':
                // Pas de tri particulier, la logique proportionnelle est gérée séparément
                break;
            case 'date':
            default:
                // Par date de saisie (ordre chronologique - déjà le tri par défaut de la requête)
                uasort($besoinsMap, function ($a, $b) {
                    return ($a['date_saisie'] ?? '') <=> ($b['date_saisie'] ?? '');
                });
                break;
        }
        return $besoinsMap;
    }

    /**
     * Distribution proportionnelle : répartit chaque don au prorata des besoins restants
     */
    private function distribuerProportionnel(array $dons, array $besoinsMap): array
    {
        $attributions = [];

        foreach ($dons as $don) {
            $quantiteDisponible = (float) $don['restant'];
            if ($quantiteDisponible <= 0) continue;

            // Calculer le total des besoins restants
            $totalBesoinsRestants = 0;
            foreach ($besoinsMap as $besoin) {
                $totalBesoinsRestants += (float) $besoin['quantite_restante'];
            }
            if ($totalBesoinsRestants <= 0) break;

            foreach ($besoinsMap as &$besoin) {
                $qteRestante = (float) $besoin['quantite_restante'];
                if ($qteRestante <= 0) continue;

                // Part proportionnelle
                $ratio = $qteRestante / $totalBesoinsRestants;
                $quantiteAttribuee = min(
                    floor($ratio * $quantiteDisponible * 100) / 100, // arrondi à 2 décimales
                    $qteRestante
                );
                if ($quantiteAttribuee <= 0) continue;

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

                $besoin['quantite_restante'] -= $quantiteAttribuee;
            }
            unset($besoin);
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
