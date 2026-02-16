<?php
$current_page = 'sinistres';
?>

<?php require_once __DIR__ . '/../dashboard/partial/head.php'; ?>
<?php require_once __DIR__ . '/../dashboard/partial/header.php'; ?>

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1 fw-semibold"><i class="ti ti-alert-triangle text-danger"></i> Détails du Sinistre #<?= $sinistre['id'] ?? 'N/A' ?></h4>
                    <p class="mb-0 text-muted">Informations complètes sur le sinistre</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $basepath ?>/sinistres/liste" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Cards -->
    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Informations Générales</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted"><small>Identifiant</small></p>
                            <h6 class="fw-semibold">#<?= $sinistre['id'] ?? 'N/A' ?></h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted"><small>Date du sinistre</small></p>
                            <h6 class="fw-semibold">
                                <i class="ti ti-calendar text-primary"></i>
                                <?= isset($sinistre['date_sinistre']) ? date('d/m/Y', strtotime($sinistre['date_sinistre'])) : 'N/A' ?>
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted"><small>Nombre de sinistres</small></p>
                            <h6 class="fw-semibold">
                                <span class="badge bg-danger-subtle text-danger fs-4">
                                    <?= $sinistre['nombre_sinistres'] ?? 0 ?>
                                </span>
                            </h6>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted"><small><i class="ti ti-map-pin"></i> Ville</small></p>
                            <h6 class="fw-semibold"><?= htmlspecialchars($sinistre['ville_nom'] ?? 'N/A') ?></h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted"><small><i class="ti ti-map"></i> Région</small></p>
                            <h6 class="fw-semibold">
                                <span class="badge bg-secondary-subtle text-secondary">
                                    <?= htmlspecialchars($sinistre['region_nom'] ?? 'N/A') ?>
                                </span>
                            </h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <p class="mb-1 text-muted"><small><i class="ti ti-users"></i> Population de la ville</small></p>
                            <h6 class="fw-semibold"><?= number_format($sinistre['population'] ?? 0) ?> habitants</h6>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-0">
                        <p class="mb-2 text-muted"><small><i class="ti ti-file-text"></i> Description</small></p>
                        <p class="mb-0"><?= htmlspecialchars($sinistre['description'] ?? 'Aucune description disponible') ?></p>
                    </div>
                </div>
            </div>

            <!-- Besoins associés -->
            <?php if (!empty($besoins)): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">
                        <i class="ti ti-package text-info"></i> Besoins Associés
                    </h5>
                    <div class="table-responsive">
                        <table class="table mb-0 text-nowrap align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Catégorie</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Quantité</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Description</h6></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($besoins as $besoin): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">
                                                <?= htmlspecialchars($besoin['categorie_nom'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td><strong class="text-primary"><?= number_format($besoin['quantite'] ?? 0) ?></strong></td>
                                        <td><?= htmlspecialchars($besoin['description'] ?? 'N/A') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Actions & Statistics -->
        <div class="col-lg-4">
            <div class="card bg-danger-subtle">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-bolt"></i> Actions Rapides
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="<?= $basepath ?>/sinistres/besoins/insert?sinistre=<?= $sinistre['id'] ?? '' ?>" class="btn btn-info">
                            <i class="ti ti-plus"></i> Ajouter un besoin
                        </a>
                        <a href="<?= $basepath ?>/sinistres/liste" class="btn btn-secondary">
                            <i class="ti ti-list"></i> Liste des sinistres
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-chart-pie"></i> Statistiques
                    </h5>
                    <div class="mb-3">
                        <p class="mb-1 text-muted"><small>Nombre de besoins</small></p>
                        <h4 class="fw-bold text-primary mb-0"><?= count($besoins ?? []) ?></h4>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <p class="mb-1 text-muted"><small>État</small></p>
                        <span class="badge bg-warning-subtle text-warning">En cours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
