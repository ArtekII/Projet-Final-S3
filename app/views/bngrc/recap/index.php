<?php
/**
 * @var array $statsBesoins
 * @var array $statsDons
 * @var array $statsDispatch
 * @var array $statsAchats
 * @var array $besoinsParType
 */

function formatMontant($montant): string {
    return number_format((float)($montant ?? 0), 0, ',', ' ') . ' Ar';
}
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-bar-chart-line me-2 text-primary"></i>Récapitulation
    </h1>
    <button id="btnActualiser" class="btn btn-primary" onclick="actualiserDonnees()">
        <i class="bi bi-arrow-clockwise me-1" id="iconActualiser"></i>Actualiser
    </button>
</div>

<!-- Indicateur de chargement -->
<div id="loadingOverlay" class="d-none">
    <div class="alert alert-info text-center">
        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
        Actualisation des données en cours...
    </div>
</div>

<!-- Cartes de résumé -->
<div class="row g-3 mb-4" id="cardsContainer">
    <!-- Besoins totaux en montant -->
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">Besoins totaux</p>
                        <h3 class="mb-0" id="valBesoinsTotal"><?= formatMontant($statsBesoins['valeur_totale_demandee']) ?></h3>
                        <small class="text-muted"><span id="valNbBesoins"><?= (int)($statsBesoins['total_besoins'] ?? 0) ?></span> besoin(s) enregistré(s)</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Besoins satisfaits en montant -->
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">Besoins satisfaits</p>
                        <h3 class="mb-0 text-success" id="valBesoinsSatisfaits"><?= formatMontant($statsBesoins['valeur_totale_recue']) ?></h3>
                        <?php 
                        $pctSatisfaits = ($statsBesoins['valeur_totale_demandee'] > 0) 
                            ? round(($statsBesoins['valeur_totale_recue'] / $statsBesoins['valeur_totale_demandee']) * 100, 1) 
                            : 0;
                        ?>
                        <small class="text-muted"><span id="valPctSatisfaits"><?= $pctSatisfaits ?></span>% des besoins satisfaits</small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-success" id="barSatisfaits" style="width: <?= $pctSatisfaits ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Besoins restants en montant -->
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">Besoins restants</p>
                        <h3 class="mb-0 text-danger" id="valBesoinsRestants"><?= formatMontant($statsBesoins['valeur_restante']) ?></h3>
                        <?php 
                        $pctRestants = ($statsBesoins['valeur_totale_demandee'] > 0) 
                            ? round(($statsBesoins['valeur_restante'] / $statsBesoins['valeur_totale_demandee']) * 100, 1) 
                            : 0;
                        ?>
                        <small class="text-muted"><span id="valPctRestants"><?= $pctRestants ?></span>% encore à couvrir</small>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-danger" id="barRestants" style="width: <?= $pctRestants ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dons & Achats résumé -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-2">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <p class="text-muted mb-1 small">Total dons argent</p>
                <h5 class="mb-0" id="valDonsArgent"><?= formatMontant($statsDons['total_argent'] ?? 0) ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info mx-auto mb-2">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <p class="text-muted mb-1 small">Argent restant</p>
                <h5 class="mb-0" id="valArgentRestant"><?= formatMontant($statsDons['argent_restant'] ?? 0) ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-2">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <p class="text-muted mb-1 small">Total dispatchs</p>
                <h5 class="mb-0" id="valDispatch"><?= (int)($statsDispatch['total_dispatches'] ?? 0) ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                    <i class="bi bi-cart-check"></i>
                </div>
                <p class="text-muted mb-1 small">Total achats (TTC)</p>
                <h5 class="mb-0" id="valAchats"><?= formatMontant($statsAchats['total_montant_total'] ?? 0) ?></h5>
            </div>
        </div>
    </div>
</div>

<!-- Détail par type de besoin -->
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>Détail par type de besoin
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Type de besoin</th>
                        <th class="text-center">Nb besoins</th>
                        <th class="text-end">Qté demandée</th>
                        <th class="text-end">Qté reçue</th>
                        <th class="text-end">Qté restante</th>
                        <th class="text-end">Valeur demandée</th>
                        <th class="text-end">Valeur reçue</th>
                        <th class="text-end">Valeur restante</th>
                        <th class="text-center">Progression</th>
                    </tr>
                </thead>
                <tbody id="tableBesoinsParType">
                    <?php if (empty($besoinsParType)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                Aucun besoin enregistré
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($besoinsParType as $type): ?>
                            <?php 
                            $pct = ($type['valeur_demandee'] > 0) 
                                ? round(($type['valeur_recue'] / $type['valeur_demandee']) * 100, 1) 
                                : 0;
                            $barClass = $pct >= 75 ? 'bg-success' : ($pct >= 40 ? 'bg-warning' : 'bg-danger');
                            ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($type['type_besoin']) ?></span>
                                </td>
                                <td class="text-center"><?= (int)$type['nb_besoins'] ?></td>
                                <td class="text-end"><?= number_format((float)$type['total_demande'], 0, ',', ' ') ?></td>
                                <td class="text-end"><?= number_format((float)$type['total_recu'], 0, ',', ' ') ?></td>
                                <td class="text-end fw-bold text-danger"><?= number_format((float)$type['total_restant'], 0, ',', ' ') ?></td>
                                <td class="text-end"><?= formatMontant($type['valeur_demandee']) ?></td>
                                <td class="text-end text-success"><?= formatMontant($type['valeur_recue']) ?></td>
                                <td class="text-end text-danger fw-bold"><?= formatMontant($type['valeur_restante']) ?></td>
                                <td class="text-center" style="min-width: 120px;">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar <?= $barClass ?>" style="width: <?= $pct ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= $pct ?>%</small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Dernière actualisation -->
<div class="text-end text-muted small mb-3">
    <i class="bi bi-clock me-1"></i>Dernière actualisation : <span id="derniereMaj"><?= date('d/m/Y H:i:s') ?></span>
</div>

<script>
function formatMontant(val) {
    val = parseFloat(val) || 0;
    return new Intl.NumberFormat('fr-FR').format(Math.round(val)) + ' Ar';
}

function actualiserDonnees() {
    const btn = document.getElementById('btnActualiser');
    const icon = document.getElementById('iconActualiser');
    const overlay = document.getElementById('loadingOverlay');

    // Désactiver le bouton et montrer le spinner
    btn.disabled = true;
    icon.classList.add('spin-animation');
    overlay.classList.remove('d-none');

    fetch('<?= BASE_URL ?>/recap/api')
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            const sb = data.statsBesoins;
            const sd = data.statsDons;
            const sdp = data.statsDispatch;
            const sa = data.statsAchats;

            // Mise à jour des cartes principales
            document.getElementById('valBesoinsTotal').textContent = formatMontant(sb.valeur_totale_demandee);
            document.getElementById('valNbBesoins').textContent = parseInt(sb.total_besoins) || 0;
            document.getElementById('valBesoinsSatisfaits').textContent = formatMontant(sb.valeur_totale_recue);
            document.getElementById('valBesoinsRestants').textContent = formatMontant(sb.valeur_restante);

            // Pourcentages
            const total = parseFloat(sb.valeur_totale_demandee) || 0;
            const recu = parseFloat(sb.valeur_totale_recue) || 0;
            const restant = parseFloat(sb.valeur_restante) || 0;
            const pctSatisf = total > 0 ? ((recu / total) * 100).toFixed(1) : '0.0';
            const pctRest = total > 0 ? ((restant / total) * 100).toFixed(1) : '0.0';

            document.getElementById('valPctSatisfaits').textContent = pctSatisf;
            document.getElementById('barSatisfaits').style.width = pctSatisf + '%';
            document.getElementById('valPctRestants').textContent = pctRest;
            document.getElementById('barRestants').style.width = pctRest + '%';

            // Dons & achats
            document.getElementById('valDonsArgent').textContent = formatMontant(sd.total_argent || 0);
            document.getElementById('valArgentRestant').textContent = formatMontant(sd.argent_restant || 0);
            document.getElementById('valDispatch').textContent = parseInt(sdp.total_dispatches) || 0;
            document.getElementById('valAchats').textContent = formatMontant(sa.total_montant_total || 0);

            // Tableau par type de besoin
            const tbody = document.getElementById('tableBesoinsParType');
            if (data.besoinsParType && data.besoinsParType.length > 0) {
                tbody.innerHTML = data.besoinsParType.map(type => {
                    const pct = parseFloat(type.valeur_demandee) > 0 
                        ? ((parseFloat(type.valeur_recue) / parseFloat(type.valeur_demandee)) * 100).toFixed(1) 
                        : '0.0';
                    const barClass = pct >= 75 ? 'bg-success' : (pct >= 40 ? 'bg-warning' : 'bg-danger');
                    return `<tr>
                        <td><span class="badge bg-secondary">${type.type_besoin}</span></td>
                        <td class="text-center">${parseInt(type.nb_besoins)}</td>
                        <td class="text-end">${new Intl.NumberFormat('fr-FR').format(Math.round(parseFloat(type.total_demande)))}</td>
                        <td class="text-end">${new Intl.NumberFormat('fr-FR').format(Math.round(parseFloat(type.total_recu)))}</td>
                        <td class="text-end fw-bold text-danger">${new Intl.NumberFormat('fr-FR').format(Math.round(parseFloat(type.total_restant)))}</td>
                        <td class="text-end">${formatMontant(type.valeur_demandee)}</td>
                        <td class="text-end text-success">${formatMontant(type.valeur_recue)}</td>
                        <td class="text-end text-danger fw-bold">${formatMontant(type.valeur_restante)}</td>
                        <td class="text-center" style="min-width: 120px;">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar ${barClass}" style="width: ${pct}%"></div>
                            </div>
                            <small class="text-muted">${pct}%</small>
                        </td>
                    </tr>`;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted"><i class="bi bi-inbox display-6 d-block mb-2"></i>Aucun besoin enregistré</td></tr>';
            }

            // Date de mise à jour
            const now = new Date();
            document.getElementById('derniereMaj').textContent = now.toLocaleDateString('fr-FR') + ' ' + now.toLocaleTimeString('fr-FR');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'actualisation des données.');
        })
        .finally(() => {
            btn.disabled = false;
            icon.classList.remove('spin-animation');
            overlay.classList.add('d-none');
        });
}
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.spin-animation {
    animation: spin 1s linear infinite;
}
</style>
