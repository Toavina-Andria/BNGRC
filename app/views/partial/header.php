<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $basepath ?>/">
            <i class="bi bi-shield-exclamation"></i> Gestion des Sinistres
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>" href="<?= $basepath ?>/dashboard">
                        <i class="bi bi-speedometer2"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'sinistres' ? 'active' : '' ?>" href="<?= $basepath ?>/sinistres">
                        <i class="bi bi-exclamation-triangle"></i> Sinistres
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'regions' ? 'active' : '' ?>" href="<?= $basepath ?>/regions">
                        <i class="bi bi-geo-alt"></i> Régions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'besoins' ? 'active' : '' ?>" href="<?= $basepath ?>/besoins">
                        <i class="bi bi-box-seam"></i> Besoins
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>