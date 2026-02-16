<?php
$current_page = 'villes';
?>

<?php require_once __DIR__ . '/../dashboard/partial/head.php'; ?>
<?php require_once __DIR__ . '/../dashboard/partial/header.php'; ?>

    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= $basepath ?>/">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Détails de la ville</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 fw-semibold mt-2">
                        <i class="ti ti-map-pin text-primary"></i> <?= htmlspecialchars($ville['nom']) ?>
                    </h4>
                    <p class="mb-0 text-muted">Population: <?= number_format($ville['population']) ?> habitants</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $basepath ?>/" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-arrow-left"></i> Retour
                    </a>
                    <a href="<?= $basepath ?>/sinistres/besoins/insert" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus"></i> Ajouter un besoin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3 fw-semibold">Besoins Totaux</h5>
                    <h3 class="fw-bold text-primary"><?= number_format($total_besoins_montant, 0, ',', ' ') ?> Ar</h3>
                    <p class="text-muted mb-0"><?= $total_besoins_quantite ?> unités nécessaires</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3 fw-semibold">Dons en Argent</h5>
                    <h3 class="fw-bold text-success"><?= number_format($total_dons_argent, 0, ',', ' ') ?> Ar</h3>
                    <p class="text-muted mb-0">Disponible pour distribution</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3 fw-semibold">Dons en Nature</h5>
                    <h3 class="fw-bold text-info"><?= $total_dons_nature ?></h3>
                    <p class="text-muted mb-0">Dons matériels reçus</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sinistres dans cette ville -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-alert-triangle text-danger"></i> Sinistres Enregistrés
                    </h5>
                    <?php if (!empty($sinistres)): ?>
                        <div class="list-group">
                            <?php foreach ($sinistres as $sinistre): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Sinistre #<?= $sinistre['id'] ?></h6>
                                            <p class="mb-0 text-muted fs-2">
                                                <?= $sinistre['nombre_sinistres'] ?> personnes affectées
                                            </p>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning">
                                            <?= $sinistre['nb_besoins'] ?> besoins
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">Aucun sinistre enregistré</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Besoins détaillés -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-package text-primary"></i> Détails des Besoins
                    </h5>
                    <?php if (!empty($besoins)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Catégorie</th>
                                        <th>Description</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($besoins as $besoin): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <?= htmlspecialchars($besoin['categorie_nom']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($besoin['description']) ?></td>
                                            <td><strong><?= $besoin['quantite'] ?></strong></td>
                                            <td><?= number_format($besoin['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                            <td class="fw-semibold">
                                                <?= number_format($besoin['quantite'] * $besoin['prix_unitaire'], 0, ',', ' ') ?> Ar
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="4" class="text-end">TOTAL:</th>
                                        <th><?= number_format($total_besoins_montant, 0, ',', ' ') ?> Ar</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Besoins par catégorie (résumé) -->
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-3">Résumé par Catégorie</h6>
                            <div class="row">
                                <?php foreach ($besoins_par_categorie as $cat => $data): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3">
                                            <h6 class="text-primary mb-1"><?= htmlspecialchars($cat) ?></h6>
                                            <p class="mb-1"><strong>Quantité:</strong> <?= $data['quantite'] ?> unités</p>
                                            <p class="mb-0"><strong>Montant:</strong> <?= number_format($data['montant'], 0, ',', ' ') ?> Ar</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-inbox fs-8"></i>
                            <p class="mt-2 mb-0">Aucun besoin enregistré pour cette ville</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Dons attribués à cette ville -->
    <?php if (!empty($dons)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-3">
                            <i class="ti ti-gift text-success"></i> Dons Attribués à cette Ville
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Donateur</th>
                                        <th>Détails</th>
                                        <th>Montant/Quantité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dons as $don): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($don['date_don'])) ?></td>
                                            <td>
                                                <?php if ($don['type'] == 'argent'): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ti ti-coin"></i> Argent
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-info-subtle text-info">
                                                        <i class="ti ti-package"></i> Nature
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($don['donateur'] ?? 'Anonyme') ?></td>
                                            <td>
                                                <?php if ($don['type'] == 'nature'): ?>
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <?= htmlspecialchars($don['categorie_nom']) ?>
                                                    </span>
                                                    <div class="text-muted fs-2">
                                                        <?= htmlspecialchars($don['description']) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Don en espèces</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-semibold">
                                                <?php if ($don['type'] == 'argent'): ?>
                                                    <?= number_format($don['montant_restant'], 0, ',', ' ') ?> Ar
                                                    <?php if ($don['montant_restant'] < $don['montant']): ?>
                                                        <div class="text-muted fs-2">
                                                            (<?= number_format($don['montant'], 0, ',', ' ') ?> Ar initialement)
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?= $don['quantite'] ?> unités
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
