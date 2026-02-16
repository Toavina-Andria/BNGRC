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
                    <h4 class="mb-1 fw-semibold"><i class="ti ti-package text-info"></i> Ajouter un Besoin</h4>
                    <p class="mb-0 text-muted">Enregistrer un besoin associé à un sinistre</p>
                </div>
                <a href="<?= $basepath ?>/sinistres/liste" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Retour aux sinistres
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= $basepath ?>/sinistres/besoins/insert">
                        <div class="mb-4">
                            <label for="id_sinistre" class="form-label fw-semibold">Sinistre <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_sinistre" name="id_sinistre" required>
                                <option value="">Sélectionner un sinistre</option>
                                <?php if (!empty($sinistres)): ?>
                                    <?php foreach ($sinistres as $sinistre): ?>
                                        <option value="<?= $sinistre['id'] ?>">
                                            #<?= $sinistre['id'] ?> - <?= htmlspecialchars($sinistre['ville_nom'] ?? 'Ville') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Sélectionnez le sinistre concerné</small>
                        </div>

                        <div class="mb-4">
                            <label for="id_categorie_besoin" class="form-label fw-semibold">Catégorie de besoin <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_categorie_besoin" name="id_categorie_besoin" required>
                                <option value="">Sélectionner une catégorie</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $categorie): ?>
                                        <option value="<?= $categorie['id'] ?>">
                                            <?= htmlspecialchars($categorie['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Type de besoin (eau, nourriture, médicaments, etc.)</small>
                        </div>

                        <div class="mb-4">
                            <label for="quantite" class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantite" name="quantite" min="1" required>
                            <small class="text-muted">Quantité nécessaire</small>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description détaillée du besoin..."></textarea>
                            <small class="text-muted">Détails supplémentaires sur le besoin</small>
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
            <div class="card bg-info-subtle">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-info-circle"></i> Information
                    </h5>
                    <p class="mb-2"><small>Les besoins sont associés aux sinistres pour mieux gérer l'aide d'urgence</small></p>
                    <hr class="my-3">
                    <p class="mb-2"><small><i class="ti ti-check text-success"></i> Sinistre concerné</small></p>
                    <p class="mb-2"><small><i class="ti ti-check text-success"></i> Catégorie de besoin</small></p>
                    <p class="mb-2"><small><i class="ti ti-check text-success"></i> Quantité nécessaire</small></p>
                    <p class="mb-0"><small><i class="ti ti-circle text-muted"></i> Description (optionnel)</small></p>
                </div>
            </div>

            <div class="card bg-warning-subtle mt-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-2"><i class="ti ti-alert-triangle"></i> Note</h6>
                    <p class="mb-0"><small>Assurez-vous que le sinistre existe avant d'ajouter un besoin</small></p>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
