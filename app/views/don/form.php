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
                            <li class="breadcrumb-item active" aria-current="page">Nouveau don</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 fw-semibold mt-2">
                        <i class="ti ti-gift text-success"></i> Enregistrer un Don
                    </h4>
                    <p class="mb-0 text-muted">Ajoutez un don en argent ou en nature</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= $basepath ?>/dons/insert" id="donForm">
                        <!-- Type de don -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Type de don *</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeArgent" value="argent" checked>
                                    <label class="form-check-label" for="typeArgent">
                                        <i class="ti ti-coin text-success"></i> Don en argent
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeNature" value="nature">
                                    <label class="form-check-label" for="typeNature">
                                        <i class="ti ti-package text-info"></i> Don en nature
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Donateur -->
                        <div class="mb-3">
                            <label for="donateur" class="form-label">Nom du donateur</label>
                            <input type="text" class="form-control" id="donateur" name="donateur" placeholder="Anonyme si vide">
                        </div>

                        <!-- Ville (optionnel) -->
                        <div class="mb-3">
                            <label for="id_ville" class="form-label">Ville destinataire (optionnel)</label>
                            <select class="form-select" id="id_ville" name="id_ville">
                                <option value="">-- Toutes les villes --</option>
                                <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Si spécifié, le don sera prioritairement attribué à cette ville</small>
                        </div>

                        <!-- Champs pour don en argent -->
                        <div id="champsDonArgent">
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant (Ar) *</label>
                                <input type="number" class="form-control" id="montant" name="montant" min="1" placeholder="Ex: 100000">
                            </div>
                        </div>

                        <!-- Champs pour don en nature -->
                        <div id="champsDonNature" style="display: none;">
                            <div class="mb-3">
                                <label for="id_categorie_besoin" class="form-label">Catégorie *</label>
                                <select class="form-select" id="id_categorie_besoin" name="id_categorie_besoin">
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Ex: Riz de qualité supérieure"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantite" class="form-label">Quantité *</label>
                                <input type="number" class="form-control" id="quantite" name="quantite" min="1" placeholder="Ex: 50">
                            </div>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i> Enregistrer le don
                            </button>
                            <a href="<?= $basepath ?>/dons/liste" class="btn btn-outline-secondary">
                                <i class="ti ti-x"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gérer l'affichage des champs selon le type de don
        document.getElementById('typeArgent').addEventListener('change', function() {
            document.getElementById('champsDonArgent').style.display = 'block';
            document.getElementById('champsDonNature').style.display = 'none';
            document.getElementById('montant').required = true;
            document.getElementById('id_categorie_besoin').required = false;
            document.getElementById('quantite').required = false;
        });

        document.getElementById('typeNature').addEventListener('change', function() {
            document.getElementById('champsDonArgent').style.display = 'none';
            document.getElementById('champsDonNature').style.display = 'block';
            document.getElementById('montant').required = false;
            document.getElementById('id_categorie_besoin').required = true;
            document.getElementById('quantite').required = true;
        });
    </script>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>