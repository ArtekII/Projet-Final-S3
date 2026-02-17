<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Don;
use app\models\Dispatch;
use app\models\Achat;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class RecapController
{
    private PdoWrapper $db;
    private LayoutView $view;

    public function __construct(PdoWrapper $db, LayoutView $view)
    {
        $this->db = $db;
        $this->view = $view;
    }

    /**
     * Page de récapitulation
     */
    public function index(): void
    {
        $data = $this->getRecapData();

        echo $this->view->renderWithLayout('bngrc/recap/index', array_merge($data, [
            'pageTitle' => 'Récapitulation - BNGRC'
        ]));
    }

    /**
     * Endpoint API pour rafraîchir les données en Ajax
     */
    public function apiData(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->getRecapData());
        exit;
    }

    /**
     * Récupère toutes les données de récapitulation
     */
    private function getRecapData(): array
    {
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);
        $dispatchModel = new Dispatch($this->db);
        $achatModel = new Achat($this->db);

        $statsBesoins = $besoinModel->getStatistiques();
        $statsDons = $donModel->getStatistiques();
        $statsDispatch = $dispatchModel->getStatistiques();
        $statsAchats = $achatModel->getStatistiques();

        // Besoins par type
        $besoinsParType = $besoinModel->getBesoinsParType();

        return [
            'statsBesoins' => $statsBesoins,
            'statsDons' => $statsDons,
            'statsDispatch' => $statsDispatch,
            'statsAchats' => $statsAchats,
            'besoinsParType' => $besoinsParType
        ];
    }
}
