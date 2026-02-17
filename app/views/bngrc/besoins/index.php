<?php
/**
 * @var array $besoins
 */

function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-clipboard-check me-2"></i>Gestion des Besoins
    </h1>
    <a href="<?= BASE_URL ?>/besoins/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Saisir un besoin
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ville</th>
                        <th>Région</th>
                        <th>Type de besoin</th>
                        <th>Désignation</th>
                        <th>Date</th>
                        <th class="text-center">Ordre</th>
                        <th class="text-end">Quantité demandée</th>
                        <th class="text-end">Quantité reçue</th>
                        <th class="text-end">Prix unitaire</th>
                        <th class="text-end">Valeur</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($besoins)): ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Aucun besoin enregistré
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($besoins as $besoin): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($besoin['ville_nom']) ?></strong></td>
                                <td><?= htmlspecialchars($besoin['region_nom']) ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($besoin['type_besoin']) ?></span></td>
                                <td><?= !empty($besoin['designation']) ? htmlspecialchars($besoin['designation']) : '-' ?></td>
                                <td><?= !empty($besoin['date_besoin']) ? date('d/m/Y', strtotime($besoin['date_besoin'])) : '-' ?></td>
                                <td class="text-center"><?= !empty($besoin['ordre']) ? $besoin['ordre'] : '-' ?></td>
                                <td class="text-end"><?= number_format($besoin['quantite_demandee']) ?></td>
                                <td class="text-end">
                                    <?= number_format($besoin['quantite_recue']) ?>
                                    <?php if ($besoin['quantite_recue'] >= $besoin['quantite_demandee']): ?>
                                        <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= formatMontant($besoin['prix_unitaire']) ?></td>
                                <td class="text-end"><?= formatMontant($besoin['quantite_demandee'] * $besoin['prix_unitaire']) ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/besoins/edit/<?= $besoin['id'] ?>" 
                                           class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= BASE_URL ?>/besoins/delete/<?= $besoin['id'] ?>" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?');">
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
