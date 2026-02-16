<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des sinistres</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Liste des sinistres</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Ville</th>
                <th>Région</th>
                <th>Nombre</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($sinistres as $sinistre): ?>
            <tr>
                <td><?= $sinistre['id'] ?></td>
                <td><?= $sinistre['ville'] ?></td>
                <td><?= $sinistre['region'] ?></td>
                <td><?= $sinistre['nombre_sinistres'] ?></td>
                <td><?= $sinistre['date_sinistre'] ?></td>
                <td><?= $sinistre['description'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
