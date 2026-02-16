<?php
$current_page = 'dashboard';
?>

<?php require_once __DIR__ . '/partial/head.php'; ?>
<?php require_once __DIR__ . '/partial/header.php'; ?>

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1 fw-semibold">Tableau de Bord BNGRC</h4>
                    <p class="mb-0 text-muted">Suivi des collectes et distributions de dons pour les sinistrés</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $basepath ?>/sinistres/insert" class="btn btn-sm btn-danger">
                        <i class="ti ti-alert-triangle"></i> Déclarer un sinistre
                    </a>
                    <a href="<?= $basepath ?>/dons/insert" class="btn btn-sm btn-success">
                        <i class="ti ti-gift"></i> Enregistrer un don
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-9 fw-semibold">Total Sinistres</h5>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?= $stats['total_sinistres'] ?? 0 ?></h4>
                            <div class="d-flex align-items-center">
                                <span class="me-1 rounded-circle bg-danger-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-right text-danger"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Personnes</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div class="text-white bg-danger rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-alert-triangle fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-9 fw-semibold">Villes Affectées</h5>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?= $stats['villes_affectees'] ?? 0 ?></h4>
                            <div class="d-flex align-items-center">
                                <span class="me-1 rounded-circle bg-warning-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-right text-warning"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Villes</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div class="text-white bg-warning rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-building fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-9 fw-semibold">Dons Reçus</h5>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?= $total_dons['nb_total_dons'] ?? 0 ?></h4>
                            <div class="d-flex align-items-center">
                                <span class="me-1 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-right text-success"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Dons</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-gift fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-9 fw-semibold">Argent Disponible</h5>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?= number_format($total_dons['total_argent_disponible'] ?? 0, 0, ',', ' ') ?> Ar</h4>
                            <div class="d-flex align-items-center">
                                <span class="me-1 rounded-circle bg-primary-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-right text-primary"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Fonds</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div class="text-white bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-coin fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Villes avec Besoins et Dons -->
    <div class="row">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-md-flex align-items-center mb-3">
                        <div>
                            <h4 class="card-title fw-semibold"><i class="ti ti-map-pin text-primary"></i> Villes avec Besoins et Dons Attribués</h4>
                            <p class="card-subtitle text-muted">Cliquez sur une ville pour voir les détails des besoins</p>
                        </div>
                        <div class="ms-auto mt-3 mt-md-0 d-flex gap-2">
                            <a href="<?= $basepath ?>/dons/dispatch" class="btn btn-sm btn-primary">
                                <i class="ti ti-truck-delivery"></i> Dispatcher les dons
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 text-nowrap align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Ville</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Région</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Population</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Sinistres</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Besoins (Montant)</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Dons Reçus</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Couverture</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Actions</h6></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($villes)): ?>
                                    <?php foreach ($villes as $ville): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-primary-subtle text-primary rounded-circle round-20">
                                                        <i class="ti ti-map-pin"></i>
                                                    </span>
                                                    <a href="<?= $basepath ?>/villes/besoins?id=<?= $ville['ville_id'] ?>" class="fw-semibold text-primary">
                                                        <?= htmlspecialchars($ville['ville_nom']) ?>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    <?= htmlspecialchars($ville['region_nom']) ?>
                                                </span>
                                            </td>
                                            <td><p class="mb-0"><?= number_format($ville['population']) ?></p></td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <?= $ville['total_sinistres'] ?> personnes
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="fw-semibold"><?= number_format($ville['montant_total_besoins'], 0, ',', ' ') ?> Ar</span>
                                                    <div class="text-muted fs-2"><?= $ville['nb_types_besoins'] ?> types de besoins</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <?= number_format($ville['total_argent_disponible'], 0, ',', ' ') ?> Ar
                                                    </span>
                                                    <?php if ($ville['nb_dons_nature'] > 0): ?>
                                                        <div class="text-muted fs-2"><?= $ville['nb_dons_nature'] ?> dons nature</div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php 
                                                    $couverture = $ville['pourcentage_couverture'];
                                                    $badgeClass = $couverture >= 80 ? 'success' : ($couverture >= 40 ? 'warning' : 'danger');
                                                ?>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-<?= $badgeClass ?>" role="progressbar" 
                                                         style="width: <?= min($couverture, 100) ?>%;" 
                                                         aria-valuenow="<?= $couverture ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <?= number_format($couverture, 1) ?>%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="<?= $basepath ?>/villes/besoins?id=<?= $ville['ville_id'] ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="ti ti-eye"></i> Voir détails
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="ti ti-inbox fs-8"></i>
                                            <p class="mt-2 mb-0">Aucune ville avec des sinistres enregistrée</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/partial/footer.php'; ?>
