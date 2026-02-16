<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Sinistres</title>
    <link href="<?= $basepath ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $basepath ?>/assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?=  $basepath ?>/assets/css/dashboard.css">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $basepath ?>/">
                <i class="bi bi-shield-exclamation"></i> Gestion des Sinistres
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= $basepath ?>/dashboard">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $basepath ?>/sinistres">
                            <i class="bi bi-exclamation-triangle"></i> Sinistres
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $basepath ?>/regions">
                            <i class="bi bi-geo-alt"></i> Régions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $basepath ?>/besoins">
                            <i class="bi bi-box-seam"></i> Besoins
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Sinistres</h6>
                                <h2 class="mb-0"><?= $stats['total_sinistres'] ?? 0 ?></h2>
                            </div>
                            <div class="text-danger">
                                <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
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
                                <h6 class="text-muted mb-2">Villes Affectées</h6>
                                <h2 class="mb-0"><?= $stats['villes_affectees'] ?? 0 ?></h2>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-building" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Besoins Totaux</h6>
                                <h2 class="mb-0"><?= $stats['total_besoins'] ?? 0 ?></h2>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-cart-fill" style="font-size: 3rem;"></i>
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
                                <h6 class="text-muted mb-2">Régions</h6>
                                <h2 class="mb-0"><?= $stats['total_regions'] ?? 0 ?></h2>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-geo-fill" style="font-size: 3rem;"></i>
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
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-circle text-danger"></i> Sinistres Récents
                        </h5>
                        <a href="<?= $basepath ?>/sinistres" class="btn btn-sm btn-outline-primary">
                            Voir tout <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
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

            <!-- Besoins par Catégorie -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart text-info"></i> Besoins par Catégorie
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($besoins_categories)): ?>
                            <?php foreach ($besoins_categories as $besoin): ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold"><?= htmlspecialchars($besoin['categorie']) ?></span>
                                        <span class="badge badge-need bg-primary">
                                            <?= number_format($besoin['quantite_totale']) ?>
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" 
                                             role="progressbar" 
                                             style="width: <?= $besoin['pourcentage'] ?>%"
                                             aria-valuenow="<?= $besoin['pourcentage'] ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= round($besoin['pourcentage'], 1) ?>%
                                        </div>
                                    </div>
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

                <!-- Top Régions Affectées -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart text-success"></i> Top Régions Affectées
                        </h5>
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
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check text-warning"></i> Besoins Détaillés par Sinistre
                        </h5>
                        <a href="<?= $basepath ?>/besoins/create" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Ajouter un besoin
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-dark">
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

    <!-- Footer -->
    <footer class="mt-5 py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-shield-check"></i> Système de Gestion des Sinistres &copy; <?= date('Y') ?>
            </p>
        </div>
    </footer>

    <script src="<?= $basepath ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
