<?php
/**
 * @var array $villes
 */
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-building me-2"></i>Gestion des Villes
    </h1>
    <a href="<?= BASE_URL ?>/villes/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Ajouter une ville
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Région</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($villes)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Aucune ville enregistrée
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($villes as $ville): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($ville['nom']) ?></strong></td>
                                <td><?= htmlspecialchars($ville['region_nom']) ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/villes/edit/<?= $ville['id'] ?>" 
                                           class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= BASE_URL ?>/villes/delete/<?= $ville['id'] ?>" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ville ?');">
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
