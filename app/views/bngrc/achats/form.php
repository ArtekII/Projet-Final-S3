<?php
/**
 * @var array|null $besoin - besoin pré-sélectionné (optionnel)
 * @var array $besoinsRestants
 * @var array $donsArgent
 * @var float $frais
 */

function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/achats">Achats</a></li>
                <li class="breadcrumb-item active">Nouvel achat</li>
            </ol>
        </nav>
        <h1 class="h2">
            <i class="bi bi-cart-plus me-2"></i>Effectuer un achat
        </h1>
    </div>
</div>

<?php if (empty($besoinsRestants)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-check-circle display-1 text-success mb-3"></i>
            <h4>Tous les besoins sont satisfaits !</h4>
            <p class="text-muted">Il n'y a aucun besoin restant à acheter.</p>
            <a href="<?= BASE_URL ?>/achats" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-left me-1"></i>Retour aux achats
            </a>
        </div>
    </div>
<?php elseif (empty($donsArgent)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-wallet2 display-1 text-warning mb-3"></i>
            <h4>Aucun don en argent disponible</h4>
            <p class="text-muted">Il n'y a plus de fonds disponibles pour effectuer des achats.</p>
            <a href="<?= BASE_URL ?>/dons/create" class="btn btn-primary mt-2">
                <i class="bi bi-plus-lg me-1"></i>Enregistrer un don
            </a>
        </div>
    </div>
<?php else: ?>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASE_URL ?>/achats/store" method="POST" id="achatForm">
                    <div class="mb-3">
                        <label for="besoin_id" class="form-label">Besoin à acheter <span class="text-danger">*</span></label>
                        <select class="form-select" id="besoin_id" name="besoin_id" required onchange="updateCalcul()">
                            <option value="">-- Sélectionner un besoin --</option>
                            <?php foreach ($besoinsRestants as $b): ?>
                                <?php $qteRestante = $b['quantite_demandee'] - $b['quantite_recue']; ?>
                                <option value="<?= $b['id'] ?>" 
                                        data-prix="<?= $b['prix_unitaire'] ?>"
                                        data-restant="<?= $qteRestante ?>"
                                        data-type="<?= htmlspecialchars($b['type_besoin']) ?>"
                                        data-ville="<?= htmlspecialchars($b['ville_nom']) ?>"
                                        <?= ($besoin && $besoin['id'] == $b['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($b['ville_nom']) ?> — <?= htmlspecialchars($b['type_besoin']) ?> 
                                    (restant: <?= number_format($qteRestante) ?>, PU: <?= formatMontant($b['prix_unitaire']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="don_id" class="form-label">Don en argent à utiliser <span class="text-danger">*</span></label>
                        <select class="form-select" id="don_id" name="don_id" required onchange="updateCalcul()">
                            <option value="">-- Sélectionner un don --</option>
                            <?php foreach ($donsArgent as $d): ?>
                                <option value="<?= $d['id'] ?>" data-restant="<?= $d['restant'] ?>">
                                    Don #<?= $d['id'] ?> — Solde: <?= formatMontant($d['restant']) ?> 
                                    (du <?= date('d/m/Y', strtotime($d['date_don'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité à acheter <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantite" name="quantite" 
                               min="0.01" step="0.01" required oninput="updateCalcul()">
                        <small class="text-muted" id="info_restant"></small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="btnSubmit" disabled>
                            <i class="bi bi-cart-check me-1"></i>Confirmer l'achat
                        </button>
                        <a href="<?= BASE_URL ?>/achats" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <!-- Aperçu du calcul -->
        <div class="card" id="apercu_card" style="display:none;">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-calculator me-2"></i>Aperçu du coût</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Type besoin</td>
                        <td class="text-end" id="calc_type">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Ville</td>
                        <td class="text-end" id="calc_ville">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Prix unitaire</td>
                        <td class="text-end" id="calc_pu">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Quantité</td>
                        <td class="text-end" id="calc_qte">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Montant HT</td>
                        <td class="text-end fw-bold" id="calc_ht">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Frais (<?= $frais ?>%)</td>
                        <td class="text-end text-warning" id="calc_frais">-</td>
                    </tr>
                    <tr class="table-success">
                        <td class="fw-bold">Total TTC</td>
                        <td class="text-end fw-bold fs-5" id="calc_total">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Solde don après achat</td>
                        <td class="text-end" id="calc_solde">-</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Règles d'achat</h6>
            </div>
            <div class="card-body">
                <ul class="text-muted small mb-0">
                    <li>Les achats utilisent les <strong>dons en argent</strong> pour acheter les besoins des villes</li>
                    <li>Un <strong>frais d'achat de <?= $frais ?>%</strong> est appliqué automatiquement</li>
                    <li class="text-danger"><strong>Erreur</strong> si des dons en nature/matériaux non dispatchés existent encore</li>
                    <li>Le montant est déduit du solde restant du don</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
const fraisPercent = <?= $frais ?>;

function formatMontant(val) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(val)) + ' Ar';
}

function updateCalcul() {
    const besoinSelect = document.getElementById('besoin_id');
    const donSelect = document.getElementById('don_id');
    const quantiteInput = document.getElementById('quantite');
    const card = document.getElementById('apercu_card');
    const btn = document.getElementById('btnSubmit');
    
    const besoinOpt = besoinSelect.selectedOptions[0];
    const donOpt = donSelect.selectedOptions[0];
    const quantite = parseFloat(quantiteInput.value) || 0;

    if (!besoinOpt || !besoinOpt.value) {
        card.style.display = 'none';
        btn.disabled = true;
        return;
    }

    const prixUnitaire = parseFloat(besoinOpt.dataset.prix) || 0;
    const besoinRestant = parseFloat(besoinOpt.dataset.restant) || 0;
    const typeBesoin = besoinOpt.dataset.type || '';
    const villeNom = besoinOpt.dataset.ville || '';

    quantiteInput.max = besoinRestant;
    document.getElementById('info_restant').textContent = 'Maximum : ' + new Intl.NumberFormat('fr-FR').format(besoinRestant);

    card.style.display = 'block';
    document.getElementById('calc_type').textContent = typeBesoin;
    document.getElementById('calc_ville').textContent = villeNom;
    document.getElementById('calc_pu').textContent = formatMontant(prixUnitaire);
    document.getElementById('calc_qte').textContent = new Intl.NumberFormat('fr-FR').format(quantite);

    const montantHT = quantite * prixUnitaire;
    const montantFrais = montantHT * fraisPercent / 100;
    const montantTotal = montantHT + montantFrais;

    document.getElementById('calc_ht').textContent = formatMontant(montantHT);
    document.getElementById('calc_frais').textContent = formatMontant(montantFrais);
    document.getElementById('calc_total').textContent = formatMontant(montantTotal);

    if (donOpt && donOpt.value) {
        const donRestant = parseFloat(donOpt.dataset.restant) || 0;
        const soldeApres = donRestant - montantTotal;
        const soldeEl = document.getElementById('calc_solde');
        soldeEl.textContent = formatMontant(soldeApres);
        soldeEl.className = 'text-end ' + (soldeApres < 0 ? 'text-danger fw-bold' : 'text-success');

        btn.disabled = (quantite <= 0 || quantite > besoinRestant || montantTotal > donRestant);
    } else {
        document.getElementById('calc_solde').textContent = '-';
        btn.disabled = true;
    }
}

document.addEventListener('DOMContentLoaded', updateCalcul);
</script>

<?php endif; ?>
