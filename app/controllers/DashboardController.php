<?php

namespace app\controllers;

use app\models\Ville;
use app\models\Region;
use app\models\Besoin;
use app\models\Don;
use app\models\Dispatch;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class DashboardController
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
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);
        $dispatchModel = new Dispatch($this->db);
        $regionModel = new Region($this->db);

        // Données du tableau de bord
        $dashboard = $villeModel->getDashboard();
        $statsBesoins = $besoinModel->getStatistiques();
        $statsDons = $donModel->getStatistiques();
        $statsDispatch = $dispatchModel->getStatistiques();
        $regions = $regionModel->getVillesParRegion();

        echo $this->view->renderWithLayout('bngrc/dashboard', [
            'pageTitle' => 'Tableau de bord - BNGRC',
            'dashboard' => $dashboard,
            'statsBesoins' => $statsBesoins,
            'statsDons' => $statsDons,
            'statsDispatch' => $statsDispatch,
            'regions' => $regions
        ]);
    }

    public function detailVille(int $id): void
    {
        $villeModel = new Ville($this->db);
        $dispatchModel = new Dispatch($this->db);

        $ville = $villeModel->find($id);
        if (!$ville) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $besoins = $villeModel->getBesoinsDetails($id);
        $historique = $dispatchModel->getHistoriqueParVille($id);

        echo $this->view->renderWithLayout('bngrc/ville_detail', [
            'pageTitle' => 'Détails - ' . $ville['nom'],
            'ville' => $ville,
            'besoins' => $besoins,
            'historique' => $historique
        ]);
    }
}
