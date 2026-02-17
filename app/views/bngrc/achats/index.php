<?php
/**
 * @var array $achats
 * @var array $villes
 * @var array $stats
 * @var float $frais
 * @var int|null $villeIdFiltre
 * @var array $donsArgent
 * @var array $statsDons
 */

function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-cart-check me-2"></i>Gestion des Achats
    </h1>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/achats/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nouvel achat
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100 border-primary">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1"><?= $stats['total_achats'] ?? 0 ?></h3>
                <small class="text-muted">Achats effectués</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-success">
            <div class="card-body text-center">
                <h3 class="text-success mb-1"><?= formatMontant($stats['total_montant_achat'] ?? 0) ?></h3>
                <small class="text-muted">Montant des achats</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-warning">
            <div class="card-body text-center">
                <h3 class="text-warning mb-1"><?= formatMontant($stats['total_frais'] ?? 0) ?></h3>
                <small class="text-muted">Total des frais</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100 border-info">
            <div class="card-body text-center">
                <h3 class="text-info mb-1"><?= formatMontant($stats['total_montant_total'] ?? 0) ?></h3>
                <small class="text-muted">Total TTC</small>
            </div>
        </div>
    </div>
</div>

<!-- Fonds argent disponibles -->
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-wallet2 me-2 text-success"></i>Fonds argent disponibles pour les achats</h6>
        <span class="badge bg-success fs-6"><?= formatMontant($statsDons['argent_restant'] ?? 0) ?></span>
    </div>
    <?php if (!empty($donsArgent)): ?>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Don #</th>
                        <th>Date</th>
                        <th class="text-end">Montant initial</th>
                        <th class="text-end">Restant</th>
                        <th class="text-center">Utilisation</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donsArgent as $d): ?>
                        <?php $pct = ($d['montant'] > 0) ? round((1 - $d['restant'] / $d['montant']) * 100, 1) : 0; ?>
                        <tr>
                            <td><span class="badge bg-secondary">#<?= $d['id'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($d['date_don'])) ?></td>
                            <td class="text-end"><?= formatMontant($d['montant']) ?></td>
                            <td class="text-end fw-bold text-success"><?= formatMontant($d['restant']) ?></td>
                            <td class="text-center" style="min-width: 120px;">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: <?= $pct ?>%"></div>
                                </div>
                                <small class="text-muted"><?= $pct ?>% utilisé</small>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/achats/create" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="card-body text-center text-muted py-3">
        <i class="bi bi-wallet2 me-2"></i>Aucun fonds argent disponible
    </div>
    <?php endif; ?>
</div>

<!-- Configuration des frais + Filtre -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Frais d'achat</h6>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/achats/frais" method="POST" class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label for="frais_percent" class="form-label small">Pourcentage de frais</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="frais_percent" name="frais_percent" 
                                   value="<?= $frais ?>" min="0" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-check-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtrer par ville</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= BASE_URL ?>/achats" class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <select class="form-select" name="ville_id" onchange="this.form.submit()">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>" <?= $villeIdFiltre == $ville['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ville['nom']) ?> (<?= htmlspecialchars($ville['region_nom']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($villeIdFiltre): ?>
                        <a href="<?= BASE_URL ?>/achats" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Liste des achats -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>Historique des achats
            <?php if ($villeIdFiltre): ?>
                <span class="badge bg-primary ms-2">Filtré</span>
            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Ville</th>
                        <th>Besoin</th>
                        <th class="text-end">Montant achat</th>
                        <th class="text-end">Frais (%)</th>
                        <th class="text-end">Total TTC</th>
                        <th>Don source</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($achats)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Aucun achat enregistré
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($achats as $achat): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($achat['date_achat'])) ?></td>
                                <td><strong><?= htmlspecialchars($achat['ville_nom']) ?></strong></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($achat['type_besoin']) ?></span></td>
                                <td class="text-end"><?= formatMontant($achat['montant_achat']) ?></td>
                                <td class="text-end"><?= number_format($achat['frais_percent'], 2) ?>%</td>
                                <td class="text-end"><strong><?= formatMontant($achat['montant_total']) ?></strong></td>
                                <td>
                                    <span class="badge bg-success">Don #<?= $achat['don_id'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
