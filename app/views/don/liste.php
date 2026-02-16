<?php
$current_page = 'dons';
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
                            <li class="breadcrumb-item active" aria-current="page">Liste des dons</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 fw-semibold mt-2">
                        <i class="ti ti-gift text-success"></i> Liste des Dons Reçus
                    </h4>
                    <p class="mb-0 text-muted">Tous les dons enregistrés dans le système</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $basepath ?>/dons/insert" class="btn btn-sm btn-success">
                        <i class="ti ti-plus"></i> Nouveau don
                    </a>
                    <a href="<?= $basepath ?>/dons/dispatch" class="btn btn-sm btn-primary">
                        <i class="ti ti-truck-delivery"></i> Dispatcher les dons
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des dons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($dons)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Donateur</th>
                                        <th>Catégorie</th>
                                        <th>Description</th>
                                        <th>Montant/Quantité</th>
                                        <th>Ville</th>
                                        <th>Statut</th>
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
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($don['description'] ?? '-') ?></td>
                                        <td class="fw-semibold">
                                            <?php if ($don['type'] == 'argent'): ?>
                                                <?= number_format($don['montant'], 0, ',', ' ') ?> Ar
                                                <?php if ($don['montant_restant'] < $don['montant']): ?>
                                                    <div class="text-success fs-2">
                                                        Restant: <?= number_format($don['montant_restant'], 0, ',', ' ') ?> Ar
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?= $don['quantite'] ?> unités
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($don['ville_nom'] ?? 'Toutes') ?></td>
                                        <td>
                                            <?php 
                                                if ($don['type'] == 'argent') {
                                                    if ($don['montant_restant'] > 0) {
                                                        echo '<span class="badge bg-success">Disponible</span>';
                                                    } else {
                                                        echo '<span class="badge bg-secondary">Utilisé</span>';
                                                    }
                                                } else {
                                                    if ($don['quantite'] > 0) {
                                                        echo '<span class="badge bg-info">Disponible</span>';
                                                    } else {
                                                        echo '<span class="badge bg-secondary">Distribué</span>';
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-inbox fs-8"></i>
                            <p class="mt-2 mb-0">Aucun don enregistré</p>
                            <a href="<?= $basepath ?>/dons/insert" class="btn btn-success mt-3">
                                <i class="ti ti-plus"></i> Enregistrer le premier don
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
