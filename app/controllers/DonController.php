<?php

namespace app\controllers;

use app\models\Don;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class DonController
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
        $donModel = new Don($this->db);
        $dons = $donModel->findAll();

        echo $this->view->renderWithLayout('bngrc/dons/index', [
            'pageTitle' => 'Gestion des Dons',
            'dons' => $dons
        ]);
    }

    public function create(): void
    {
        $donModel = new Don($this->db);
        $typesDons = $donModel->getTypesDons();

        echo $this->view->renderWithLayout('bngrc/dons/form', [
            'pageTitle' => 'Enregistrer un Don',
            'don' => null,
            'typesDons' => $typesDons
        ]);
    }

    public function store(): void
    {
        $donModel = new Don($this->db);
        
        $data = [
            'type' => trim($_POST['type'] ?? ''),
            'quantite' => (float) ($_POST['quantite'] ?? 0)
        ];

        if (empty($data['type']) || $data['quantite'] <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/dons/create');
            exit;
        }

        $donModel->create($data);
        $_SESSION['success'] = 'Don enregistré avec succès.';
        header('Location: ' . BASE_URL . '/dons');
        exit;
    }

    public function edit(int $id): void
    {
        $donModel = new Don($this->db);

        $don = $donModel->find($id);
        if (!$don) {
            $_SESSION['error'] = 'Don non trouvé.';
            header('Location: ' . BASE_URL . '/dons');
            exit;
        }

        $typesDons = $donModel->getTypesDons();

        echo $this->view->renderWithLayout('bngrc/dons/form', [
            'pageTitle' => 'Modifier le Don',
            'don' => $don,
            'typesDons' => $typesDons
        ]);
    }

    public function update(int $id): void
    {
        $donModel = new Don($this->db);
        
        $data = [
            'type' => trim($_POST['type'] ?? ''),
            'quantite' => (float) ($_POST['quantite'] ?? 0)
        ];

        if (empty($data['type']) || $data['quantite'] <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/dons/edit/' . $id);
            exit;
        }

        $donModel->update($id, $data);
        $_SESSION['success'] = 'Don mis à jour avec succès.';
        header('Location: ' . BASE_URL . '/dons');
        exit;
    }

    public function delete(int $id): void
    {
        $donModel = new Don($this->db);
        $donModel->delete($id);
        $_SESSION['success'] = 'Don supprimé avec succès.';
        header('Location: ' . BASE_URL . '/dons');
        exit;
    }
}
