<?php

namespace app\controllers;

use app\models\Achat;
use app\models\Besoin;
use app\models\Don;
use app\models\Ville;
use app\template\LayoutView;
use flight\database\PdoWrapper;

class AchatController
{
    private PdoWrapper $db;
    private LayoutView $view;

    public function __construct(PdoWrapper $db, LayoutView $view)
    {
        $this->db = $db;
        $this->view = $view;
    }

    /**
     * Liste des achats (filtrable par ville)
     */
    public function index(): void
    {
        $achatModel = new Achat($this->db);
        $villeModel = new Ville($this->db);
        $donModel = new Don($this->db);

        $villeId = isset($_GET['ville_id']) && $_GET['ville_id'] !== '' ? (int) $_GET['ville_id'] : null;
        $achats = $achatModel->findAll($villeId);
        $villes = $villeModel->findAll();
        $stats = $achatModel->getStatistiques();
        $frais = $achatModel->getFraisPercent();
        $donsArgent = $donModel->getDonsArgentDisponibles();
        $statsDons = $donModel->getStatistiques();

        echo $this->view->renderWithLayout('bngrc/achats/index', [
            'pageTitle' => 'Gestion des Achats',
            'achats' => $achats,
            'villes' => $villes,
            'stats' => $stats,
            'frais' => $frais,
            'villeIdFiltre' => $villeId,
            'donsArgent' => $donsArgent,
            'statsDons' => $statsDons
        ]);
    }

    /**
     * Formulaire d'achat depuis un besoin restant
     */
    public function create(): void
    {
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);
        $achatModel = new Achat($this->db);

        $besoinId = isset($_GET['besoin_id']) ? (int) $_GET['besoin_id'] : 0;
        $besoin = $besoinId > 0 ? $besoinModel->find($besoinId) : null;

        $besoinsRestants = $besoinModel->getBesoinsNonSatisfaits();
        $donsArgent = $donModel->getDonsArgentDisponibles();
        $frais = $achatModel->getFraisPercent();

        echo $this->view->renderWithLayout('bngrc/achats/form', [
            'pageTitle' => 'Effectuer un Achat',
            'besoin' => $besoin,
            'besoinsRestants' => $besoinsRestants,
            'donsArgent' => $donsArgent,
            'frais' => $frais
        ]);
    }

    /**
     * Enregistrer un achat
     */
    public function store(): void
    {
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);
        $achatModel = new Achat($this->db);

        $besoinId = (int) ($_POST['besoin_id'] ?? 0);
        $donId = (int) ($_POST['don_id'] ?? 0);
        $quantite = (float) ($_POST['quantite'] ?? 0);

        // Validations de base
        if ($besoinId <= 0 || $donId <= 0 || $quantite <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/achats/create');
            exit;
        }

        $besoin = $besoinModel->find($besoinId);
        $don = $donModel->find($donId);

        if (!$besoin || !$don) {
            $_SESSION['error'] = 'Besoin ou don introuvable.';
            header('Location: ' . BASE_URL . '/achats/create');
            exit;
        }

        // Vérifier que le don est bien en argent
        if ($don['type_don'] !== 'argent') {
            $_SESSION['error'] = 'Seuls les dons en argent peuvent être utilisés pour les achats.';
            header('Location: ' . BASE_URL . '/achats/create');
            exit;
        }

        // Vérifier qu'il n'y a pas de don en nature/matériaux pour ce type de besoin
        $donsNatureDisponibles = $this->verifierDonsNatureExistants($besoin['type_besoin']);
        if (!empty($donsNatureDisponibles)) {
            $_SESSION['error'] = 'Impossible d\'acheter : il reste des dons en nature/matériaux non dispatchés pour le type "' 
                . htmlspecialchars($besoin['type_besoin']) . '". Veuillez d\'abord dispatcher ces dons.';
            header('Location: ' . BASE_URL . '/achats/create?besoin_id=' . $besoinId);
            exit;
        }

        // Vérifier la quantité restante du besoin
        $quantiteRestante = $besoin['quantite_demandee'] - $besoin['quantite_recue'];
        if ($quantite > $quantiteRestante) {
            $_SESSION['error'] = 'La quantité demandée (' . number_format($quantite) . ') dépasse le besoin restant (' . number_format($quantiteRestante) . ').';
            header('Location: ' . BASE_URL . '/achats/create?besoin_id=' . $besoinId);
            exit;
        }

        // Calcul du montant
        $frais = $achatModel->getFraisPercent();
        $montantAchat = $quantite * $besoin['prix_unitaire'];
        $montantTotal = $montantAchat * (1 + $frais / 100);

        // Vérifier le solde du don
        if ($montantTotal > (float) $don['restant']) {
            $_SESSION['error'] = 'Le montant total (' . number_format($montantTotal, 0, ',', ' ') . ' Ar) dépasse le solde restant du don (' 
                . number_format($don['restant'], 0, ',', ' ') . ' Ar).';
            header('Location: ' . BASE_URL . '/achats/create?besoin_id=' . $besoinId);
            exit;
        }

        // Enregistrer l'achat
        $achatModel->create([
            'don_id' => $donId,
            'ville_id' => $besoin['ville_id'],
            'besoin_id' => $besoinId,
            'montant_achat' => $montantAchat,
            'frais_percent' => $frais,
            'montant_total' => $montantTotal
        ]);

        // Mettre à jour la quantité reçue du besoin
        $besoinModel->updateQuantiteRecue($besoinId, $quantite);

        // Mettre à jour le restant du don
        $donModel->updateRestant($donId, $montantTotal);

        $_SESSION['success'] = 'Achat enregistré avec succès : ' . number_format($quantite) . ' ' 
            . $besoin['type_besoin'] . ' pour ' . number_format($montantTotal, 0, ',', ' ') . ' Ar (dont ' 
            . $frais . '% de frais).';
        header('Location: ' . BASE_URL . '/achats');
        exit;
    }

    /**
     * Mettre à jour le frais d'achat
     */
    public function updateFrais(): void
    {
        $achatModel = new Achat($this->db);
        $frais = (float) ($_POST['frais_percent'] ?? 10.0);

        if ($frais < 0 || $frais > 100) {
            $_SESSION['error'] = 'Le pourcentage de frais doit être entre 0 et 100.';
            header('Location: ' . BASE_URL . '/achats');
            exit;
        }

        $achatModel->updateFraisPercent($frais);
        $_SESSION['success'] = 'Frais d\'achat mis à jour à ' . $frais . '%.';
        header('Location: ' . BASE_URL . '/achats');
        exit;
    }

    /**
     * Vérifie s'il existe des dons en nature/matériaux non dispatchés
     * dont le type correspond au type de besoin
     */
    private function verifierDonsNatureExistants(string $typeBesoin): array
    {
        $donModel = new Don($this->db);
        $dons = $donModel->getDonsNonDispatches();
        
        // Note : les dons nature/materiaux n'ont pas de "type_besoin" direct,
        // mais le sujet dit "erreur si l'achat existe encore dans les dons restants"
        // On vérifie donc s'il reste des dons nature/materiaux non dispatchés
        return $dons;
    }
}
