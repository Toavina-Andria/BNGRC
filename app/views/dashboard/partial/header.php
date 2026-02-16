    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="<?= $basepath ?>/" class="text-nowrap logo-img">
            <span class="fs-5 fw-bold text-primary"><i class="ti ti-shield-exclamation"></i> BNGRC</span>
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-6"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Menu Principal</span>
            </li>
            <li class="sidebar-item <?= $current_page === 'dashboard' ? 'selected' : '' ?>">
              <a class="sidebar-link" href="<?= $basepath ?>/" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Gestion</span>
            </li>
            
            <li class="sidebar-item <?= $current_page === 'sinistres' ? 'selected' : '' ?>">
              <a class="sidebar-link" href="<?= $basepath ?>/sinistres/liste" aria-expanded="false">
                <span>
                  <i class="ti ti-alert-triangle"></i>
                </span>
                <span class="hide-menu">Sinistres</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <div class="body-wrapper-inner">
        <div class="container-fluid">