<?php

namespace app\controllers;

use app\models\Ville;
use app\models\Region;
use app\models\Besoin;
use app\models\Don;
use app\models\Dispatch;
use app\models\Achat;
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
        $achatModel = new Achat($this->db);

        // Données du tableau de bord
        $dashboard = $villeModel->getDashboard();
        $statsBesoins = $besoinModel->getStatistiques();
        $statsDons = $donModel->getStatistiques();
        $statsDispatch = $dispatchModel->getStatistiques();
        $regions = $regionModel->getVillesParRegion();
        $modeDistribution = $achatModel->getModeDistribution();

        echo $this->view->renderWithLayout('bngrc/dashboard', [
            'pageTitle' => 'Tableau de bord - BNGRC',
            'dashboard' => $dashboard,
            'statsBesoins' => $statsBesoins,
            'statsDons' => $statsDons,
            'statsDispatch' => $statsDispatch,
            'regions' => $regions,
            'modeDistribution' => $modeDistribution
        ]);
    }

    /**
     * Réinitialiser toutes les données à leur état initial
     */
    public function reinitialiser(): void
    {
        // Vider les tables de résultats (ordre important pour les FK)
        $this->db->exec('DELETE FROM achats');
        $this->db->exec('DELETE FROM dispatch');

        // Remettre les besoins à zéro
        $this->db->exec('UPDATE besoins SET quantite_recue = 0');

        // Remettre les dons à leur état initial
        $this->db->exec('UPDATE dons SET restant = COALESCE(montant, quantite), dispatched = FALSE');

        $_SESSION['success'] = 'Toutes les données ont été réinitialisées à leur état initial.';
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    /**
     * Mettre à jour le mode de distribution
     */
    public function updateMode(): void
    {
        $achatModel = new Achat($this->db);
        $mode = $_POST['mode_distribution'] ?? 'date';
        $modesValides = ['date', 'priorite', 'proportionnel'];

        if (!in_array($mode, $modesValides)) {
            $_SESSION['error'] = 'Mode de distribution invalide.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $achatModel->updateModeDistribution($mode);
        $_SESSION['success'] = 'Mode de distribution mis à jour : ' . $mode . '.';
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
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
