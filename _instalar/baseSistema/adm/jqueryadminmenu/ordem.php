<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$codmenu = "";

$form = new autoform2();
$form->start("cadastro", "", "GET");

if (isset($_GET['codmenu'])) {
    $codmenu = $_GET['codmenu'];
    //Lista os dados
    $where = new dataFilter(dbJqueryadminmenu::_codmenu, $codmenu);
    $where->add(dbJqueryadminmenu::_cod, 0, dataFilter::op_different);
    $dados = dbJqueryadminmenu::ObjsList($Conexao, $where, 'ordem asc');
} else {
    $dados = false;

    $form->insertHtml(dbJqueryadminmenu::getAutoFormField("Menu para ordenar", "codmenu", "", 1, $Conexao));
    $form->send_cancel("Avançar", "index.php", "Cancelar");
    $form->end();
}

$pageVars = array('pageTitle' => __('table_jqueryadminmenu'), 'pageAction' => "Ordem", "nav-breadcrumbs" => array(__('table_jqueryadminmenu') => "index.php"));
?><!doctype html>
<html class="fixed sidebar-left-xs">
    <head>
        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>

        <script>
            $(document).ready(function () {
                $('.ordem').sortable({
                    update: function () {
                        order = [];
                        $('.ordem').children('li').each(function (idx, elm) {
                            order.push(elm.id.split('_')[1]);
                        });

                        $('#status').load('ordem-salvar.php', {'order': order});
                        $('#status').html('Aguarde...');
                    }
                });

                $('.ordem').disableSelection();
            });
        </script>
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
                            <section class="panel main-panel-pg">
                                <?php include '../lib/masterpage/panel-header.php'; ?>

                                <div class="panel-body">
                                    <?php echo $msg; ?>

                                    <div id="status"></div>

                                    <?php if ($codmenu !== "") : ?>
                                        <?php if (issetArray($dados)) : ?>
                                            <h3><?php echo "Ordenar " . $dados[0]->getTitulo(); ?></h3>

                                            <ul class="ordem">
                                                <?php foreach ($dados as $obj) : ?>

                                                    <li class="navbar-inner" id="listItem_<?php echo $obj->getCod(); ?>">
                                                        <span class="titulo">
                                                            <i class="fa fa-arrows"></i>
                                                            <?php echo $obj->getTitulo(); ?>
                                                        </span>
                                                    </li>

                                                <?php endforeach ?>
                                            </ul>
                                        <?php else : ?>
                                            Este menu não possui itens filhos que possam ser ordenados.
                                        <?php endif; ?>

                                        <div class="btn-toolbar">
                                            <a href="ordem.php" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i> Voltar</a>
                                        </div>
                                    <?php else : ?>
                                        <?php echo $form->getForm(); ?>
                                    <?php endif; ?>
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