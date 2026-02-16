<?php
$current_page = 'sinistres';
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
                            <li class="breadcrumb-item"><a href="<?= $basepath ?>/sinistres/liste">Sinistres</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Ajouter un besoin</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 fw-semibold mt-2">
                        <i class="ti ti-package text-info"></i> Ajouter un Besoin
                    </h4>
                    <p class="mb-0 text-muted">Enregistrer un besoin pour le sinistre #<?= htmlspecialchars($sinistre_id) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= $basepath ?>/sinistres/besoins/insert">
                        <!-- Sinistre ID (caché) -->
                        <input type="hidden" name="id_sinistre" value="<?= htmlspecialchars($sinistre_id) ?>">
                        
                        <div class="mb-3">
                            <label for="id_categorie_besoin" class="form-label fw-semibold">Catégorie du besoin *</label>
                            <select class="form-select" id="id_categorie_besoin" name="id_categorie_besoin" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                <?php
                                $categories = \Flight::db()->query('SELECT id, nom FROM bn_categorie_besoin ORDER BY nom')->fetchAll();
                                foreach ($categories as $cat):
                                ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Précisions sur le besoin..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantite" class="form-label fw-semibold">Quantité *</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" required min="1" placeholder="Ex: 50">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="prix_unitaire" class="form-label fw-semibold">Prix Unitaire (Ar) *</label>
                                <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" required min="1" step="0.01" placeholder="Ex: 5000">
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle"></i> Le montant total de ce besoin sera: <strong><span id="montant_total">0</span> Ar</strong>
                        </div>
                        
                        <div class="mb-3 d-flex gap-2">
                            <button type="submit" name="action" value="add_another" class="btn btn-success">
                                <i class="ti ti-plus"></i> Enregistrer et ajouter un autre
                            </button>
                            <button type="submit" name="action" value="finish" class="btn btn-primary">
                                <i class="ti ti-check"></i> Enregistrer et terminer
                            </button>
                            <a href="<?= $basepath ?>/" class="btn btn-outline-secondary">
                                <i class="ti ti-x"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculer le montant total
        function updateMontantTotal() {
            const quantite = parseFloat(document.getElementById('quantite').value) || 0;
            const prixUnitaire = parseFloat(document.getElementById('prix_unitaire').value) || 0;
            const montantTotal = quantite * prixUnitaire;
            document.getElementById('montant_total').textContent = montantTotal.toLocaleString('fr-FR');
        }

        document.getElementById('quantite').addEventListener('input', updateMontantTotal);
        document.getElementById('prix_unitaire').addEventListener('input', updateMontantTotal);
    </script>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
