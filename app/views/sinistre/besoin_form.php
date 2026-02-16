<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un besoin de sinistre</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Ajouter un besoin au sinistre</h2>
    <form method="post" action="/sinistres/besoins/insert">
        <div class="mb-3">
            <label for="id_sinistre" class="form-label">ID sinistre</label>
            <input type="text" class="form-control" id="id_sinistre" name="id_sinistre" required>
        </div>
        <div class="mb-3">
            <label for="id_categorie_besoin" class="form-label">ID catégorie besoin</label>
            <input type="text" class="form-control" id="id_categorie_besoin" name="id_categorie_besoin" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" class="form-control" id="quantite" name="quantite" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/sinistres/liste" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
