<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";

$template = array(
    "htmlstart" => "<ul class='#ulclass#'>\n",
    "htmlend" => "</ul>",
    "li" => "\n\t<li><span>getTitulo</span> <a href='editar.php?cod=getCod'>__(editar)</a></li>",
    "lisub" => "\n\t<li><span>getTitulo</span> <a href='editar.php?cod=getCod'>__(editar)</a>#getmenu#</li>",
    "nivelclass" => array(
        0 => "treecheckbox",
        1 => ""
    )
);
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryadmingrupo'); ?> - Editar</title>

        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryadminmenu'); ?> <small>Listar</small></h3>
                </div>

                <div class="btn-toolbar">
                    <a href="inserir.php" class="btn btn-primary"><i class="icon-plus icon-white"></i> Inserir</a>                    
                </div>
                                
                <?php echo $msg; ?>
                <?php echo dbJqueryadminmenu::getMenu($Conexao, $adm_folder, 0, 0, $template); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>