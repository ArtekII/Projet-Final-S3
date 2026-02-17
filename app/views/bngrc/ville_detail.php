<?php
/**
 * @var array $ville
 * @var array $besoins
 * @var array $historique
 */

function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Tableau de bord</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($ville['nom']) ?></li>
            </ol>
        </nav>
        <h1 class="h2">
            <i class="bi bi-building me-2"></i><?= htmlspecialchars($ville['nom']) ?>
        </h1>
        <p class="text-muted mb-0">
            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($ville['region_nom']) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
</div>

<!-- Besoins de la ville -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-clipboard-check me-2"></i>Besoins de la ville
            </h5>
            <a href="<?= BASE_URL ?>/besoins/create" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Ajouter un besoin
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Type de besoin</th>
                        <th class="text-end">Demandé</th>
                        <th class="text-end">Reçu</th>
                        <th class="text-end">Restant</th>
                        <th class="text-end">Prix unitaire</th>
                        <th class="text-end">Valeur totale</th>
                        <th style="width: 150px;">Couverture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($besoins)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucun besoin enregistré pour cette ville
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($besoins as $besoin): ?>
                            <?php
                            $couverture = $besoin['quantite_demandee'] > 0 
                                ? ($besoin['quantite_recue'] / $besoin['quantite_demandee']) * 100 
                                : 0;
                            $barClass = $couverture >= 80 ? 'bg-success' : ($couverture >= 50 ? 'bg-warning' : 'bg-danger');
                            $quantiteRestante = $besoin['quantite_demandee'] - $besoin['quantite_recue'];
                            ?>
                            <tr>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($besoin['type_besoin']) ?></span></td>
                                <td class="text-end"><?= number_format($besoin['quantite_demandee']) ?></td>
                                <td class="text-end"><?= number_format($besoin['quantite_recue']) ?></td>
                                <td class="text-end">
                                    <?php if ($quantiteRestante > 0): ?>
                                        <span class="text-danger"><?= number_format($quantiteRestante) ?></span>
                                    <?php else: ?>
                                        <span class="text-success"><i class="bi bi-check-circle"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= formatMontant($besoin['prix_unitaire']) ?></td>
                                <td class="text-end"><?= formatMontant($besoin['quantite_demandee'] * $besoin['prix_unitaire']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar <?= $barClass ?>" 
                                                 style="width: <?= min($couverture, 100) ?>%"></div>
                                        </div>
                                        <span class="small"><?= number_format($couverture, 0) ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Historique des distributions -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-clock-history me-2"></i>Historique des distributions reçues
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type don</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Aucune distribution reçue
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($historique as $dist): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($dist['date_dispatch'])) ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($dist['don_type']) ?></span></td>
                                <td class="text-end"><?= number_format($dist['quantite_attribuee']) ?></td>
                                <td class="text-end"><?= formatMontant($dist['quantite_attribuee'] * ($dist['prix_unitaire'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
