<?php
$current_page = 'dashboard';
?>

<?php require_once __DIR__ . '/partial/head.php'; ?>
<?php require_once __DIR__ . '/partial/header.php'; ?>

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <div>
                    <h4 class="mb-1 fw-semibold">Tableau de Bord BNGRC</h4>
                    <p class="mb-0 text-muted">Vue d'ensemble des sinistres et besoins</p>
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
                                <p class="text-dark me-1 fs-3 mb-0">Sinistres</p>
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
                    <h5 class="card-title mb-9 fw-semibold">Besoins Totaux</h5>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?= $stats['total_besoins'] ?? 0 ?></h4>
                            <div class="d-flex align-items-center">
                                <span class="me-1 rounded-circle bg-primary-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-right text-primary"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Besoins</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div class="text-white bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-package fs-6"></i>
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
                    <h5 class="card-title mb-9 fw-semibold">Régions</h5>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3"><?= $stats['total_regions'] ?? 0 ?></h4>
                            <div class="d-flex align-items-center">
                                <span class="me-1 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-right text-success"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Régions</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-map fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Sinistres Récents -->
        <div class="col-lg-8">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-md-flex align-items-center mb-3">
                        <div>
                            <h4 class="card-title fw-semibold"><i class="ti ti-alert-triangle text-danger"></i> Sinistres Récents</h4>
                            <p class="card-subtitle text-muted">Liste des derniers sinistres enregistrés</p>
                        </div>
                        <div class="ms-auto mt-3 mt-md-0 d-flex gap-2">
                            <a href="<?= $basepath ?>/sinistres/insert" class="btn btn-sm btn-success">
                                <i class="ti ti-plus"></i> Nouveau
                            </a>
                            <a href="<?= $basepath ?>/sinistres" class="btn btn-sm btn-primary">
                                Voir tout <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 text-nowrap align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th><h6 class="fs-4 fw-semibold mb-0">ID</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Ville</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Région</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Population</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Nombre</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Actions</h6></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sinistres)): ?>
                                    <?php foreach ($sinistres as $sinistre): ?>
                                        <tr>
                                            <td><p class="mb-0 fw-semibold">#<?= $sinistre['id'] ?></p></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-primary-subtle text-primary rounded-circle round-20">
                                                        <i class="ti ti-map-pin"></i>
                                                    </span>
                                                    <p class="mb-0"><?= htmlspecialchars($sinistre['ville_nom']) ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    <?= htmlspecialchars($sinistre['region_nom']) ?>
                                                </span>
                                            </td>
                                            <td><p class="mb-0"><?= number_format($sinistre['population']) ?></p></td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <?= $sinistre['nombre_sinistres'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= $basepath ?>/sinistres/view/<?= $sinistre['id'] ?>" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="ti ti-eye fs-5"></i>
                                                    </a>
                                                    <a href="<?= $basepath ?>/sinistres/edit/<?= $sinistre['id'] ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="ti ti-pencil fs-5"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="ti ti-inbox fs-8"></i>
                                            <p class="mt-2 mb-0">Aucun sinistre enregistré</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Besoins par Catégorie + Top Régions Affectées -->
        <div class="col-lg-4">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h4 class="card-title fw-semibold mb-4"><i class="ti ti-chart-pie text-info"></i> Besoins par Catégorie</h4>
                    <?php if (!empty($besoins_categories)): ?>
                        <?php foreach ($besoins_categories as $besoin): ?>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold"><?= htmlspecialchars($besoin['categorie']) ?></span>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <?= number_format($besoin['quantite_totale']) ?>
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" 
                                         role="progressbar" 
                                         style="width: <?= $besoin['pourcentage'] ?>%"
                                         aria-valuenow="<?= $besoin['pourcentage'] ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted"><?= round($besoin['pourcentage'], 1) ?>%</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-inbox fs-8"></i>
                            <p class="mt-2 mb-0">Aucun besoin enregistré</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card overflow-hidden mt-4">
                <div class="card-body p-4">
                    <h4 class="card-title fw-semibold mb-4"><i class="ti ti-chart-bar text-success"></i> Top Régions Affectées</h4>
                    <?php if (!empty($top_regions)): ?>
                        <?php foreach ($top_regions as $index => $region): ?>
                            <div class="d-flex align-items-center justify-content-between py-3 <?= $index < count($top_regions) - 1 ? 'border-bottom' : '' ?>">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-secondary-subtle text-secondary rounded-circle fw-semibold"><?= $index + 1 ?></span>
                                    <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($region['nom']) ?></h6>
                                </div>
                                <span class="badge bg-danger-subtle text-danger rounded-pill">
                                    <?= $region['nombre_sinistres'] ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-inbox fs-8"></i>
                            <p class="mt-2 mb-0">Aucune donnée disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Besoins Détaillés -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-md-flex align-items-center mb-3">
                        <div>
                            <h4 class="card-title fw-semibold"><i class="ti ti-list-check text-warning"></i> Besoins Détaillés par Sinistre</h4>
                            <p class="card-subtitle text-muted">Détails complets des besoins identifiés</p>
                        </div>
                        <div class="ms-auto mt-3 mt-md-0">
                            <a href="<?= $basepath ?>/besoins/create" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus"></i> Ajouter un besoin
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 text-nowrap align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Sinistre</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Ville</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Catégorie</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Description</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Quantité</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Actions</h6></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($besoins_details)): ?>
                                    <?php foreach ($besoins_details as $besoin): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-dark-subtle text-dark">#<?= $besoin['id_sinistre'] ?></span>
                                            </td>
                                            <td><p class="mb-0"><?= htmlspecialchars($besoin['ville_nom']) ?></p></td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">
                                                    <?= htmlspecialchars($besoin['categorie_nom']) ?>
                                                </span>
                                            </td>
                                            <td><p class="mb-0 text-muted"><?= htmlspecialchars($besoin['description'] ?? 'N/A') ?></p></td>
                                            <td>
                                                <p class="mb-0 fw-bold text-primary">
                                                    <?= number_format($besoin['quantite']) ?>
                                                </p>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= $basepath ?>/besoins/edit/<?= $besoin['id'] ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="ti ti-pencil fs-5"></i>
                                                    </a>
                                                    <a href="<?= $basepath ?>/besoins/delete/<?= $besoin['id'] ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?')">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="ti ti-inbox fs-8"></i>
                                            <p class="mt-2 mb-0">Aucun besoin détaillé disponible</p>
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
