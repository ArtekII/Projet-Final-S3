<?php
/**
 * @var array $dispatches
 * @var array $stats
 */

function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-arrow-left-right me-2"></i>Dispatch des Dons
    </h1>
    <a href="<?= BASE_URL ?>/dispatch/simuler" class="btn btn-primary">
        <i class="bi bi-play-fill me-1"></i>Lancer le dispatch
    </a>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100 border-primary">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1"><?= $stats['total_dispatches'] ?? 0 ?></h3>
                <small class="text-muted">Attributions effectuées</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-success">
            <div class="card-body text-center">
                <h3 class="text-success mb-1"><?= formatMontant($stats['valeur_totale_dispatchee'] ?? 0) ?></h3>
                <small class="text-muted">Valeur dispatchée</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-info">
            <div class="card-body text-center">
                <h3 class="text-info mb-1"><?= $stats['nb_dons_dispatches'] ?? 0 ?></h3>
                <small class="text-muted">Dons dispatchés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-warning">
            <div class="card-body text-center">
                <h3 class="text-warning mb-1"><?= $stats['nb_villes_beneficiaires'] ?? 0 ?></h3>
                <small class="text-muted">Villes bénéficiaires</small>
            </div>
        </div>
    </div>
</div>

<!-- Historique des dispatches -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-clock-history me-2"></i>Historique des distributions
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date dispatch</th>
                        <th>Type don</th>
                        <th class="text-end">Quantité</th>
                        <th>Ville bénéficiaire</th>
                        <th>Région</th>
                        <th class="text-end">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dispatches)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Aucun dispatch effectué
                                <br><small>Cliquez sur "Lancer le dispatch" pour attribuer les dons aux villes</small>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dispatches as $dispatch): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($dispatch['date_dispatch'])) ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($dispatch['don_type']) ?></span></td>
                                <td class="text-end"><?= number_format($dispatch['quantite_attribuee']) ?></td>
                                <td><?= htmlspecialchars($dispatch['ville_nom']) ?></td>
                                <td><?= htmlspecialchars($dispatch['region_nom']) ?></td>
                                <td class="text-end"><?= formatMontant($dispatch['quantite_attribuee'] * ($dispatch['prix_unitaire'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
