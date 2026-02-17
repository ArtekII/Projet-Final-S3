<?php
/**
 * @var array|null $don
 * @var array $typesDons
 */
$isEdit = $don !== null;
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dons">Dons</a></li>
                <li class="breadcrumb-item active"><?= $isEdit ? 'Modifier' : 'Enregistrer' ?></li>
            </ol>
        </nav>
        <h1 class="h2">
            <i class="bi bi-gift me-2"></i><?= $isEdit ? 'Modifier le don' : 'Enregistrer un don' ?>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASE_URL ?>/dons/<?= $isEdit ? 'update/' . $don['id'] : 'store' ?>" method="POST">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type de don <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="type" name="type" 
                               value="<?= htmlspecialchars($don['type'] ?? '') ?>" 
                               list="types_dons_list" required placeholder="Ex: Riz, Huile, Argent...">
                        <datalist id="types_dons_list">
                            <?php foreach ($typesDons as $type): ?>
                                <option value="<?= htmlspecialchars($type) ?>">
                            <?php endforeach; ?>
                            <option value="Riz">
                            <option value="Huile">
                            <option value="Tôle">
                            <option value="Savon">
                            <option value="Sucre">
                            <option value="Argent">
                            <option value="Eau">
                            <option value="Médicaments">
                        </datalist>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantite" name="quantite" 
                               value="<?= htmlspecialchars($don['quantite'] ?? '') ?>" 
                               min="0.01" step="0.01" required>
                        <small class="text-muted">Pour les dons en argent, saisir le montant en Ariary</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?>
                        </button>
                        <a href="<?= BASE_URL ?>/dons" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Information</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Les dons enregistrés seront automatiquement attribués aux villes ayant des besoins 
                    correspondants lors du dispatch.
                </p>
                <p class="text-muted mb-0">
                    <strong>Règles de dispatch :</strong>
                </p>
                <ul class="text-muted">
                    <li>Les dons sont dispatchés par ordre chronologique de saisie</li>
                    <li>Les dons sont attribués aux besoins du même type</li>
                    <li>Un don de type "Riz" sera attribué aux besoins de type "Riz"</li>
                </ul>
            </div>
        </div>
    </div>
</div>
