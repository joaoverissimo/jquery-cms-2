<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";

$template = array(
    "htmlstart" => "<ul class='#ulclass#'>\n",
    "htmlend" => "</ul>",
    "li" => "\n\t<li><span>getTitulo</span> <a href='editar.php?cod=getCod'>__(editar)</a> <a href='deletar.php?cod=getCod'>[x]</a></li>",
    "lisub" => "\n\t<li><span>getTitulo</span> <a href='editar.php?cod=getCod'>__(editar)</a> <a href='deletar.php?cod=getCod'>[x]</a>#getmenu#</li>",
    "nivelclass" => array(
        0 => "treecheckbox",
        1 => ""
    )
);

$pageVars = array('pageTitle' => __('table_jqueryadminmenu'), 'pageAction' => "Resultado da Consulta", "nav-breadcrumbs" => array());
?><!doctype html>
<html class="fixed sidebar-left-xs">
    <head>
        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>
        <section class="body">
            <?php include '../lib/masterpage/header.php'; ?>

            <div class="inner-wrapper">
                <?php include '../lib/masterpage/navbar.php'; ?>

                <section role="main" class="content-body">
                    <?php include '../lib/masterpage/page-header.php'; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <?php include "form/page-btn-toolbar-default.php"; ?>

                            <section class="panel">
                                <?php include '../lib/masterpage/panel-header.php'; ?>
                                <?php include './form/panel-body.php'; ?>
                            </section>
                        </div>
                    </div>
                </section>
            </div>

            <?php include '../lib/masterpage/footer.php'; ?>
        </section>
    </body>
</html>