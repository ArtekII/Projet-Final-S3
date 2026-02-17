<?php

namespace app\controllers;

use app\models\Dispatch;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class DispatchController
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
        $dispatchModel = new Dispatch($this->db);
        $dispatches = $dispatchModel->findAll();
        $stats = $dispatchModel->getStatistiques();

        echo $this->view->renderWithLayout('bngrc/dispatch/index', [
            'pageTitle' => 'Historique des Dispatches',
            'dispatches' => $dispatches,
            'stats' => $stats
        ]);
    }

    public function simuler(): void
    {
        $dispatchModel = new Dispatch($this->db);
        $attributions = $dispatchModel->simulerDispatch();

        echo $this->view->renderWithLayout('bngrc/dispatch/resultat', [
            'pageTitle' => 'Résultat du Dispatch',
            'attributions' => $attributions
        ]);
    }

    public function confirmerSimulation(): void
    {
        $dispatchModel = new Dispatch($this->db);
        $attributions = $dispatchModel->simulerDispatch();

        if (count($attributions) > 0) {
            $_SESSION['success'] = count($attributions) . ' attribution(s) effectuée(s) avec succès.';
        } else {
            $_SESSION['info'] = 'Aucune attribution à effectuer. Soit tous les dons sont déjà dispatchés, soit il n\'y a pas de besoins correspondants.';
        }

        header('Location: ' . BASE_URL . '/dispatch');
        exit;
    }
}
