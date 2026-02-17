<?php
/**
 * @var array $regions
 */
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-map me-2"></i>Gestion des Régions
    </h1>
    <a href="<?= BASE_URL ?>/regions/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Ajouter une région
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th class="text-end">Nombre de villes</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($regions)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                Aucune région enregistrée
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($regions as $region): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($region['nom']) ?></strong></td>
                                <td class="text-end"><?= number_format($region['nb_villes']) ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/regions/edit/<?= $region['id'] ?>" 
                                           class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= BASE_URL ?>/regions/delete/<?= $region['id'] ?>" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr ? Toutes les villes de cette région seront également supprimées.');">
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
