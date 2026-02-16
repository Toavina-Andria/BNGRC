<?php
// Ensure $basepath is defined
if (!isset($basepath)) {
    $basepath = Flight::get('base_path');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Sinistres BNGRC</title>
    <link rel="stylesheet" href="<?= $basepath ?>/assets/css/styles.min.css" />
    <link rel="stylesheet" href="<?= $basepath ?>/assets/css/icons/tabler-icons/tabler-icons.css" />
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed"></body>