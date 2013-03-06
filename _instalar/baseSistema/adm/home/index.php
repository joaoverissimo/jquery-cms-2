<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";


//Lista os dados
$where = new dataFilter('jqueryadminmenu.cod', 0, dataFilter::op_different);
$orderBy = new dataOrder('jqueryadminmenu.ordem', 'asc');
$dados = dbJqueryadminmenu::ObjsList($Conexao, $where, $orderBy);
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryadminmenu'); ?> - Listar</title>

        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3>Home <small>Admin</small></h3>
                </div>

                <?php if ($dados !== false) : ?>
                    <ul class="thumbnails">
                        <?php foreach ($dados as $obj) : ?>
                            <?php if ($currentUser->validatePermissions($obj->getLink($adm_folder))): ?>

                                <li class="span3">
                                    <div class="thumbnail">
                                        <a href="<?php echo $obj->getLink($adm_folder); ?>" style="text-align: center;display: block;" class="btn">
                                            <img src="<?php echo $obj->objIcon()->getSrc(); ?>">
                                            <h3 style="text-align: center"><?php echo $obj->getTitulo(); ?></h3>
                                        </a>                                            
                                    </div>
                                </li>

                            <?php endif; ?>

                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    Não existem menus.
                <?php endif; ?>

            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>
