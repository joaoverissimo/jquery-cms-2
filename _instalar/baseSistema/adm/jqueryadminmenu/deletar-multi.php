<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";

//CONEXÃO E VALORES
//POST
if (count($_POST) > 0) {
    if (issetArray($_POST['multi'])) {
        foreach ($_POST['multi'] as $cod) {
            try {
                $registro = new objJqueryadminmenu($Conexao, true);
                $registro->loadByCod($cod);
                $registro->Delete();
                $msg .= fEnd_MsgString("O registro #$cod foi excluido com sucesso." . fEnd_closeTheIFrameImDone('jqueryadminmenu', $registro->getCod()), 'success');
            } catch (jquerycmsException $exc) {
                $msg .= fEnd_MsgString("Ocorreram problemas ao deletar o registro #$cod.", 'warning', $exc->getMessage());
            }
        }
    }
}

$pageVars = array('pageTitle' => __('table_jqueryadminmenu'), 'pageAction' => "Deletar Selecionados", "nav-breadcrumbs" => array(__('table_jqueryadminmenu') => "index.php"));
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
                            <section class="panel main-panel-pg">
                                <?php include '../lib/masterpage/panel-header.php'; ?>

                                <div class="panel-body">
                                    <div>
                                        <?php
                                        if ($msg) {
                                            echo $msg;
                                        } else {
                                            echo "Nenhum registro selecionado";
                                        }
                                        ?>

                                        <a href='index.php' class='btn btn-default'>Voltar</a>
                                    </div>
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