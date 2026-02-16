<?php require_once __DIR__ . '/../dashboard/partial/head.php'; ?>
<?php require_once __DIR__ . '/../dashboard/partial/header.php'; ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-semibold mb-0">Liste des Achats Effectués</h5>
                <a href="<?= $basepath ?>/achats/besoins-restants" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Nouvel achat
                </a>
            </div>

            <!-- Filtre par ville -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <form method="GET" action="<?= $basepath ?>/achats/liste">
                        <div class="input-group">
                            <select name="id_ville" class="form-select">
                                <option value="">Toutes les villes</option>
                                <?php foreach ($villes as $ville): ?>
                                    <option value="<?= $ville['id'] ?>" <?= ($id_ville_filtre == $ville['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ville['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (empty($achats)): ?>
                <div class="alert alert-info">
                    <i class="ti ti-info-circle"></i> Aucun achat enregistré pour le moment.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-borderless text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Région</th>
                                <th>Ville</th>
                                <th>Catégorie</th>
                                <th>Description</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Prix Unit.</th>
                                <th class="text-end">Montant Base</th>
                                <th class="text-end">Frais (%)</th>
                                <th class="text-end">TOTAL</th>
                                <th>Donateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_montant_base = 0;
                            $total_frais = 0;
                            $total_avec_frais = 0;
                            
                            foreach ($achats as $achat): 
                                $total_montant_base += $achat['montant_total'];
                                $frais = $achat['montant_avec_frais'] - $achat['montant_total'];
                                $total_frais += $frais;
                                $total_avec_frais += $achat['montant_avec_frais'];
                            ?>
                                <tr>
                                    <td>
                                        <small><?= date('d/m/Y H:i', strtotime($achat['date_achat'])) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($achat['region_nom']) ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            <?= htmlspecialchars($achat['ville_nom']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($achat['categorie_nom']) ?></td>
                                    <td>
                                        <small><?= htmlspecialchars($achat['besoin_description']) ?></small>
                                    </td>
                                    <td class="text-end"><?= number_format($achat['quantite'], 0, ',', ' ') ?></td>
                                    <td class="text-end"><?= number_format($achat['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                                    <td class="text-end"><?= number_format($achat['montant_total'], 2, ',', ' ') ?> Ar</td>
                                    <td class="text-end">
                                        <small class="text-warning">
                                            +<?= number_format($achat['frais_pourcentage'], 1) ?>%<br>
                                            (<?= number_format($frais, 2, ',', ' ') ?> Ar)
                                        </small>
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        <?= number_format($achat['montant_avec_frais'], 2, ',', ' ') ?> Ar
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($achat['donateur_nom']) ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="7" class="text-end fw-bold">TOTAUX :</td>
                                <td class="text-end fw-bold">
                                    <?= number_format($total_montant_base, 2, ',', ' ') ?> Ar
                                </td>
                                <td class="text-end fw-bold text-warning">
                                    <?= number_format($total_frais, 2, ',', ' ') ?> Ar
                                </td>
                                <td class="text-end fw-bold text-danger">
                                    <?= number_format($total_avec_frais, 2, ',', ' ') ?> Ar
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-light-info">
                            <div class="card-body">
                                <h6 class="text-info">Nombre d'achats</h6>
                                <h3 class="mb-0"><?= count($achats) ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light-success">
                            <div class="card-body">
                                <h6 class="text-success">Montant total (base)</h6>
                                <h3 class="mb-0"><?= number_format($total_montant_base, 0, ',', ' ') ?> Ar</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light-danger">
                            <div class="card-body">
                                <h6 class="text-danger">Montant total (avec frais)</h6>
                                <h3 class="mb-0"><?= number_format($total_avec_frais, 0, ',', ' ') ?> Ar</h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
