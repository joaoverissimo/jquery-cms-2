<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

//Filtra a lista
$filtraList = "";
if (isset($_GET['filtraList']) && $_GET['filtraList']) {
    $filtraList = $_GET['filtraList'];
}

//Lista os dados
$where = new dataFilter('jqueryadmingrupo.cod', 1, dataFilter::op_different);
if ($filtraList) {
    $where->dataFilter('meuteste.titulo', $filtraList, dataFilter::op_likeMatches);
}

$orderBy = new dataOrder('jqueryadmingrupo.cod', 'desc');

$paginaAtual = 0;
if (isset($_REQUEST['page']))
    $paginaAtual = $_REQUEST['page'];

$pager = new dataPager($paginaAtual, 15, $Conexao, 'jqueryadmingrupo', $where);

$dados = dbJqueryadmingrupo::ObjsListLeft($Conexao, $where, $orderBy, $pager->getLimit());

$form = new autoform2();
$form->start("cadastro", "", "GET", array('class' => 'form-inline '));
$form->text("Busca rapida", "filtraList", $filtraList);
$form->send_cancel("<i class='fa fa-filter'></i> Filtrar", "index.php", array('btn2class' => 'btn-xs btn-default'), "Limpar Filtros");
$form->end();

$pageVars = array('pageTitle' => __('table_jqueryadmingrupo'), 'pageAction' => "Resultado da Consulta", "nav-breadcrumbs" => array());
?><!doctype html>
<html class="fixed sidebar-left-xs">
    <head>
        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
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
                            <?php include "../lib/masterpage/page-search-form.php"; ?>

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