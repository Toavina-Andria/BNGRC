<?php
$current_page = 'dashboard';
?>

<?php require_once __DIR__ . '/partial/head.php'; ?>
<?php require_once __DIR__ . '/partial/header.php'; ?>

<div class="main-content">
    
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Tableau de Bord</h1>
        <p class="text-muted">Vue d'ensemble des sinistres et besoins</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Sinistres</div>
                            <div class="stat-value"><?= $stats['total_sinistres'] ?? 0 ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Villes Affectées</div>
                            <div class="stat-value"><?= $stats['villes_affectees'] ?? 0 ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Besoins Totaux</div>
                            <div class="stat-value"><?= $stats['total_besoins'] ?? 0 ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-cart-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Régions</div>
                            <div class="stat-value"><?= $stats['total_regions'] ?? 0 ?></div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-geo-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">

        <!-- Sinistres Récents -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-exclamation-circle text-danger"></i> Sinistres Récents
                    </span>
                    <a href="<?= $basepath ?>/sinistres" class="btn btn-sm btn-primary">
                        Voir tout <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ville</th>
                                    <th>Région</th>
                                    <th>Population</th>
                                    <th>Nombre</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sinistres)): ?>
                                    <?php foreach ($sinistres as $sinistre): ?>
                                        <tr>
                                            <td><strong>#<?= $sinistre['id'] ?></strong></td>
                                            <td>
                                                <i class="bi bi-geo-alt-fill text-primary"></i>
                                                <?= htmlspecialchars($sinistre['ville_nom']) ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($sinistre['region_nom']) ?>
                                                </span>
                                            </td>
                                            <td><?= number_format($sinistre['population']) ?></td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <?= $sinistre['nombre_sinistres'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= $basepath ?>/sinistres/view/<?= $sinistre['id'] ?>" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= $basepath ?>/sinistres/edit/<?= $sinistre['id'] ?>" 
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p class="mt-2">Aucun sinistre enregistré</p>
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

            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pie-chart text-info"></i> Besoins par Catégorie
                </div>
                <div class="card-body">
                    <?php if (!empty($besoins_categories)): ?>
                        <?php foreach ($besoins_categories as $besoin): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold"><?= htmlspecialchars($besoin['categorie']) ?></span>
                                    <span class="badge bg-primary">
                                        <?= number_format($besoin['quantite_totale']) ?>
                                    </span>
                                </div>
                                <div class="progress">
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
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Aucun besoin enregistré</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-bar-chart text-success"></i> Top Régions Affectées
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($top_regions)): ?>
                            <?php foreach ($top_regions as $index => $region): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="badge bg-secondary me-2"><?= $index + 1 ?></span>
                                        <strong><?= htmlspecialchars($region['nom']) ?></strong>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">
                                        <?= $region['nombre_sinistres'] ?> sinistre(s)
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center text-muted">
                                Aucune donnée disponible
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Besoins Détaillés -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-list-check text-warning"></i> Besoins Détaillés par Sinistre
                    </span>
                    <a href="<?= $basepath ?>/besoins/create" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter un besoin
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Sinistre</th>
                                    <th>Ville</th>
                                    <th>Catégorie</th>
                                    <th>Description</th>
                                    <th>Quantité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($besoins_details)): ?>
                                    <?php foreach ($besoins_details as $besoin): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-dark">#<?= $besoin['id_sinistre'] ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($besoin['ville_nom']) ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= htmlspecialchars($besoin['categorie_nom']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($besoin['description'] ?? 'N/A') ?></td>
                                            <td>
                                                <strong class="text-primary">
                                                    <?= number_format($besoin['quantite']) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <a href="<?= $basepath ?>/besoins/edit/<?= $besoin['id'] ?>" 
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= $basepath ?>/besoins/delete/<?= $besoin['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p class="mt-2">Aucun besoin détaillé disponible</p>
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

</div>

<?php require_once __DIR__ . '/partial/footer.php'; ?>