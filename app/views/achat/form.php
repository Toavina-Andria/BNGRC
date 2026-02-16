<?php require_once __DIR__ . '/../dashboard/partial/head.php'; ?>
<?php require_once __DIR__ . '/../dashboard/partial/header.php'; ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">
                <i class="ti ti-shopping-cart"></i> Effectuer un Achat
            </h5>

            <?php if ($existe_dans_dons): ?>
                <div class="alert alert-danger">
                    <i class="ti ti-alert-triangle"></i>
                    <strong>Attention !</strong> Ce besoin existe déjà dans les dons en nature disponibles. 
                    Veuillez utiliser le dispatch automatique plutôt que d'acheter.
                    <div class="mt-2">
                        <a href="<?= $basepath ?>/dons/simuler" class="btn btn-sm btn-primary">
                            <i class="ti ti-eye"></i> Simuler le dispatch
                        </a>
                        <a href="<?= $basepath ?>/achats/besoins-restants" class="btn btn-sm btn-secondary">
                            <i class="ti ti-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            <?php else: ?>

            <!-- Informations du besoin -->
            <div class="card bg-light-primary mb-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="ti ti-info-circle"></i> Détails du Besoin</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Ville :</strong><br>
                            <span class="badge bg-primary"><?= htmlspecialchars($besoin['ville_nom']) ?></span>
                        </div>
                        <div class="col-md-3">
                            <strong>Région :</strong><br>
                            <?= htmlspecialchars($besoin['region_nom']) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Catégorie :</strong><br>
                            <?= htmlspecialchars($besoin['categorie_nom']) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Sinistres :</strong><br>
                            <?= number_format($besoin['nombre_sinistres'], 0, ',', ' ') ?> personnes
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Description :</strong><br>
                            <?= htmlspecialchars($besoin['description']) ?>
                        </div>
                        <div class="col-md-2">
                            <strong>Quantité restante :</strong><br>
                            <span class="text-primary fs-5"><?= number_format($besoin['quantite'], 0, ',', ' ') ?></span>
                        </div>
                        <div class="col-md-2">
                            <strong>Prix unitaire :</strong><br>
                            <span class="fs-5"><?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar</span>
                        </div>
                        <div class="col-md-2">
                            <strong>Montant total :</strong><br>
                            <span class="text-success fw-bold fs-5">
                                <?= number_format($besoin['montant_total'], 2, ',', ' ') ?> Ar
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'achat -->
            <form method="POST" action="<?= $basepath ?>/achats/insert" id="formAchat">
                <input type="hidden" name="id_besoin" value="<?= $besoin['id'] ?>">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="quantite" class="form-label">Quantité à acheter *</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" 
                               min="1" max="<?= $besoin['quantite'] ?>" required
                               oninput="calculerMontants()">
                        <small class="text-muted">Maximum : <?= number_format($besoin['quantite'], 0, ',', ' ') ?></small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="id_don_argent" class="form-label">Don en argent à utiliser *</label>
                        <select class="form-select" id="id_don_argent" name "id_don_argent" required onchange="verifierMontant()">
                            <option value="">-- Sélectionner un don --</option>
                            <?php foreach ($dons_argent as $don): ?>
                                <option value="<?= $don['id_don'] ?>" 
                                        data-montant="<?= $don['montant_restant'] ?>">
                                    <?= htmlspecialchars($don['donateur']) ?> - 
                                    <?= number_format($don['montant_restant'], 2, ',', ' ') ?> Ar disponible
                                    <?php if ($don['ville_nom']): ?>
                                        (<?= htmlspecialchars($don['ville_nom']) ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Récapitulatif des montants -->
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="ti ti-calculator"></i> Calcul du montant</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Montant de base :</strong><br>
                                <span id="montant_base" class="fs-5">0.00 Ar</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Frais (<?= number_format($frais_pourcentage, 1) ?>%) :</strong><br>
                                <span id="montant_frais" class="fs-5 text-warning">0.00 Ar</span>
                            </div>
                            <div class="col-md-3">
                                <strong>TOTAL AVEC FRAIS :</strong><br>
                                <span id="montant_total" class="fs-4 fw-bold text-danger">0.00 Ar</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Montant disponible :</strong><br>
                                <span id="montant_disponible" class="fs-5 text-success">-- Ar</span>
                            </div>
                        </div>
                        <div id="alert_insuffisant" class="alert alert-danger mt-3" style="display:none;">
                            <i class="ti ti-alert-circle"></i> Le don sélectionné n'a pas assez d'argent disponible !
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= $basepath ?>/achats/besoins-restants" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-success" id="btnSubmit">
                        <i class="ti ti-check"></i> Valider l'achat
                    </button>
                </div>
            </form>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const prixUnitaire = <?= $besoin['prix_unitaire'] ?>;
const fraisPourcentage = <?= $frais_pourcentage ?>;
const quantiteMax = <?= $besoin['quantite'] ?>;

function calculerMontants() {
    const quantite = parseFloat(document.getElementById('quantite').value) || 0;
    
    if (quantite > quantiteMax) {
        document.getElementById('quantite').value = quantiteMax;
        return calculerMontants();
    }
    
    const montantBase = quantite * prixUnitaire;
    const montantFrais = montantBase * (fraisPourcentage / 100);
    const montantTotal = montantBase + montantFrais;
    
    document.getElementById('montant_base').textContent = montantBase.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Ar';
    document.getElementById('montant_frais').textContent = montantFrais.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Ar';
    document.getElementById('montant_total').textContent = montantTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Ar';
    
    verifierMontant();
}

function verifierMontant() {
    const select = document.getElementById('id_don_argent');
    const selectedOption = select.options[select.selectedIndex];
    const montantDisponible = parseFloat(selectedOption.getAttribute('data-montant')) || 0;
    
    document.getElementById('montant_disponible').textContent = montantDisponible.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Ar';
    
    const quantite = parseFloat(document.getElementById('quantite').value) || 0;
    const montantTotal = quantite * prixUnitaire * (1 + (fraisPourcentage / 100));
    
    const alertDiv = document.getElementById('alert_insuffisant');
    const btnSubmit = document.getElementById('btnSubmit');
    
    if (montantDisponible > 0 && montantTotal > montantDisponible) {
        alertDiv.style.display = 'block';
        btnSubmit.disabled = true;
    } else {
        alertDiv.style.display = 'none';
        btnSubmit.disabled = false;
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('quantite').addEventListener('input', calculerMontants);
    document.getElementById('id_don_argent').addEventListener('change', verifierMontant);
});
</script>

<?php require_once __DIR__ . '/../dashboard/partial/footer.php'; ?>
