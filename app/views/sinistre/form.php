<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un sinistre</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Ajouter un sinistre</h2>
    <form method="post" action="/sinistres/insert">
        <div class="mb-3">
            <label for="ville" class="form-label">ID ville</label>
            <input type="text" class="form-control" id="id_ville" name="id_ville" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de sinistres</label>
            <input type="number" class="form-control" id="nombre_sinistres" name="nombre_sinistres" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/sinistres/liste" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
