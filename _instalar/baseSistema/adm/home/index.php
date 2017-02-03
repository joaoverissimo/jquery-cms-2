<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$hoje = objDate::initPtBr(___DataAtual);

$pageVars = array('pageTitle' => "Home", 'pageAction' => "Listar", "nav-breadcrumbs" => array());
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
                    <?php include 'dados/migalhas.php'; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <section class="panel dash-panel-agenda-atrasadas dash-panel">
                                <header class="panel-heading">
                                    <div class="panel-actions">
                                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                    </div>
                                    <h2 class="panel-title"><i class="fa fa-star"></i> Dashboard</h2>
                                </header>
                                <div class="panel-body " style="display: block;">
                                    <div class="dash-item dash-item-interacao-agenda dash-item-interacao-agenda-atrasadas">
                                        Esperando algo...
                                    </div>
                                </div> 
                        </div>
                    </div>
                </section>
            </div>

            <?php include '../lib/masterpage/footer.php'; ?>
        </section>
    </body>
</html>