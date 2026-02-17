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
        $sql = "SELECT d.*, t.nom as type_don
                FROM dons d
                JOIN `type` t ON d.type_id = t.id
                ORDER BY d.date_don DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT d.*, t.nom as type_don
                FROM dons d
                JOIN `type` t ON d.type_id = t.id
                WHERE d.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO dons (type_id, designation, montant, quantite, restant, date_don) VALUES (?, ?, ?, ?, ?, ?)"
        );

        $montant = !empty($data['montant']) ? (float) $data['montant'] : null;
        $quantite = !empty($data['quantite']) ? (float) $data['quantite'] : null;
        $designation = !empty($data['designation']) ? trim($data['designation']) : null;
        $typeNom = $this->getTypeNom((int) $data['type_id']);
        // restant = montant pour argent, quantite pour les autres types
        $restant = $typeNom === 'argent' ? $montant : $quantite;
        $dateDon = !empty($data['date_don']) ? $data['date_don'] : date('Y-m-d');

        $stmt->execute([
            $data['type_id'],
            $designation,
            $montant,
            $quantite,
            $restant,
            $dateDon
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $montant = !empty($data['montant']) ? (float) $data['montant'] : null;
        $quantite = !empty($data['quantite']) ? (float) $data['quantite'] : null;
        $designation = !empty($data['designation']) ? trim($data['designation']) : null;
        $typeNom = $this->getTypeNom((int) $data['type_id']);
        $restant = $typeNom === 'argent' ? $montant : $quantite;

        $dateDon = !empty($data['date_don']) ? $data['date_don'] : date('Y-m-d');

        $stmt = $this->db->prepare(
            "UPDATE dons SET type_id = ?, designation = ?, montant = ?, quantite = ?, restant = ?, date_don = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['type_id'],
            $designation,
            $montant,
            $quantite,
            $restant,
            $dateDon,
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
        $sql = "SELECT d.*, t.nom as type_don
                FROM dons d
                JOIN `type` t ON d.type_id = t.id
                WHERE d.dispatched = FALSE AND t.nom <> 'argent'
                ORDER BY d.date_don ASC, d.id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les dons en argent avec du restant
     */
    public function getDonsArgentDisponibles(): array
    {
        $sql = "SELECT d.*, t.nom as type_don
                FROM dons d
                JOIN `type` t ON d.type_id = t.id
                WHERE t.nom = 'argent' AND d.restant > 0
                ORDER BY d.date_don ASC, d.id ASC";
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
                    SUM(CASE WHEN t.nom = 'argent' THEN COALESCE(montant, 0) ELSE 0 END) as total_argent,
                    SUM(CASE WHEN t.nom = 'argent' THEN COALESCE(restant, 0) ELSE 0 END) as argent_restant
                FROM dons d
                JOIN `type` t ON d.type_id = t.id";
        
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
                       b.id as besoin_id, tb.nom as type_besoin, b.designation as besoin_designation, v.nom as ville_nom, r.nom as region_nom,
                       t.nom as type_don
                FROM dons d
                JOIN `type` t ON d.type_id = t.id
                LEFT JOIN dispatch dp ON d.id = dp.don_id
                LEFT JOIN besoins b ON dp.besoin_id = b.id
                LEFT JOIN `type` tb ON b.type_id = tb.id
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
        $sql = "SELECT id, nom FROM type ORDER BY nom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

    private function getTypeNom(int $typeId): string
    {
        $stmt = $this->db->prepare("SELECT nom FROM `type` WHERE id = ?");
        $stmt->execute([$typeId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? strtolower(trim((string) $result['nom'])) : '';
    }
}
