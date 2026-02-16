<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un don</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Ajouter un don</h2>
    <form method="post" action="/dons/insert">
        <div class="mb-3">
            <label for="id_categorie_besoin" class="form-label">Categorie</label>
            <select class="form-control" id="id_categorie_besoin" name="id_categorie_besoin" required>
                <option value="">-- Selectionner une categorie --</option>
                <?php
                $categories = \Flight::db()->query('SELECT id, nom FROM bn_categorie_besoin ORDER BY nom')->fetchAll();
                foreach ($categories as $cat):
                ?>
                <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="donateur" class="form-label">Donateur</label>
            <input type="text" class="form-control" id="donateur" name="donateur">
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="quantite" class="form-label">Quantite</label>
            <input type="number" class="form-control" id="quantite" name="quantite" required min="1">
        </div>
        
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/dons/liste" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
</body>
</html>
