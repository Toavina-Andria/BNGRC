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
                            <li class="breadcrumb-item"><a href="<?= $basepath ?>/dons/liste">Dons</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?= $simulation ? 'Simulation' : 'Dispatch' ?>
                            </li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 fw-semibold mt-2">
                        <i class="ti ti-truck-delivery text-primary"></i> 
                        <?= $simulation ? 'Simulation du Dispatch' : 'Résultat du Dispatch' ?>
                    </h4>
                    <p class="mb-0 text-muted">Distribution automatique des dons aux besoins</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sélection de la méthode (avant simulation/résultat) -->
    <?php if ($simulation): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-3">
                            <i class="ti ti-settings"></i> Choisir la méthode de dispatch
                        </h5>
                        <p class="text-muted mb-3">Sélectionnez la stratégie de distribution qui convient le mieux à vos besoins :</p>
                        <form method="GET" class="d-flex gap-2 flex-wrap">
                            <button type="submit" name="methode" value="quantite" class="btn btn-outline-info <?= (!isset($methode) || $methode == 'quantite') ? 'active' : '' ?>">
                                <i class="ti ti-arrow-narrow-down"></i> Par Quantité
                                <small class="d-block text-muted">Petits besoins d'abord</small>
                            </button>
                            <button type="submit" name="methode" value="proportionnalite" class="btn btn-outline-success <?= isset($methode) && $methode == 'proportionnalite' ? 'active' : '' ?>">
                                <i class="ti ti-scale"></i> Proportionnelle
                                <small class="d-block text-muted">Distribution équitable</small>
                            </button>
                            <button type="submit" name="methode" value="ordre" class="btn btn-outline-primary <?= isset($methode) && $methode == 'ordre' ? 'active' : '' ?>">
                                <i class="ti ti-sort-ascending"></i> Par Ordre (FIFO)
                                <small class="d-block text-muted">Plus anciens d'abord</small>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Message de simulation -->
    <?php if ($simulation): ?>
        <div class="alert alert-info">
            <i class="ti ti-info-circle"></i>
            <strong>Mode Simulation :</strong> Ceci est une prévisualisation du dispatch utilisant la méthode 
            <strong>
                <?php 
                    $labels = [
                        'quantite' => 'Par Quantité (petits besoins d\'abord)',
                        'proportionnalite' => 'Proportionnelle',
                        'ordre' => 'Par Ordre - FIFO (plus anciens d\'abord)'
                    ];
                    echo $labels[$result['methode']] ?? 'Par Quantité';
                ?>
            </strong>.
            Aucune modification n'a été appliquée à la base de données. 
            Cliquez sur "Valider le Dispatch" pour appliquer réellement la distribution.
        </div>
    <?php else: ?>
        <div class="alert alert-success">
            <i class="ti ti-check"></i>
            <strong>Dispatch effectué avec succès !</strong> 
            Les dons ont été distribués aux besoins selon la méthode 
            <strong>
                <?php 
                    $labels = [
                        'quantite' => 'Par Quantité (petits besoins d\'abord)',
                        'proportionnalite' => 'Proportionnelle',
                        'ordre' => 'Par Ordre - FIFO (plus anciens d\'abord)'
                    ];
                    echo $labels[$result['methode']] ?? 'Par Quantité';
                ?>
            </strong>.
        </div>
    <?php endif; ?>

    <!-- Résumé du dispatch -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-success-subtle">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-success"><?= $result['dons_nature_traites'] ?></h3>
                    <p class="mb-0">Dons nature traités</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info-subtle">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-info"><?= $result['dons_argent_utilises'] ?></h3>
                    <p class="mb-0">Dons argent utilisés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary-subtle">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-primary"><?= number_format($result['montant_argent_utilise'], 0, ',', ' ') ?> Ar</h3>
                    <p class="mb-0">Montant utilisé</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning-subtle">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-warning"><?= $result['besoins_satisfaits'] ?></h3>
                    <p class="mb-0">Besoins satisfaits</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails du dispatch -->
    <?php if (!empty($result['details'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-3">Détails de la Distribution</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Catégorie</th>
                                        <th>Quantité</th>
                                        <th>Montant</th>
                                        <th>Ville</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result['details'] as $detail): ?>
                                        <tr>
                                            <td>
                                                <?php if ($detail['type'] == 'argent'): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ti ti-coin"></i> Argent
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-info-subtle text-info">
                                                        <i class="ti ti-package"></i> Nature
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($detail['categorie']) ?></td>
                                            <td><strong><?= $detail['quantite'] ?></strong> unités</td>
                                            <td>
                                                <?php if ($detail['type'] == 'argent' && isset($detail['montant'])): ?>
                                                    <!-- Montant utilisé (seulement pour dons argent) -->
                                                    <?= number_format($detail['montant'], 0, ',', ' ') ?> Ar
                                                <?php else: ?>
                                                    <!-- Pas de montant pour dons nature (gratuit) -->
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($detail['ville']) ?></td>
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

    <div class="row">
        <div class="col-12">
            <div class="d-flex gap-2">
                <?php if ($simulation): ?>
                    <a href="<?= $basepath ?>/dons/dispatch" class="btn btn-success">
                        <i class="ti ti-check"></i> Valider le Dispatch
                    </a>
                    <a href="<?= $basepath ?>/dons/liste" class="btn btn-outline-secondary">
                        <i class="ti ti-x"></i> Annuler
                    </a>
                <?php else: ?>
                    <a href="<?= $basepath ?>/" class="btn btn-primary">
                        <i class="ti ti-home"></i> Retour au tableau de bord
                    </a>
                    <a href="<?= $basepath ?>/dons/liste" class="btn btn-outline-secondary">
                        <i class="ti ti-list"></i> Voir les dons
                    </a>
                    <a href="<?= $basepath ?>/recapitulation" class="btn btn-outline-info">
                        <i class="ti ti-chart-bar"></i> Récapitulation
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
