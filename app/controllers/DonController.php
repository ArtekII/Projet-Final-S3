<?php

namespace app\controllers;

use app\models\Don;
use app\models\Type;
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
        $typeModel = new Type($this->db);
        $typesDons = $typeModel->findAll();

        echo $this->view->renderWithLayout('bngrc/dons/form', [
            'pageTitle' => 'Enregistrer un Don',
            'don' => null,
            'typesDons' => $typesDons
        ]);
    }

    public function store(): void
    {
        $donModel = new Don($this->db);
        $typeModel = new Type($this->db);
        
        $typeId = (int) ($_POST['type_id'] ?? 0);
        $type = $typeModel->find($typeId);
        $typeNom = $type ? strtolower(trim((string) $type['nom'])) : '';
        $data = [
            'type_id' => $typeId,
            'designation' => $_POST['designation'] ?? null,
            'montant' => $_POST['montant'] ?? null,
            'quantite' => $_POST['quantite'] ?? null,
        ];

        if ($typeId <= 0 || !$type) {
            $_SESSION['error'] = 'Veuillez sélectionner le type de don.';
            header('Location: ' . BASE_URL . '/dons/create');
            exit;
        }

        if ($typeNom !== 'argent' && empty(trim($data['designation'] ?? ''))) {
            $_SESSION['error'] = 'Veuillez saisir la désignation du don.';
            header('Location: ' . BASE_URL . '/dons/create');
            exit;
        }

        if ($typeNom === 'argent' && (empty($data['montant']) || (float)$data['montant'] <= 0)) {
            $_SESSION['error'] = 'Veuillez saisir un montant valide pour un don en argent.';
            header('Location: ' . BASE_URL . '/dons/create');
            exit;
        }

        if ($typeNom !== 'argent' && (empty($data['quantite']) || (float)$data['quantite'] <= 0)) {
            $_SESSION['error'] = 'Veuillez saisir une quantité valide.';
            header('Location: ' . BASE_URL . '/dons/create');
            exit;
        }

        if ($typeNom === 'argent') {
            $data['designation'] = null;
            $data['quantite'] = null;
        } else {
            $data['montant'] = null;
        }

        $donModel->create($data);
        $_SESSION['success'] = 'Don enregistré avec succès.';
        header('Location: ' . BASE_URL . '/dons');
        exit;
    }

    public function edit(int $id): void
    {
        $donModel = new Don($this->db);
        $typeModel = new Type($this->db);

        $don = $donModel->find($id);
        if (!$don) {
            $_SESSION['error'] = 'Don non trouvé.';
            header('Location: ' . BASE_URL . '/dons');
            exit;
        }

        $typesDons = $typeModel->findAll();

        echo $this->view->renderWithLayout('bngrc/dons/form', [
            'pageTitle' => 'Modifier le Don',
            'don' => $don,
            'typesDons' => $typesDons
        ]);
    }

    public function update(int $id): void
    {
        $donModel = new Don($this->db);
        $typeModel = new Type($this->db);
        
        $typeId = (int) ($_POST['type_id'] ?? 0);
        $type = $typeModel->find($typeId);
        $typeNom = $type ? strtolower(trim((string) $type['nom'])) : '';
        $data = [
            'type_id' => $typeId,
            'designation' => $_POST['designation'] ?? null,
            'montant' => $_POST['montant'] ?? null,
            'quantite' => $_POST['quantite'] ?? null,
        ];

        if ($typeId <= 0 || !$type) {
            $_SESSION['error'] = 'Veuillez sélectionner le type de don.';
            header('Location: ' . BASE_URL . '/dons/edit/' . $id);
            exit;
        }

        if ($typeNom !== 'argent' && empty(trim($data['designation'] ?? ''))) {
            $_SESSION['error'] = 'Veuillez saisir la désignation du don.';
            header('Location: ' . BASE_URL . '/dons/edit/' . $id);
            exit;
        }

        if ($typeNom === 'argent' && (empty($data['montant']) || (float)$data['montant'] <= 0)) {
            $_SESSION['error'] = 'Veuillez saisir un montant valide.';
            header('Location: ' . BASE_URL . '/dons/edit/' . $id);
            exit;
        }

        if ($typeNom !== 'argent' && (empty($data['quantite']) || (float)$data['quantite'] <= 0)) {
            $_SESSION['error'] = 'Veuillez saisir une quantité valide.';
            header('Location: ' . BASE_URL . '/dons/edit/' . $id);
            exit;
        }

        if ($typeNom === 'argent') {
            $data['designation'] = null;
            $data['quantite'] = null;
        } else {
            $data['montant'] = null;
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
