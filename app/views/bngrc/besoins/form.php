<?php
/**
 * @var array|null $besoin
 * @var array $villes
 * @var array $typesBesoins
 */
$isEdit = $besoin !== null;
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/besoins">Besoins</a></li>
                <li class="breadcrumb-item active"><?= $isEdit ? 'Modifier' : 'Saisir' ?></li>
            </ol>
        </nav>
        <h1 class="h2">
            <i class="bi bi-clipboard-check me-2"></i><?= $isEdit ? 'Modifier le besoin' : 'Saisir un besoin' ?>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASE_URL ?>/besoins/<?= $isEdit ? 'update/' . $besoin['id'] : 'store' ?>" method="POST">
                    <div class="mb-3">
                        <label for="ville_id" class="form-label">Ville <span class="text-danger">*</span></label>
                        <select class="form-select" id="ville_id" name="ville_id" required>
                            <option value="">Sélectionner une ville</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>" 
                                    <?= ($besoin['ville_id'] ?? '') == $ville['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ville['nom']) ?> (<?= htmlspecialchars($ville['region_nom']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type_besoin" class="form-label">Type de besoin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="type_besoin" name="type_besoin" 
                               value="<?= htmlspecialchars($besoin['type_besoin'] ?? '') ?>" 
                               list="types_besoins_list" required placeholder="Ex: Riz, Huile, Tôle...">
                        <datalist id="types_besoins_list">
                            <?php foreach ($typesBesoins as $type): ?>
                                <option value="<?= htmlspecialchars($type) ?>">
                            <?php endforeach; ?>
                            <option value="Riz">
                            <option value="Huile">
                            <option value="Tôle">
                            <option value="Savon">
                            <option value="Sucre">
                            <option value="Eau">
                            <option value="Médicaments">
                        </datalist>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantite_demandee" class="form-label">Quantité demandée <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantite_demandee" name="quantite_demandee" 
                               value="<?= htmlspecialchars($besoin['quantite_demandee'] ?? '') ?>" 
                               min="0.01" step="0.01" required onchange="calculateTotal()">
                    </div>
                    
                    <div class="mb-3">
                        <label for="prix_unitaire" class="form-label">Prix unitaire (Ar)</label>
                        <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" 
                               value="<?= htmlspecialchars($besoin['prix_unitaire'] ?? '0') ?>" 
                               min="0" step="0.01" onchange="calculateTotal()">
                        <small class="text-muted">Optionnel - pour estimer la valeur</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Valeur estimée</label>
                        <div class="form-control bg-light" id="valeur-estimee">0 Ar</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?>
                        </button>
                        <a href="<?= BASE_URL ?>/besoins" class="btn btn-secondary">
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
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Aide</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    <strong>Types de besoins courants :</strong>
                </p>
                <ul class="list-unstyled">
                    <li><span class="badge bg-success me-2">Riz</span> Produit alimentaire de base</li>
                    <li class="mt-2"><span class="badge bg-warning me-2">Huile</span> Huile alimentaire</li>
                    <li class="mt-2"><span class="badge bg-info me-2">Tôle</span> Matériaux de construction</li>
                    <li class="mt-2"><span class="badge bg-secondary me-2">Autre</span> Vous pouvez saisir un type personnalisé</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function calculateTotal() {
    const quantite = parseFloat(document.getElementById('quantite_demandee').value) || 0;
    const prix = parseFloat(document.getElementById('prix_unitaire').value) || 0;
    
    const total = quantite * prix;
    document.getElementById('valeur-estimee').textContent = new Intl.NumberFormat('fr-FR').format(total) + ' Ar';
}

document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
