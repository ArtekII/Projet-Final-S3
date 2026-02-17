<?php

namespace app\models;

use flight\database\PdoWrapper;

class Don
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM dons ORDER BY date_don DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM dons WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO dons (type_don, montant, quantite, restant) VALUES (?, ?, ?, ?)"
        );

        $montant = !empty($data['montant']) ? (float) $data['montant'] : null;
        $quantite = !empty($data['quantite']) ? (float) $data['quantite'] : null;
        // restant = montant pour argent, quantite pour nature/materiaux
        $restant = $data['type_don'] === 'argent' ? $montant : $quantite;

        $stmt->execute([
            $data['type_don'],
            $montant,
            $quantite,
            $restant
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $montant = !empty($data['montant']) ? (float) $data['montant'] : null;
        $quantite = !empty($data['quantite']) ? (float) $data['quantite'] : null;
        $restant = $data['type_don'] === 'argent' ? $montant : $quantite;

        $stmt = $this->db->prepare(
            "UPDATE dons SET type_don = ?, montant = ?, quantite = ?, restant = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['type_don'],
            $montant,
            $quantite,
            $restant,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM dons WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function markAsDispatched(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE dons SET dispatched = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupère les dons non dispatchés (par ordre de date)
     */
    public function getDonsNonDispatches(): array
    {
        $sql = "SELECT * FROM dons WHERE dispatched = FALSE AND type_don IN ('nature', 'materiaux') ORDER BY date_don ASC, id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les dons en argent avec du restant
     */
    public function getDonsArgentDisponibles(): array
    {
        $sql = "SELECT * FROM dons WHERE type_don = 'argent' AND restant > 0 ORDER BY date_don ASC, id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques des dons
     */
    public function getStatistiques(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_dons,
                    SUM(COALESCE(montant, 0)) as montant_total,
                    SUM(COALESCE(quantite, 0)) as quantite_totale,
                    SUM(COALESCE(restant, 0)) as restant_total,
                    SUM(CASE WHEN dispatched = TRUE THEN 1 ELSE 0 END) as dons_dispatches,
                    SUM(CASE WHEN dispatched = FALSE THEN 1 ELSE 0 END) as dons_en_attente,
                    SUM(CASE WHEN type_don = 'argent' THEN COALESCE(montant, 0) ELSE 0 END) as total_argent,
                    SUM(CASE WHEN type_don = 'argent' THEN COALESCE(restant, 0) ELSE 0 END) as argent_restant
                FROM dons";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les dons avec leur dispatch
     */
    public function getDonsAvecDispatch(): array
    {
        $sql = "SELECT d.*, 
                       dp.quantite_attribuee, dp.date_dispatch,
                       b.id as besoin_id, b.type_besoin, v.nom as ville_nom, r.nom as region_nom
                FROM dons d
                LEFT JOIN dispatch dp ON d.id = dp.don_id
                LEFT JOIN besoins b ON dp.besoin_id = b.id
                LEFT JOIN villes v ON b.ville_id = v.id
                LEFT JOIN regions r ON v.region_id = r.id
                ORDER BY d.date_don DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les types de dons distincts
     */
    public function getTypesDons(): array
    {
        $sql = "SELECT DISTINCT type_don FROM dons ORDER BY type_don";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Met à jour le restant d'un don
     */
    public function updateRestant(int $id, float $montantUtilise): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE dons SET restant = restant - ? WHERE id = ?"
        );
        return $stmt->execute([$montantUtilise, $id]);
    }
}
