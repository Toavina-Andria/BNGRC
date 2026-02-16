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
                    <h4 class="mb-1 fw-semibold"><i class="ti ti-alert-triangle text-danger"></i> Ajouter un Sinistre</h4>
                    <p class="mb-0 text-muted">Enregistrer un nouveau sinistre dans le système</p>
                </div>
                <a href="<?= $basepath ?>/sinistres" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= $basepath ?>/sinistres/insert">
                        <div class="mb-4">
                            <label for="id_ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_ville" name="id_ville" required>
                                <option value="">Sélectionner une ville</option>
                                <?php if (!empty($villes)): ?>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?= $ville['id'] ?>">
                                            <?= htmlspecialchars($ville['nom']) ?> - <?= htmlspecialchars($ville['region_nom'] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Sélectionnez la ville touchée par le sinistre</small>
                        </div>

                        <div class="mb-4">
                            <label for="nombre_sinistres" class="form-label fw-semibold">Nombre de sinistres <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="nombre_sinistres" name="nombre_sinistres" min="1" required>
                            <small class="text-muted">Indiquez le nombre de sinistres recensés</small>
                        </div>

                        <div class="mb-4">
                            <label for="date_sinistre" class="form-label fw-semibold">Date du sinistre</label>
                            <input type="date" class="form-control" id="date_sinistre" name="date_sinistre" value="<?= date('Y-m-d') ?>">
                            <small class="text-muted">Date de survenance du sinistre</small>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description détaillée du sinistre..."></textarea>
                            <small class="text-muted">Décrivez la nature et les circonstances du sinistre</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Enregistrer
                            </button>
                            <a href="<?= $basepath ?>/sinistres/liste" class="btn btn-secondary">
                                <i class="ti ti-x"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card bg-primary-subtle">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-info-circle"></i> Information
                    </h5>
                    <p class="mb-2"><small>Assurez-vous de remplir tous les champs obligatoires marqués d'une étoile *</small></p>
                    <hr class="my-3">
                    <p class="mb-2"><small><i class="ti ti-check text-success"></i> Ville concernée</small></p>
                    <p class="mb-2"><small><i class="ti ti-check text-success"></i> Nombre de sinistres</small></p>
                    <p class="mb-2"><small><i class="ti ti-circle text-muted"></i> Date (optionnel)</small></p>
                    <p class="mb-0"><small><i class="ti ti-circle text-muted"></i> Description (optionnel)</small></p>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
