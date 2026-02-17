<?php

namespace app\models;

use flight\database\PdoWrapper;

class Besoin
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $sql = "SELECT b.*, v.nom as ville_nom, r.nom as region_nom
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN regions r ON v.region_id = r.id
                ORDER BY b.date_saisie DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT b.*, v.nom as ville_nom, r.nom as region_nom
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN regions r ON v.region_id = r.id
                WHERE b.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO besoins (ville_id, type_besoin, quantite_demandee, prix_unitaire) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['ville_id'],
            $data['type_besoin'],
            $data['quantite_demandee'],
            $data['prix_unitaire'] ?? 0
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE besoins SET ville_id = ?, type_besoin = ?, quantite_demandee = ?, prix_unitaire = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['ville_id'],
            $data['type_besoin'],
            $data['quantite_demandee'],
            $data['prix_unitaire'] ?? 0,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM besoins WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateQuantiteRecue(int $id, float $quantite): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE besoins SET quantite_recue = quantite_recue + ? WHERE id = ?"
        );
        return $stmt->execute([$quantite, $id]);
    }

    public function findByVille(int $villeId): array
    {
        $sql = "SELECT b.*
                FROM besoins b
                WHERE b.ville_id = ?
                ORDER BY b.type_besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les besoins non satisfaits (pour le dispatch)
     */
    public function getBesoinsNonSatisfaits(string $typeBesoin = null): array
    {
        $sql = "SELECT b.*, v.nom as ville_nom, r.nom as region_nom,
                       (b.quantite_demandee - b.quantite_recue) as quantite_restante
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN regions r ON v.region_id = r.id
                WHERE b.quantite_recue < b.quantite_demandee";
        
        if ($typeBesoin !== null) {
            $sql .= " AND b.type_besoin = ?";
        }
        
        $sql .= " ORDER BY b.date_saisie ASC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($typeBesoin !== null) {
            $stmt->execute([$typeBesoin]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les statistiques globales des besoins
     */
    public function getStatistiques(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_besoins,
                    SUM(quantite_demandee * prix_unitaire) as valeur_totale_demandee,
                    SUM(quantite_recue * prix_unitaire) as valeur_totale_recue,
                    SUM((quantite_demandee - quantite_recue) * prix_unitaire) as valeur_restante
                FROM besoins";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les types de besoins distincts
     */
    public function getTypesBesoins(): array
    {
        $sql = "SELECT DISTINCT type_besoin FROM besoins ORDER BY type_besoin";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Récupère les statistiques des besoins groupées par type
     */
    public function getBesoinsParType(): array
    {
        $sql = "SELECT 
                    type_besoin,
                    COUNT(*) as nb_besoins,
                    SUM(quantite_demandee) as total_demande,
                    SUM(quantite_recue) as total_recu,
                    SUM(quantite_demandee - quantite_recue) as total_restant,
                    SUM(quantite_demandee * prix_unitaire) as valeur_demandee,
                    SUM(quantite_recue * prix_unitaire) as valeur_recue,
                    SUM((quantite_demandee - quantite_recue) * prix_unitaire) as valeur_restante
                FROM besoins
                GROUP BY type_besoin
                ORDER BY type_besoin";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
