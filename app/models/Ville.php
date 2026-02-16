<?php

namespace app\models;

use flight\database\PdoWrapper;

class Ville
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $sql = "SELECT v.*, r.nom as region_nom 
                FROM villes v 
                JOIN regions r ON v.region_id = r.id 
                ORDER BY r.nom, v.nom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT v.*, r.nom as region_nom 
                FROM villes v 
                JOIN regions r ON v.region_id = r.id 
                WHERE v.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO villes (nom, region_id) VALUES (?, ?)"
        );
        $stmt->execute([
            $data['nom'],
            $data['region_id']
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE villes SET nom = ?, region_id = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['nom'],
            $data['region_id'],
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM villes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findByRegion(int $regionId): array
    {
        $sql = "SELECT * FROM villes WHERE region_id = ? ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$regionId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le tableau de bord des villes avec leurs besoins et dons attribués
     */
    public function getDashboard(): array
    {
        $sql = "SELECT 
                    v.id,
                    v.nom as ville_nom,
                    r.nom as region_nom,
                    COALESCE(SUM(b.quantite_demandee * b.prix_unitaire), 0) as valeur_besoins,
                    COALESCE(SUM(b.quantite_recue * b.prix_unitaire), 0) as valeur_recue,
                    COUNT(DISTINCT b.id) as nb_besoins
                FROM villes v
                JOIN regions r ON v.region_id = r.id
                LEFT JOIN besoins b ON v.id = b.ville_id
                GROUP BY v.id, v.nom, r.nom
                ORDER BY r.nom, v.nom";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les détails des besoins d'une ville
     */
    public function getBesoinsDetails(int $villeId): array
    {
        $sql = "SELECT 
                    b.id,
                    b.type_besoin,
                    b.prix_unitaire,
                    b.quantite_demandee,
                    b.quantite_recue,
                    (b.quantite_demandee - b.quantite_recue) as quantite_restante,
                    (b.quantite_demandee * b.prix_unitaire) as valeur_totale,
                    (b.quantite_recue * b.prix_unitaire) as valeur_recue
                FROM besoins b
                WHERE b.ville_id = ?
                ORDER BY b.type_besoin";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
