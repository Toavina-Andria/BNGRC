<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un besoin de sinistre</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Ajouter un besoin au sinistre #<?= htmlspecialchars($sinistre_id) ?></h2>
    <form method="post" action="/sinistres/besoins/insert">
        <!-- Sinistre ID (caché) -->
        <input type="hidden" name="id_sinistre" value="<?= htmlspecialchars($sinistre_id) ?>">
        
        <div class="mb-3">
            <label for="id_categorie_besoin" class="form-label">Catégorie besoin</label>
            <select class="form-control" id="id_categorie_besoin" name="id_categorie_besoin" required>
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
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" class="form-control" id="quantite" name="quantite" required min="1">
        </div>
        
        <div class="mb-3">
            <button type="submit" name="action" value="add_another" class="btn btn-success">Enregistrer et ajouter un autre</button>
            <button type="submit" name="action" value="finish" class="btn btn-primary">Enregistrer et terminer</button>
            <a href="/sinistres/liste" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
</body>
</html>
