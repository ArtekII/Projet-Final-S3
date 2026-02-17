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
                        <label for="type_id" class="form-label">Type de don <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_id" name="type_id" required onchange="toggleFields()">
                            <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>-- Sélectionner --</option>
                            <?php foreach ($typesDons as $type): ?>
                                <?php $isSelected = ($don['type_id'] ?? null) == $type['id']; ?>
                                <option value="<?= $type['id'] ?>" data-nom="<?= htmlspecialchars(strtolower($type['nom'])) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="designation_group">
                        <label for="designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="designation" name="designation" 
                               value="<?= htmlspecialchars($don['designation'] ?? '') ?>" 
                               placeholder="Ex: riz, tôle, tente, médicaments...">
                        <small class="text-muted">Doit correspondre à la désignation du besoin pour le dispatch.</small>
                    </div>
                    
                    <div class="mb-3" id="montant_group">
                        <label for="montant" class="form-label">Montant (Ariary) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="montant" name="montant" 
                               value="<?= htmlspecialchars($don['montant'] ?? '') ?>" 
                               min="0.01" step="0.01">
                        <small class="text-muted">Montant en Ariary</small>
                    </div>

                    <div class="mb-3" id="quantite_group">
                        <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantite" name="quantite" 
                               value="<?= htmlspecialchars($don['quantite'] ?? '') ?>" 
                               min="0.01" step="0.01">
                        <small class="text-muted">Quantité d'unités (kg, pièces, litres...)</small>
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

                <script>
                function toggleFields() {
                    const typeSelect = document.getElementById('type_id');
                    const selected = typeSelect.selectedOptions[0];
                    const typeDon = selected ? (selected.dataset.nom || '') : '';
                    const montantGroup = document.getElementById('montant_group');
                    const quantiteGroup = document.getElementById('quantite_group');
                    const designationGroup = document.getElementById('designation_group');
                    
                    if (!typeDon) {
                        montantGroup.style.display = 'none';
                        quantiteGroup.style.display = 'none';
                        designationGroup.style.display = 'none';
                        document.getElementById('montant').required = false;
                        document.getElementById('quantite').required = false;
                        document.getElementById('designation').required = false;
                        return;
                    }

                    if (typeDon === 'argent') {
                        montantGroup.style.display = 'block';
                        quantiteGroup.style.display = 'none';
                        designationGroup.style.display = 'none';
                        document.getElementById('montant').required = true;
                        document.getElementById('quantite').required = false;
                        document.getElementById('designation').required = false;
                        document.getElementById('quantite').value = '';
                        document.getElementById('designation').value = '';
                    } else {
                        montantGroup.style.display = 'none';
                        quantiteGroup.style.display = 'block';
                        designationGroup.style.display = 'block';
                        document.getElementById('montant').required = false;
                        document.getElementById('quantite').required = true;
                        document.getElementById('designation').required = true;
                        document.getElementById('montant').value = '';
                    }
                }
                // Initialiser au chargement
                document.addEventListener('DOMContentLoaded', toggleFields);
                </script>
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
                    Les dons enregistrés seront gérés selon leur type.
                </p>
                <p class="text-muted mb-0">
                    <strong>Types de dons :</strong>
                </p>
                <ul class="text-muted">
                    <li><strong>Argent</strong> : Saisir le montant en Ariary. L'argent sera utilisé pour acheter des besoins (avec frais d'achat).</li>
                    <li><strong>Nature</strong> : Don en nature (riz, huile, savon...). Dispatché directement aux villes.</li>
                    <li><strong>Matériaux</strong> : Don de matériaux (tôle, tente...). Dispatché directement aux villes.</li>
                    <li><strong>Désignation</strong> : Doit être la même que celle d’un besoin pour être dispatché.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
