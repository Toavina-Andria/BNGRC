<?php 
// Fonction helper pour formater les nombres en gérant les valeurs null
function safe_number_format($number, $decimals = 0, $dec_point = ',', $thousands_sep = ' ') {
    return number_format($number ?? 0, $decimals, $dec_point, $thousands_sep);
}
?>
<?php require_once __DIR__ . '/partial/head.php'; ?>
<?php require_once __DIR__ . '/partial/header.php'; ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">
                    <i class="ti ti-chart-bar"></i> Récapitulation des Besoins et Dons
                </h5>
                <button class="btn btn-primary" id="btnActualiser">
                    <i class="ti ti-refresh"></i> Actualiser
                </button>
            </div>

            <div id="derniere_maj" class="text-muted mb-3">
                <small>Dernière mise à jour : <?= $data['derniere_mise_a_jour'] ?></small>
            </div>

            <!-- Cartes de statistiques principales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-primary mb-1">Besoins Totaux</h6>
                                    <h4 class="mb-0" id="montant_total">
                                        <?= safe_number_format($data['besoins']['montant_total'], 0, ',', ' ') ?> Ar
                                    </h4>
                                    <small class="text-muted" id="quantite_totale">
                                        <?= safe_number_format($data['besoins']['quantite_totale'], 0, ',', ' ') ?> unités
                                    </small>
                                </div>
                                <div class="text-primary fs-1">
                                    <i class="ti ti-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-light-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-success mb-1">Besoins Satisfaits</h6>
                                    <h4 class="mb-0" id="montant_satisfait">
                                        <?= safe_number_format($data['besoins']['montant_satisfait'], 0, ',', ' ') ?> Ar
                                    </h4>
                                    <small class="text-muted" id="quantite_satisfaite">
                                        <?= safe_number_format($data['besoins']['quantite_satisfaite'], 0, ',', ' ') ?> unités
                                    </small>
                                </div>
                                <div class="text-success fs-1">
                                    <i class="ti ti-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-light-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-danger mb-1">Besoins Restants</h6>
                                    <h4 class="mb-0" id="montant_restant">
                                        <?= safe_number_format($data['besoins']['montant_restant'], 0, ',', ' ') ?> Ar
                                    </h4>
                                    <small class="text-muted" id="quantite_restante">
                                        <?= safe_number_format($data['besoins']['quantite_restante'], 0, ',', ' ') ?> unités
                                    </small>
                                </div>
                                <div class="text-danger fs-1">
                                    <i class="ti ti-alert-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-light-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-info mb-1">Taux de Couverture</h6>
                                    <h4 class="mb-0" id="taux_couverture">
                                        <?= safe_number_format($data['besoins']['taux_couverture'], 2) ?> %
                                    </h4>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             id="progress_bar"
                                             style="width: <?= $data['besoins']['taux_couverture'] ?>%">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-info fs-1">
                                    <i class="ti ti-chart-pie"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails par catégorie -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="ti ti-category"></i> Répartition par Catégorie</h6>
                    <div class="table-responsive" id="table_categories">
                        <table class="table table-hover table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th>Catégorie</th>
                                    <th class="text-end">Montant Total</th>
                                    <th class="text-end">Montant Satisfait</th>
                                    <th class="text-end">Montant Restant</th>
                                    <th>Progression</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['par_categorie'] as $cat): 
                                    $taux_cat = 0;
                                    if ($cat['montant_total_categorie'] > 0) {
                                        $taux_cat = ($cat['montant_satisfait_categorie'] / $cat['montant_total_categorie']) * 100;
                                    }
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cat['categorie']) ?></td>
                                        <td class="text-end">
                                            <?= safe_number_format($cat['montant_total_categorie'], 2, ',', ' ') ?> Ar
                                        </td>
                                        <td class="text-end text-success">
                                            <?= safe_number_format($cat['montant_satisfait_categorie'], 2, ',', ' ') ?> Ar
                                        </td>
                                        <td class="text-end text-danger">
                                            <?= safe_number_format($cat['montant_restant_categorie'], 2, ',', ' ') ?> Ar
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                    <div class="progress-bar <?= $taux_cat >= 75 ? 'bg-success' : ($taux_cat >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                         style="width: <?= $taux_cat ?>%">
                                                    </div>
                                                </div>
                                                <small class="text-muted"><?= safe_number_format($taux_cat, 1) ?>%</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Statistiques des dons -->
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3"><i class="ti ti-gift"></i> Statistiques des Dons</h6>
                    <div class="row" id="stats_dons">
                        <div class="col-md-3">
                            <div class="border-end pe-3">
                                <p class="text-muted mb-1">Dons en Argent</p>
                                <h4 class="mb-0"><?= $data['dons']['nb_dons_argent'] ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end pe-3">
                                <p class="text-muted mb-1">Dons en Nature</p>
                                <h4 class="mb-0"><?= $data['dons']['nb_dons_nature'] ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end pe-3">
                                <p class="text-muted mb-1">Montant Total Argent</p>
                                <h4 class="mb-0"><?= safe_number_format($data['dons']['montant_total_argent'], 0, ',', ' ') ?> Ar</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Argent Restant</p>
                            <h4 class="mb-0 text-success"><?= safe_number_format($data['dons']['montant_restant_argent'], 0, ',', ' ') ?> Ar</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const basepath = '<?= $basepath ?>';

// Actualiser les données via Ajax
document.getElementById('btnActualiser').addEventListener('click', function() {
    const btn = this;
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="ti ti-loader ti-spin"></i> Actualisation...';
    btn.disabled = true;

    fetch(basepath + '/recapitulation/ajax')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour les statistiques principales
            document.getElementById('montant_total').textContent = formatNumber(data.besoins.montant_total) + ' Ar';
            document.getElementById('quantite_totale').textContent = formatNumber(data.besoins.quantite_totale, 0) + ' unités';
            
            document.getElementById('montant_satisfait').textContent = formatNumber(data.besoins.montant_satisfait) + ' Ar';
            document.getElementById('quantite_satisfaite').textContent = formatNumber(data.besoins.quantite_satisfaite, 0) + ' unités';
            
            document.getElementById('montant_restant').textContent = formatNumber(data.besoins.montant_restant) + ' Ar';
            document.getElementById('quantite_restante').textContent = formatNumber(data.besoins.quantite_restante, 0) + ' unités';
            
            document.getElementById('taux_couverture').textContent = data.besoins.taux_couverture.toFixed(2) + ' %';
            document.getElementById('progress_bar').style.width = data.besoins.taux_couverture + '%';
            
            // Mettre à jour la date
            document.getElementById('derniere_maj').innerHTML = '<small>Dernière mise à jour : ' + data.derniere_mise_a_jour + '</small>';
            
            // Rafraîchir la table des catégories
            updateCategoriesTable(data.par_categorie);
            
            // Rafraîchir les stats des dons
            updateDonsStats(data.dons);
            
            // Réinitialiser le bouton
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'actualisation des données');
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        });
});

function formatNumber(num, decimals = 0) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(num);
}

function updateCategoriesTable(categories) {
    const tbody = document.querySelector('#table_categories tbody');
    tbody.innerHTML = '';
    
    categories.forEach(cat => {
        const taux = cat.montant_total_categorie > 0 
            ? (cat.montant_satisfait_categorie / cat.montant_total_categorie) * 100 
            : 0;
        const progressColor = taux >= 75 ? 'bg-success' : (taux >= 50 ? 'bg-warning' : 'bg-danger');
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${cat.categorie}</td>
            <td class="text-end">${formatNumber(cat.montant_total_categorie, 2)} Ar</td>
            <td class="text-end text-success">${formatNumber(cat.montant_satisfait_categorie, 2)} Ar</td>
            <td class="text-end text-danger">${formatNumber(cat.montant_restant_categorie, 2)} Ar</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                        <div class="progress-bar ${progressColor}" style="width: ${taux}%"></div>
                    </div>
                    <small class="text-muted">${taux.toFixed(1)}%</small>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function updateDonsStats(dons) {
    const container = document.getElementById('stats_dons');
    container.innerHTML = `
        <div class="col-md-3">
            <div class="border-end pe-3">
                <p class="text-muted mb-1">Dons en Argent</p>
                <h4 class="mb-0">${dons.nb_dons_argent}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border-end pe-3">
                <p class="text-muted mb-1">Dons en Nature</p>
                <h4 class="mb-0">${dons.nb_dons_nature}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border-end pe-3">
                <p class="text-muted mb-1">Montant Total Argent</p>
                <h4 class="mb-0">${formatNumber(dons.montant_total_argent)} Ar</h4>
            </div>
        </div>
        <div class="col-md-3">
            <p class="text-muted mb-1">Argent Restant</p>
            <h4 class="mb-0 text-success">${formatNumber(dons.montant_restant_argent)} Ar</h4>
        </div>
    `;
}
</script>

<?php require_once __DIR__ . '/partial/footer.php'; ?>
