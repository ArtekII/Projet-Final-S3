<?php

namespace app\models;

use flight\database\PdoWrapper;

class Achat
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function findAll(?int $villeId = null): array
    {
        $sql = "SELECT a.*, 
                       tb.nom as type_besoin, b.designation as besoin_designation, b.prix_unitaire, b.quantite_demandee, b.quantite_recue,
                       v.nom as ville_nom, r.nom as region_nom,
                       td.nom as type_don, d.montant as don_montant, d.restant as don_restant
                FROM achats a
                JOIN besoins b ON a.besoin_id = b.id
                JOIN `type` tb ON b.type_id = tb.id
                JOIN villes v ON a.ville_id = v.id
                JOIN regions r ON v.region_id = r.id
                JOIN dons d ON a.don_id = d.id
                JOIN `type` td ON d.type_id = td.id";
        
        if ($villeId !== null) {
            $sql .= " WHERE a.ville_id = ?";
        }
        
        $sql .= " ORDER BY a.date_achat DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($villeId !== null) {
            $stmt->execute([$villeId]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO achats (don_id, ville_id, besoin_id, montant_achat, frais_percent, montant_total, date_achat) 
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $data['don_id'],
            $data['ville_id'],
            $data['besoin_id'],
            $data['montant_achat'],
            $data['frais_percent'],
            $data['montant_total']
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupère le frais d'achat configuré
     */
    public function getFraisPercent(): float
    {
        $sql = "SELECT frais_achat_percent FROM parametres ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (float) $result['frais_achat_percent'] : 10.00;
    }

    /**
     * Met à jour le frais d'achat
     */
    public function updateFraisPercent(float $frais): bool
    {
        // Vérifie s'il existe déjà un paramètre
        $stmt = $this->db->query("SELECT COUNT(*) FROM parametres");
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $stmt = $this->db->prepare("UPDATE parametres SET frais_achat_percent = ? ORDER BY id DESC LIMIT 1");
        } else {
            $stmt = $this->db->prepare("INSERT INTO parametres (frais_achat_percent) VALUES (?)");
        }
        return $stmt->execute([$frais]);
    }

    /**
     * Statistiques des achats
     */
    public function getStatistiques(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_achats,
                    COALESCE(SUM(montant_achat), 0) as total_montant_achat,
                    COALESCE(SUM(montant_total), 0) as total_montant_total,
                    COALESCE(SUM(montant_total - montant_achat), 0) as total_frais
                FROM achats";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le mode de distribution configuré
     */
    public function getModeDistribution(): string
    {
        $sql = "SELECT mode_distribution FROM parametres ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['mode_distribution'] : 'date';
    }

    /**
     * Met à jour le mode de distribution
     */
    public function updateModeDistribution(string $mode): bool
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM parametres");
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $stmt = $this->db->prepare("UPDATE parametres SET mode_distribution = ? ORDER BY id DESC LIMIT 1");
        } else {
            $stmt = $this->db->prepare("INSERT INTO parametres (mode_distribution) VALUES (?)");
        }
        return $stmt->execute([$mode]);
    }
}
