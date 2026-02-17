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
            "INSERT INTO dons (type, quantite) VALUES (?, ?)"
        );
        $stmt->execute([
            $data['type'],
            $data['quantite']
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE dons SET type = ?, quantite = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['type'],
            $data['quantite'],
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
        $sql = "SELECT * FROM dons WHERE dispatched = FALSE ORDER BY date_don ASC, id ASC";
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
                    SUM(quantite) as quantite_totale,
                    SUM(CASE WHEN dispatched = TRUE THEN 1 ELSE 0 END) as dons_dispatches,
                    SUM(CASE WHEN dispatched = FALSE THEN 1 ELSE 0 END) as dons_en_attente
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
        $sql = "SELECT DISTINCT type FROM dons ORDER BY type";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
