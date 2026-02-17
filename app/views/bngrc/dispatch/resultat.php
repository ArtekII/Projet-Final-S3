<?php
/**
 * @var array $attributions
 */
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dispatch">Dispatch</a></li>
                <li class="breadcrumb-item active">Résultat</li>
            </ol>
        </nav>
        <h1 class="h2">
            <i class="bi bi-check-circle me-2 text-success"></i>Résultat du Dispatch
        </h1>
    </div>
</div>

<?php if (empty($attributions)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-info-circle display-1 text-info mb-3"></i>
            <h4>Aucune attribution effectuée</h4>
            <p class="text-muted mb-4">
                Cela peut signifier que :
            </p>
            <ul class="list-unstyled text-muted">
                <li><i class="bi bi-check me-2"></i>Tous les dons ont déjà été dispatchés</li>
                <li><i class="bi bi-check me-2"></i>Il n'y a pas de besoins correspondants aux dons disponibles</li>
                <li><i class="bi bi-check me-2"></i>Tous les besoins sont déjà satisfaits</li>
            </ul>
            <a href="<?= BASE_URL ?>/dispatch" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-1"></i>Retour à l'historique
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-success mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong><?= count($attributions) ?> attribution(s)</strong> effectuée(s) avec succès !
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
                            <th>Type</th>
                            <th class="text-end">Quantité attribuée</th>
                            <th>Ville bénéficiaire</th>
                            <th>Région</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attributions as $attr): ?>
                            <tr>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($attr['type']) ?></span></td>
                                <td class="text-end"><?= number_format($attr['quantite_attribuee']) ?></td>
                                <td><?= htmlspecialchars($attr['ville']) ?></td>
                                <td><?= htmlspecialchars($attr['region']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-primary">
            <i class="bi bi-speedometer2 me-1"></i>Voir le tableau de bord
        </a>
        <a href="<?= BASE_URL ?>/dispatch" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour à l'historique
        </a>
    </div>
<?php endif; ?>
