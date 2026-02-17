<?php
$current_page = 'dashboard';
?>

<?php require_once __DIR__ . '/partial/head.php'; ?>
<?php require_once __DIR__ . '/partial/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title fw-semibold">Confirmer la réinitialisation</h4>
                    <p class="card-text">
                        Cette action supprimera <strong>toutes les données</strong> saisies (sinistres, dons, achats, villes, etc.)
                        du système. Cette opération est irréversible. Voulez-vous continuer ?
                    </p>
                    <form method="post" action="<?= $basepath ?>/reset">
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash"></i> Oui, supprimer toutes les données
                        </button>
                        <a href="<?= $basepath ?>" class="btn btn-secondary ms-2">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partial/footer.php'; ?>