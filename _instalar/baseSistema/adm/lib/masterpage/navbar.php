<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            Menu do sistema
        </div>
        <div id="btn-side-bar-menu" class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <?php
                if (isset($currentUser)) {
                    echo dbJqueryadminmenu::getMenuValidate($Conexao, $adm_folder, 0, 0, null, $currentUser);
                }
                ?>
            </nav>
        </div>

    </div>

</aside>
<!-- end: sidebar -->
