<?php

namespace app\controllers;

use app\models\Ville;
use app\models\Region;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class VilleController
{
    private PdoWrapper $db;
    private LayoutView $view;

    public function __construct(PdoWrapper $db, LayoutView $view)
    {
        $this->db = $db;
        $this->view = $view;
    }

    public function index(): void
    {
        $villeModel = new Ville($this->db);
        $villes = $villeModel->findAll();

        echo $this->view->renderWithLayout('bngrc/villes/index', [
            'pageTitle' => 'Gestion des Villes',
            'villes' => $villes
        ]);
    }

    public function create(): void
    {
        $regionModel = new Region($this->db);
        $regions = $regionModel->findAll();

        echo $this->view->render('bngrc/villes/form', [
            'pageTitle' => 'Ajouter une Ville',
            'ville' => null,
            'regions' => $regions
        ]);
    }

    public function store(): void
    {
        $villeModel = new Ville($this->db);
        
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'region_id' => (int) ($_POST['region_id'] ?? 0)
        ];

        if (empty($data['nom']) || $data['region_id'] <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/villes/create');
            exit;
        }

        $villeModel->create($data);
        $_SESSION['success'] = 'Ville ajoutée avec succès.';
        header('Location: ' . BASE_URL . '/villes');
        exit;
    }

    public function edit(int $id): void
    {
        $villeModel = new Ville($this->db);
        $regionModel = new Region($this->db);

        $ville = $villeModel->find($id);
        if (!$ville) {
            $_SESSION['error'] = 'Ville non trouvée.';
            header('Location: ' . BASE_URL . '/villes');
            exit;
        }

        $regions = $regionModel->findAll();

        echo $this->view->render('bngrc/villes/form', [
            'pageTitle' => 'Modifier la Ville',
            'ville' => $ville,
            'regions' => $regions
        ]);
    }

    public function update(int $id): void
    {
        $villeModel = new Ville($this->db);
        
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'region_id' => (int) ($_POST['region_id'] ?? 0)
        ];

        if (empty($data['nom']) || $data['region_id'] <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/villes/edit/' . $id);
            exit;
        }

        $villeModel->update($id, $data);
        $_SESSION['success'] = 'Ville mise à jour avec succès.';
        header('Location: ' . BASE_URL . '/villes');
        exit;
    }

    public function delete(int $id): void
    {
        $villeModel = new Ville($this->db);
        $villeModel->delete($id);
        $_SESSION['success'] = 'Ville supprimée avec succès.';
        header('Location: ' . BASE_URL . '/villes');
        exit;
    }
}
