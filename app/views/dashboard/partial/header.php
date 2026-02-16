<!-- Sidebar Navigation -->
<div class="sidebar">
    <div class="sidebar-header">
        <a href="<?= $basepath ?>/" class="sidebar-brand">
            <i class="bi bi-shield-exclamation"></i>
            <span>Sinistres</span>
        </a>
    </div>
    
    <div class="sidebar-menu">
        <div class="menu-section">Menu</div>
        
        <a href="<?= $basepath ?>/dashboard" class="menu-item <?= $current_page === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        
        <div class="menu-section">Gestion</div>
        
        <a href="<?= $basepath ?>/sinistres" class="menu-item <?= $current_page === 'sinistres' ? 'active' : '' ?>">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Sinistres</span>
        </a>
        
        <a href="<?= $basepath ?>/besoins" class="menu-item <?= $current_page === 'besoins' ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i>
            <span>Besoins</span>
        </a>
        
        <a href="<?= $basepath ?>/villes" class="menu-item <?= $current_page === 'villes' ? 'active' : '' ?>">
            <i class="bi bi-geo-alt"></i>
            <span>Villes</span>
        </a>
        
        <a href="<?= $basepath ?>/regions" class="menu-item <?= $current_page === 'regions' ? 'active' : '' ?>">
            <i class="bi bi-map"></i>
            <span>Régions</span>
        </a>
    </div>
</div>