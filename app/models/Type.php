<?php

namespace app\models;

use flight\database\PdoWrapper;

class Type
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $sql = "SELECT id, nom FROM `type` ORDER BY nom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nom FROM `type` WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByNom(string $nom): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nom FROM `type` WHERE nom = ?");
        $stmt->execute([$nom]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
