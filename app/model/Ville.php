<?php
class Ville {

    public static function getAll() {
        $db = Flight::db();
        return $db->query("SELECT * FROM ville")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insert($nom, $region) {
        $db = Flight::db();
        $stmt = $db->prepare("INSERT INTO ville(nom_ville, region) VALUES (?, ?)");
        $stmt->execute([$nom, $region]);
    }
}
