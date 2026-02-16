<?php require_once __DIR__ . '/../dashboard/partial/head.php'; ?>
<?php require_once __DIR__ . '/../dashboard/partial/header.php'; ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-semibold mb-0">Besoins Restants - Achats Possibles</h5>
                <a href="<?= $basepath ?>/achats/liste" class="btn btn-secondary">
                    <i class="ti ti-list"></i> Liste des achats
                </a>
            </div>

            <!-- Filtre par ville -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <form method="GET" action="<?= $basepath ?>/achats/besoins-restants">
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
                <div class="col-md-8 text-end">
                    <div class="alert alert-info mb-0">
                        <i class="ti ti-info-circle"></i>
                        <strong>Frais d'achat :</strong> <?= number_format($frais_pourcentage, 2) ?>%
                    </div>
                </div>
            </div>

            <?php if (empty($besoins)): ?>
                <div class="alert alert-success">
                    <i class="ti ti-check"></i> Tous les besoins ont été satisfaits ! Aucun achat nécessaire.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-borderless text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Région</th>
                                <th>Ville</th>
                                <th>Catégorie</th>
                                <th>Description</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Prix Unit.</th>
                                <th class="text-end">Montant Total</th>
                                <th class="text-end">Avec Frais (<?= number_format($frais_pourcentage, 1) ?>%)</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($besoins as $besoin): 
                                $montant_avec_frais = $besoin['montant_total'] * (1 + ($frais_pourcentage / 100));
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($besoin['region_nom']) ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            <?= htmlspecialchars($besoin['ville_nom']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($besoin['categorie_nom']) ?></td>
                                    <td><?= htmlspecialchars($besoin['description']) ?></td>
                                    <td class="text-end"><?= number_format($besoin['quantite'], 0, ',', ' ') ?></td>
                                    <td class="text-end"><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                                    <td class="text-end fw-semibold">
                                        <?= number_format($besoin['montant_total'], 2, ',', ' ') ?> Ar
                                    </td>
                                    <td class="text-end text-danger fw-semibold">
                                        <?= number_format($montant_avec_frais, 2, ',', ' ') ?> Ar
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $basepath ?>/achats/form?id_besoin=<?= $besoin['id'] ?>" 
                                           class="btn btn-sm btn-success">
                                            <i class="ti ti-shopping-cart"></i> Acheter
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="6" class="text-end fw-bold">TOTAL :</td>
                                <td class="text-end fw-bold">
                                    <?php 
                                    $total_montant = array_sum(array_column($besoins, 'montant_total'));
                                    echo number_format($total_montant, 2, ',', ' ') . ' Ar';
                                    ?>
                                </td>
                                <td class="text-end fw-bold text-danger">
                                    <?php 
                                    $total_avec_frais = $total_montant * (1 + ($frais_pourcentage / 100));
                                    echo number_format($total_avec_frais, 2, ',', ' ') . ' Ar';
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
