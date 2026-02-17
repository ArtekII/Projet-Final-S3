<?php

namespace app\models;

use flight\database\PdoWrapper;

class Region
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM regions ORDER BY nom");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM regions WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO regions (nom) VALUES (?)");
        $stmt->execute([$data['nom']]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE regions SET nom = ? WHERE id = ?");
        return $stmt->execute([$data['nom'], $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM regions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getVillesParRegion(): array
    {
        $sql = "SELECT r.*, COUNT(v.id) as nb_villes
                FROM regions r
                LEFT JOIN villes v ON r.id = v.region_id
                GROUP BY r.id
                ORDER BY r.nom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
