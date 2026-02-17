<?php

namespace app\controllers;

use app\models\Achat;
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
        $achatModel = new Achat($this->db);
        $dispatches = $dispatchModel->findAll();
        $stats = $dispatchModel->getStatistiques();
        $modeDistribution = $achatModel->getModeDistribution();

        echo $this->view->renderWithLayout('bngrc/dispatch/index', [
            'pageTitle' => 'Historique des Dispatches',
            'dispatches' => $dispatches,
            'stats' => $stats,
            'modeDistribution' => $modeDistribution
        ]);
    }

    /**
     * Simulation : aperçu sans persister en BD
     */
    public function simuler(): void
    {
        $dispatchModel = new Dispatch($this->db);
        $achatModel = new Achat($this->db);

        // Le mode peut venir du query string ou de la config
        $mode = $_GET['mode'] ?? $achatModel->getModeDistribution();
        $modesValides = ['date', 'plus_petit', 'proportionnel'];
        if (!in_array($mode, $modesValides)) {
            $mode = 'date';
        }

        $attributions = $dispatchModel->simulerDispatch($mode);

        echo $this->view->renderWithLayout('bngrc/dispatch/resultat', [
            'pageTitle' => 'Simulation du Dispatch',
            'attributions' => $attributions,
            'isSimulation' => true,
            'modeDistribution' => $mode
        ]);
    }

    /**
     * Validation : persiste réellement le dispatch en BD
     */
    public function valider(): void
    {
        $dispatchModel = new Dispatch($this->db);
        $achatModel = new Achat($this->db);

        $mode = $_POST['mode'] ?? $achatModel->getModeDistribution();
        $modesValides = ['date', 'plus_petit', 'proportionnel'];
        if (!in_array($mode, $modesValides)) {
            $mode = 'date';
        }

        $attributions = $dispatchModel->validerDispatch($mode);

        if (count($attributions) > 0) {
            $_SESSION['success'] = count($attributions) . ' attribution(s) effectuée(s) avec succès (mode: ' . $mode . ').';
        } else {
            $_SESSION['info'] = 'Aucune attribution à effectuer. Soit tous les dons sont déjà dispatchés, soit il n\'y a pas de besoins correspondants.';
        }

        header('Location: ' . BASE_URL . '/dispatch');
        exit;
    }
}
