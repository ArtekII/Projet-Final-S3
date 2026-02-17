<?php
/**
 * @var array $dons
 */
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-gift me-2"></i>Gestion des Dons
    </h1>
    <a href="<?= BASE_URL ?>/dons/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Enregistrer un don
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Restant</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dons)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Aucun don enregistré
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dons as $don): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($don['date_don'])) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = match($don['type_don']) {
                                        'argent' => 'bg-success',
                                        'nature' => 'bg-primary',
                                        'materiaux' => 'bg-info',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($don['type_don']) ?></span>
                                </td>
                                <td class="text-end"><?= $don['montant'] !== null ? number_format((float)$don['montant'], 0, ',', ' ') . ' Ar' : '-' ?></td>
                                <td class="text-end"><?= $don['quantite'] !== null ? number_format((float)$don['quantite']) : '-' ?></td>
                                <td class="text-end"><?= number_format((float)$don['restant'], 0, ',', ' ') . ($don['type_don'] === 'argent' ? ' Ar' : '') ?></td>
                                <td class="text-center">
                                    <?php if ($don['dispatched']): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Dispatché</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/dons/edit/<?= $don['id'] ?>" 
                                           class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= BASE_URL ?>/dons/delete/<?= $don['id'] ?>" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?');">
                                            <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
