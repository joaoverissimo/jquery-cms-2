<?php
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$pageVars = array('pageTitle' => "Permissões inválidas", 'pageAction' => "Listar", "nav-breadcrumbs" => array());
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

                            <section class="panel panel-horizontal">
                                <header class="panel-heading bg-white">
                                    <div class="panel-heading-icon bg-primary mt-sm">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                </header>
                                <div class="panel-body p-lg">
                                    <h3 class="text-weight-semibold mt-sm">Permissões inválidas</h3>
                                    <p>Atualmente seu grupo não permite acesso a este recurso do sistema.</p>
                                </div>
                            </section>

                            <section class="panel page-search-form">
                                <header class="panel-heading">
                                    <div class="panel-actions">
                                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                                        <!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
                                    </div>

                                    <h2 class="panel-title">Permissões inválidas</h2>
                                </header>

                                <div class="panel-body">
                                    <p>Prezado <b><?php echo $currentUser->getNome(); ?></b>, seu grupo é <b><?php echo $currentUser->objGrupo()->getTitulo(); ?></b>.</p>
                                    <p>A página <?php echo isset($_GET['url']) && $_GET['url'] ? "<a href='{$_GET['url']}'>/" . str_replace(___siteUrl, "", $_GET['url']) . "</a>" : ''; ?> solicitada foi não adicionada as permissões de seu grupo.</p>
                                    <p>Solicite ao administrador para conceder-lhe as pemissões de acesso, ou, faça <a href="login.php">login</a> com outra conta.</p>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>
            </div>

            <?php include '../lib/masterpage/footer.php'; ?>
        </section>
    </body>
</html>