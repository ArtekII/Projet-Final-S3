<?php
/**
 * @var array|null $ville
 * @var array $regions
 */
$isEdit = $ville !== null;
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/villes">Villes</a></li>
                <li class="breadcrumb-item active"><?= $isEdit ? 'Modifier' : 'Ajouter' ?></li>
            </ol>
        </nav>
        <h1 class="h2">
            <i class="bi bi-building me-2"></i><?= $isEdit ? 'Modifier la ville' : 'Ajouter une ville' ?>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASE_URL ?>/villes/<?= $isEdit ? 'update/' . $ville['id'] : 'store' ?>" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom de la ville <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               value="<?= htmlspecialchars($ville['nom'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="region_id" class="form-label">Région <span class="text-danger">*</span></label>
                        <select class="form-select" id="region_id" name="region_id" required>
                            <option value="">Sélectionner une région</option>
                            <?php foreach ($regions as $region): ?>
                                <option value="<?= $region['id'] ?>" 
                                    <?= ($ville['region_id'] ?? '') == $region['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($region['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?>
                        </button>
                        <a href="<?= BASE_URL ?>/villes" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
