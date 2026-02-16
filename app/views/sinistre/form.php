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
            <label for="id_ville" class="form-label">Ville</label>
            <select class="form-control" id="id_ville" name="id_ville" required>
                <option value="">-- Sélectionner une ville --</option>
                <?php
                $villes = \Flight::db()->query('SELECT id, nom FROM bn_ville ORDER BY nom')->fetchAll();
                foreach ($villes as $ville):
                ?>
                <option value="<?= htmlspecialchars($ville['id']) ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="nombre_sinistres" class="form-label">Nombre de sinistres</label>
            <input type="number" class="form-control" id="nombre_sinistres" name="nombre_sinistres" required min="1">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/sinistres/liste" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
