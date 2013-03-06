<div class="header">
    <div class="inner">
        <h1>
            <a href="<?php echo $adm_folder; ?>/">
                <img src="<?php echo $adm_folder; ?>/lib/css/logo.png" alt="Home"/>
            </a>
        </h1>

        <div class="actions">
            <div class="btn-group">
                <a href="<?php echo $adm_folder; ?>/home/logout.php" class="btn"><i class="icon-hand-left"></i> Logout</a>
                <a href="/" target="_blank" class="btn"><i class="icon-share"></i> Abrir o site</a>
            </div>
            <?php if (isset($currentUser)) : ?>
                <p class="username">Olá <b><?php echo $currentUser->getNome(); ?></b>, bem vindo novamente.</p>
            <?php endif; ?>
        </div>

        <div class="navbar">
            <div class="navbar-inner">
                <?php include 'navbar.php'; ?>
            </div>
        </div>
    </div>
</div>
