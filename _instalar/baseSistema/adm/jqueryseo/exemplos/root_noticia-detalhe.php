<?php
require_once 'jquerycms/config.php';

if (!isset($_REQUEST['cod'])) {
    header("Location: noticia.php");
}

$obj = new objNoticia($Conexao);
$obj->loadByCod($_REQUEST['cod']);
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_noticia'); ?> - # <?php echo $obj->getCod(); ?></title>

        <?php include 'masterpage/head.php'; ?>
        <style>
            .seo_cloud li {display: inline-block; margin-right: 10px;}
            .seo_cloud li .size1 {font-size: 10px;}
            .seo_cloud li .size2 {font-size: 12px;}
            .seo_cloud li .size3 {font-size: 14px;}
            .seo_cloud li .size4 {font-size: 16px;}
            .seo_cloud li .size5 {font-size: 18px;}
            .seo_cloud li .size6 {font-size: 20px;}
            .seo_cloud li .size7 {font-size: 22px;}
        </style>
    </head>
    <body>        
        <?php include 'masterpage/header.php'; ?>

        <div class="container" style="margin-top: 50px;">
            <section id="lista">
                <div class="page-header">
                    <h1><?php echo __('table_noticia'); ?> <small>Detalhes</small></h1>
                </div>

                <ul class="jquerydetalhe">
                    <li>
                        <h3><?php echo __('noticia.cod'); ?> </h3>
                        <p><?php echo $obj->getCod(); ?></p>
                    </li>
                    <li>
                        <h3><?php echo __('noticia.titulo'); ?> </h3>
                        <p><?php echo $obj->getTitulo(); ?></p>
                    </li>
                    <li>
                        <h3><?php echo __('noticia.seo'); ?> </h3>
                        <p><?php echo $obj->getSeo(); ?></p>
                    </li>                    
                </ul>

                <?php echo $obj->objSeo()->getKeysCloud(true); ?>

                <div class="clearfix"></div>
            </section>

            <?php include 'masterpage/footer.php'; ?>
        </div>
    </body>
</html>