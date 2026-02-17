<?php
/**
 * @var array $attributions
 * @var bool $isSimulation
 * @var string $modeDistribution
 */

$modesLabels = [
    'date' => 'Par date (FIFO)',
    'plus_petit' => 'Plus petit besoin d\'abord',
    'proportionnel' => 'Proportionnel'
];

function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dispatch">Dispatch</a></li>
                <li class="breadcrumb-item active"><?= $isSimulation ? 'Simulation' : 'Résultat' ?></li>
            </ol>
        </nav>
        <h1 class="h2">
            <?php if ($isSimulation): ?>
                <i class="bi bi-eye me-2 text-info"></i>Simulation du Dispatch
            <?php else: ?>
                <i class="bi bi-check-circle me-2 text-success"></i>Résultat du Dispatch
            <?php endif; ?>
        </h1>
    </div>
</div>

<?php if ($isSimulation && !empty($attributions)): ?>
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Ceci est une simulation.</strong> Les données n'ont pas été enregistrées. 
        Cliquez sur <strong>"Valider le dispatch"</strong> pour confirmer les attributions.
        <br><small><i class="bi bi-sliders me-1"></i>Mode de distribution : <strong><?= $modesLabels[$modeDistribution] ?? $modeDistribution ?></strong></small>
    </div>
<?php endif; ?>

<?php if (empty($attributions)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-info-circle display-1 text-info mb-3"></i>
            <h4>Aucune attribution à effectuer</h4>
            <p class="text-muted mb-4">
                Cela peut signifier que :
            </p>
            <ul class="list-unstyled text-muted">
                <li><i class="bi bi-check me-2"></i>Tous les dons nature/matériaux ont déjà été dispatchés</li>
                <li><i class="bi bi-check me-2"></i>Il n'y a pas de besoins correspondants aux dons disponibles</li>
                <li><i class="bi bi-check me-2"></i>Tous les besoins sont déjà satisfaits</li>
            </ul>
            <a href="<?= BASE_URL ?>/dispatch" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-1"></i>Retour à l'historique
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-<?= $isSimulation ? 'warning' : 'success' ?> mb-4">
        <i class="bi bi-<?= $isSimulation ? 'eye-fill' : 'check-circle-fill' ?> me-2"></i>
        <strong><?= count($attributions) ?> attribution(s)</strong> 
        <?= $isSimulation ? 'prévue(s) dans cette simulation' : 'effectuée(s) avec succès !' ?>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>Détail des attributions
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Don #</th>
                            <th>Type don</th>
                            <th>Besoin</th>
                            <th class="text-end">Quantité attribuée</th>
                            <th class="text-end">Valeur</th>
                            <th>Ville</th>
                            <th>Région</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalValeur = 0; ?>
                        <?php foreach ($attributions as $attr): ?>
                            <?php $valeur = $attr['quantite_attribuee'] * ($attr['prix_unitaire'] ?? 0); $totalValeur += $valeur; ?>
                            <tr>
                                <td><span class="badge bg-secondary">#<?= $attr['don_id'] ?></span></td>
                                <td>
                                    <?php
                                    $badgeClass = $attr['type_don'] === 'nature' ? 'bg-primary' : 'bg-info';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($attr['type_don']) ?></span>
                                </td>
                                <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($attr['type_besoin'] ?? '') ?></span></td>
                                <td class="text-end"><?= number_format($attr['quantite_attribuee']) ?></td>
                                <td class="text-end"><?= formatMontant($valeur) ?></td>
                                <td><?= htmlspecialchars($attr['ville']) ?></td>
                                <td><?= htmlspecialchars($attr['region']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-success fw-bold">
                            <td colspan="4" class="text-end">Total</td>
                            <td class="text-end"><?= formatMontant($totalValeur) ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <?php if ($isSimulation): ?>
            <form action="<?= BASE_URL ?>/dispatch/valider" method="POST">
                <input type="hidden" name="mode" value="<?= htmlspecialchars($modeDistribution ?? 'date') ?>">
                <button type="submit" class="btn btn-success btn-lg" 
                        onclick="return confirm('Êtes-vous sûr de vouloir valider ce dispatch ? Cette action est irréversible.')">
                    <i class="bi bi-check-circle me-1"></i>Valider le dispatch
                </button>
            </form>
            <a href="<?= BASE_URL ?>/dispatch/simuler" class="btn btn-outline-info btn-lg">
                <i class="bi bi-arrow-clockwise me-1"></i>Relancer la simulation
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/dashboard" class="btn btn-primary">
                <i class="bi bi-speedometer2 me-1"></i>Voir le tableau de bord
            </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/dispatch" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour à l'historique
        </a>
    </div>
<?php endif; ?>
