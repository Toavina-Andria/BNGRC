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
                    <h4 class="mb-1 fw-semibold"><i class="ti ti-pencil text-warning"></i> Modifier le Sinistre #<?= $sinistre['id'] ?? 'N/A' ?></h4>
                    <p class="mb-0 text-muted">Mettre à jour les informations du sinistre</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $basepath ?>/sinistres/view/<?= $sinistre['id'] ?? '#' ?>" class="btn btn-info">
                        <i class="ti ti-eye"></i> Voir
                    </a>
                    <a href="<?= $basepath ?>/sinistres" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= $basepath ?>/sinistres/update/<?= $sinistre['id'] ?? '' ?>">
                        <input type="hidden" name="id" value="<?= $sinistre['id'] ?? '' ?>">
                        
                        <div class="mb-4">
                            <label for="id_ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_ville" name="id_ville" required>
                                <option value="">Sélectionner une ville</option>
                                <?php if (!empty($villes)): ?>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?= $ville['id'] ?>" <?= ($sinistre['id_ville'] ?? '') == $ville['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ville['nom']) ?> - <?= htmlspecialchars($ville['region_nom'] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Sélectionnez la ville touchée par le sinistre</small>
                        </div>

                        <div class="mb-4">
                            <label for="nombre_sinistres" class="form-label fw-semibold">Nombre de sinistres <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="nombre_sinistres" name="nombre_sinistres" 
                                   value="<?= $sinistre['nombre_sinistres'] ?? 0 ?>" min="1" required>
                            <small class="text-muted">Indiquez le nombre de sinistres recensés</small>
                        </div>

                        <div class="mb-4">
                            <label for="date_sinistre" class="form-label fw-semibold">Date du sinistre</label>
                            <input type="date" class="form-control" id="date_sinistre" name="date_sinistre" 
                                   value="<?= isset($sinistre['date_sinistre']) ? date('Y-m-d', strtotime($sinistre['date_sinistre'])) : date('Y-m-d') ?>">
                            <small class="text-muted">Date de survenance du sinistre</small>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                      placeholder="Description détaillée du sinistre..."><?= htmlspecialchars($sinistre['description'] ?? '') ?></textarea>
                            <small class="text-muted">Décrivez la nature et les circonstances du sinistre</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-device-floppy"></i> Mettre à jour
                            </button>
                            <a href="<?= $basepath ?>/sinistres/view/<?= $sinistre['id'] ?? '#' ?>" class="btn btn-secondary">
                                <i class="ti ti-x"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card bg-warning-subtle">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-info-circle"></i> Information
                    </h5>
                    <p class="mb-2"><small>Vous êtes en train de modifier le sinistre <strong>#<?= $sinistre['id'] ?? 'N/A' ?></strong></small></p>
                    <hr class="my-3">
                    <p class="mb-2"><small><i class="ti ti-alert-triangle text-warning"></i> Les modifications seront enregistrées immédiatement</small></p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Informations actuelles</h6>
                    <p class="mb-2"><small class="text-muted">Ville: </small><strong><?= htmlspecialchars($sinistre['ville_nom'] ?? 'N/A') ?></strong></p>
                    <p class="mb-2"><small class="text-muted">Région: </small><strong><?= htmlspecialchars($sinistre['region_nom'] ?? 'N/A') ?></strong></p>
                    <p class="mb-0"><small class="text-muted">Nombre actuel: </small><strong><?= $sinistre['nombre_sinistres'] ?? 0 ?></strong></p>
                </div>
            </div>

            <div class="card bg-danger-subtle mt-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-2"><i class="ti ti-trash"></i> Zone Dangereuse</h6>
                    <p class="mb-2"><small>Supprimer définitivement ce sinistre</small></p>
                    <a href="<?= $basepath ?>/sinistres/delete/<?= $sinistre['id'] ?? '#' ?>" 
                       class="btn btn-danger btn-sm w-100"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sinistre ? Cette action est irréversible.')">
                        <i class="ti ti-trash"></i> Supprimer
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
