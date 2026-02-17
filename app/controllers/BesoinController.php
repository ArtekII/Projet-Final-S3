<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Type;
use app\models\Ville;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class BesoinController
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
        $besoinModel = new Besoin($this->db);
        $besoins = $besoinModel->findAll();

        echo $this->view->renderWithLayout('bngrc/besoins/index', [
            'pageTitle' => 'Gestion des Besoins',
            'besoins' => $besoins
        ]);
    }

    public function create(): void
    {
        $villeModel = new Ville($this->db);
        $typeModel = new Type($this->db);

        $villes = $villeModel->findAll();
        $typesBesoins = $typeModel->findAll();

        echo $this->view->renderWithLayout('bngrc/besoins/form', [
            'pageTitle' => 'Saisir un Besoin',
            'besoin' => null,
            'villes' => $villes,
            'typesBesoins' => $typesBesoins
        ]);
    }

    public function store(): void
    {
        $besoinModel = new Besoin($this->db);
        $typeModel = new Type($this->db);
        
        $data = [
            'ville_id' => (int) ($_POST['ville_id'] ?? 0),
            'type_id' => (int) ($_POST['type_id'] ?? 0),
            'designation' => null,
            'quantite_demandee' => (float) ($_POST['quantite_demandee'] ?? 0),
            'prix_unitaire' => (float) ($_POST['prix_unitaire'] ?? 0),
            'date_besoin' => !empty($_POST['date_besoin']) ? $_POST['date_besoin'] : null,
            'ordre' => !empty($_POST['ordre']) ? (int) $_POST['ordre'] : null
        ];
        $designation = trim($_POST['designation'] ?? '');
        $data['designation'] = $designation !== '' ? $designation : null;
        $type = $typeModel->find($data['type_id']);

        if ($data['ville_id'] <= 0 || $data['type_id'] <= 0 || !$type || $data['quantite_demandee'] <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/besoins/create');
            exit;
        }

        $besoinModel->create($data);
        $_SESSION['success'] = 'Besoin enregistré avec succès.';
        header('Location: ' . BASE_URL . '/besoins');
        exit;
    }

    public function edit(int $id): void
    {
        $besoinModel = new Besoin($this->db);
        $villeModel = new Ville($this->db);
        $typeModel = new Type($this->db);

        $besoin = $besoinModel->find($id);
        if (!$besoin) {
            $_SESSION['error'] = 'Besoin non trouvé.';
            header('Location: ' . BASE_URL . '/besoins');
            exit;
        }

        $villes = $villeModel->findAll();
        $typesBesoins = $typeModel->findAll();

        echo $this->view->renderWithLayout('bngrc/besoins/form', [
            'pageTitle' => 'Modifier le Besoin',
            'besoin' => $besoin,
            'villes' => $villes,
            'typesBesoins' => $typesBesoins
        ]);
    }

    public function update(int $id): void
    {
        $besoinModel = new Besoin($this->db);
        $typeModel = new Type($this->db);
        
        $data = [
            'ville_id' => (int) ($_POST['ville_id'] ?? 0),
            'type_id' => (int) ($_POST['type_id'] ?? 0),
            'designation' => null,
            'quantite_demandee' => (float) ($_POST['quantite_demandee'] ?? 0),
            'prix_unitaire' => (float) ($_POST['prix_unitaire'] ?? 0),
            'date_besoin' => !empty($_POST['date_besoin']) ? $_POST['date_besoin'] : null,
            'ordre' => !empty($_POST['ordre']) ? (int) $_POST['ordre'] : null
        ];
        $designation = trim($_POST['designation'] ?? '');
        $data['designation'] = $designation !== '' ? $designation : null;
        $type = $typeModel->find($data['type_id']);

        if ($data['ville_id'] <= 0 || $data['type_id'] <= 0 || !$type || $data['quantite_demandee'] <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/besoins/edit/' . $id);
            exit;
        }

        $besoinModel->update($id, $data);
        $_SESSION['success'] = 'Besoin mis à jour avec succès.';
        header('Location: ' . BASE_URL . '/besoins');
        exit;
    }

    public function delete(int $id): void
    {
        $besoinModel = new Besoin($this->db);
        $besoinModel->delete($id);
        $_SESSION['success'] = 'Besoin supprimé avec succès.';
        header('Location: ' . BASE_URL . '/besoins');
        exit;
    }
}
