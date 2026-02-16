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
                    <a href="<?= $basepath ?>/sinistres/besoins/insert" class="btn btn-info">
                        <i class="ti ti-package"></i> Ajouter un besoin
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
                                    <th><h6 class="fs-4 fw-semibold mb-0">Nombre</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Date</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Description</h6></th>
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
                                                <p class="mb-0"><?= htmlspecialchars($sinistre['ville'] ?? 'N/A') ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <?= htmlspecialchars($sinistre['region'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <?= $sinistre['nombre_sinistres'] ?? 0 ?>
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-muted">
                                                <i class="ti ti-calendar"></i>
                                                <?= isset($sinistre['date_sinistre']) ? date('d/m/Y', strtotime($sinistre['date_sinistre'])) : 'N/A' ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <?= htmlspecialchars($sinistre['description'] ?? 'Aucune description') ?>
                                            </p>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= $basepath ?>/sinistres/view/<?= $sinistre['id'] ?>" 
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="ti ti-eye fs-5"></i>
                                                </a>
                                                <a href="<?= $basepath ?>/sinistres/edit/<?= $sinistre['id'] ?>" 
                                                   class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="ti ti-pencil fs-5"></i>
                                                </a>
                                                <a href="<?= $basepath ?>/sinistres/delete/<?= $sinistre['id'] ?>" 
                                                   class="btn btn-sm btn-danger" title="Supprimer"
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sinistre ?')">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
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
