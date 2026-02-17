<?php
/**
 * @var array $dashboard
 * @var array $statsBesoins
 * @var array $statsDons
 * @var array $statsDispatch
 * @var array $regions
 */

// Fonction pour formater les montants
function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}

function formatPourcentage($value, $total): string {
    if ($total <= 0) return '0%';
    return number_format(($value / $total) * 100, 1) . '%';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
    </h1>
    <div class="btn-toolbar">
        <a href="<?= BASE_URL ?>/dispatch/simuler" class="btn btn-primary">
            <i class="bi bi-play-fill me-1"></i>Simuler le dispatch
        </a>
    </div>
</div>

<!-- Statistiques globales -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Valeur totale des besoins</h6>
                        <h3 class="mb-0"><?= formatMontant($statsBesoins['valeur_totale_demandee'] ?? 0) ?></h3>
                        <small class="text-muted"><?= $statsBesoins['total_besoins'] ?? 0 ?> besoins enregistrés</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total dons argent</h6>
                        <h3 class="mb-0"><?= formatMontant($statsDons['total_argent'] ?? 0) ?></h3>
                        <small class="text-muted"><?= $statsDons['total_dons'] ?? 0 ?> dons reçus</small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-gift"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Dons en attente</h6>
                        <h3 class="mb-0"><?= $statsDons['dons_en_attente'] ?? 0 ?></h3>
                        <small class="text-muted">Non dispatchés</small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Valeur dispatchée</h6>
                        <h3 class="mb-0"><?= formatMontant($statsDispatch['valeur_totale_dispatchee'] ?? 0) ?></h3>
                        <small class="text-muted"><?= $statsDispatch['nb_villes_beneficiaires'] ?? 0 ?> villes bénéficiaires</small>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Barre de progression globale -->
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title">Progression de la couverture des besoins</h6>
        <?php
        $valeurDemandee = (float)($statsBesoins['valeur_totale_demandee'] ?? 0);
        $valeurRecue = (float)($statsBesoins['valeur_totale_recue'] ?? 0);
        $pourcentage = $valeurDemandee > 0 ? ($valeurRecue / $valeurDemandee) * 100 : 0;
        ?>
        <div class="progress" style="height: 25px;">
            <div class="progress-bar bg-success" role="progressbar" 
                 style="width: <?= min($pourcentage, 100) ?>%;" 
                 aria-valuenow="<?= $pourcentage ?>" aria-valuemin="0" aria-valuemax="100">
                <?= number_format($pourcentage, 1) ?>%
            </div>
        </div>
        <div class="d-flex justify-content-between mt-2 text-muted small">
            <span>Couvert: <?= formatMontant($valeurRecue) ?></span>
            <span>Restant: <?= formatMontant($valeurDemandee - $valeurRecue) ?></span>
        </div>
    </div>
</div>

<!-- Tableau des villes -->
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-building me-2"></i>Situation par ville
            </h5>
            <a href="<?= BASE_URL ?>/villes" class="btn btn-sm btn-outline-primary">
                Gérer les villes
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ville</th>
                        <th>Région</th>
                        <th class="text-end">Valeur besoins</th>
                        <th class="text-end">Valeur reçue</th>
                        <th style="width: 200px;">Couverture</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dashboard)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Aucune donnée disponible
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dashboard as $ville): ?>
                            <?php
                            $valeurBesoins = (float)($ville['valeur_besoins'] ?? 0);
                            $valeurRecue = (float)($ville['valeur_recue'] ?? 0);
                            $couverture = $valeurBesoins > 0 ? ($valeurRecue / $valeurBesoins) * 100 : 0;
                            $barClass = $couverture >= 80 ? 'bg-success' : ($couverture >= 50 ? 'bg-warning' : 'bg-danger');
                            ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($ville['ville_nom']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($ville['region_nom']) ?></td>
                                <td class="text-end"><?= formatMontant($valeurBesoins) ?></td>
                                <td class="text-end"><?= formatMontant($valeurRecue) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar <?= $barClass ?>" 
                                                 style="width: <?= min($couverture, 100) ?>%"></div>
                                        </div>
                                        <span class="small text-muted"><?= number_format($couverture, 0) ?>%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="<?= BASE_URL ?>/dashboard/ville/<?= $ville['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Voir détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Résumé par région -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-map me-2"></i>Résumé par région
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($regions as $region): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="border rounded p-3">
                        <h6 class="mb-2"><?= htmlspecialchars($region['nom']) ?></h6>
                        <div class="text-muted small">
                            <span><i class="bi bi-building me-1"></i><?= $region['nb_villes'] ?> villes</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
