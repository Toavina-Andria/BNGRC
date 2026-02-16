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
                    <h4 class="mb-1 fw-semibold"><i class="ti ti-alert-triangle text-danger"></i> Liste des Sinistres</h4>
                    <p class="mb-0 text-muted">Gestion des sinistres enregistrés dans le système</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $basepath ?>/sinistres/insert" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Ajouter un sinistre
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sinistres Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 text-nowrap align-middle table-hover">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th><h6 class="fs-4 fw-semibold mb-0">ID</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Ville</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Région</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Population</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Nombre Sinistrés</h6></th>
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
                                                <p class="mb-0"><?= htmlspecialchars($sinistre['ville']) ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <?= htmlspecialchars($sinistre['region']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-0"><?= number_format($sinistre['population']) ?></p>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <?= $sinistre['nombre_sinistres'] ?> personnes
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= $basepath ?>/villes/besoins?id=<?= $sinistre['id_ville'] ?>" class="btn btn-sm btn-info">
                                                <i class="ti ti-eye"></i> Voir détails
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="ti ti-inbox fs-8"></i>
                                        <p class="mt-2 mb-0">Aucun sinistre enregistré</p>
                                        <a href="<?= $basepath ?>/sinistres/insert" class="btn btn-sm btn-primary mt-3">
                                            <i class="ti ti-plus"></i> Ajouter le premier sinistre
                                        </a>
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

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
