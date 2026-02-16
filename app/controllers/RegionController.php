<?php

namespace app\controllers;

use app\models\Region;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class RegionController
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
        $regionModel = new Region($this->db);
        $regions = $regionModel->getVillesParRegion();

        echo $this->view->renderWithLayout('bngrc/regions/index', [
            'pageTitle' => 'Gestion des Régions',
            'regions' => $regions
        ]);
    }

    public function create(): void
    {
        echo $this->view->renderWithLayout('bngrc/regions/form', [
            'pageTitle' => 'Ajouter une Région',
            'region' => null
        ]);
    }

    public function store(): void
    {
        $regionModel = new Region($this->db);
        
        $data = [
            'nom' => trim($_POST['nom'] ?? '')
        ];

        if (empty($data['nom'])) {
            $_SESSION['error'] = 'Veuillez saisir le nom de la région.';
            header('Location: ' . BASE_URL . '/regions/create');
            exit;
        }

        $regionModel->create($data);
        $_SESSION['success'] = 'Région ajoutée avec succès.';
        header('Location: ' . BASE_URL . '/regions');
        exit;
    }

    public function edit(int $id): void
    {
        $regionModel = new Region($this->db);

        $region = $regionModel->find($id);
        if (!$region) {
            $_SESSION['error'] = 'Région non trouvée.';
            header('Location: ' . BASE_URL . '/regions');
            exit;
        }

        echo $this->view->renderWithLayout('bngrc/regions/form', [
            'pageTitle' => 'Modifier la Région',
            'region' => $region
        ]);
    }

    public function update(int $id): void
    {
        $regionModel = new Region($this->db);
        
        $data = [
            'nom' => trim($_POST['nom'] ?? '')
        ];

        if (empty($data['nom'])) {
            $_SESSION['error'] = 'Veuillez saisir le nom de la région.';
            header('Location: ' . BASE_URL . '/regions/edit/' . $id);
            exit;
        }

        $regionModel->update($id, $data);
        $_SESSION['success'] = 'Région mise à jour avec succès.';
        header('Location: ' . BASE_URL . '/regions');
        exit;
    }

    public function delete(int $id): void
    {
        $regionModel = new Region($this->db);
        $regionModel->delete($id);
        $_SESSION['success'] = 'Région supprimée avec succès.';
        header('Location: ' . BASE_URL . '/regions');
        exit;
    }
}
