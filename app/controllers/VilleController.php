<?php
class VilleController {

    public static function index() {
        $villes = Ville::getAll();
        Flight::render('ville/liste', ['villes' => $villes]);
    }

    public static function ajout() {
        Flight::render('ville/ajout');
    }

    public static function save() {
        Ville::insert($_POST['nom_ville'], $_POST['region']);
        Flight::redirect('/villes');
    }
}
