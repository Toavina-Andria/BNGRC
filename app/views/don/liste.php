<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des dons</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Liste des dons</h2>
    <div class="mb-3">
        <a href="/dons/insert" class="btn btn-success">Ajouter un don</a>
        <a href="/dons/dispatch" class="btn btn-warning">Dispatcher les dons</a>
    </div>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Categorie</th>
                <th>Donateur</th>
                <th>Description</th>
                <th>Quantite</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($dons as $don): ?>
            <tr>
                <td><?= htmlspecialchars($don['id']) ?></td>
                <td><?= htmlspecialchars($don['categorie_nom']) ?></td>
                <td><?= htmlspecialchars($don['donateur'] ?? '-') ?></td>
                <td><?= htmlspecialchars($don['description'] ?? '-') ?></td>
                <td><?= htmlspecialchars($don['quantite']) ?></td>
                <td><?= htmlspecialchars($don['date_don'] ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
