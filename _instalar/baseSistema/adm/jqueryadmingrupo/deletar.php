<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");

//CONEXÃO E VALORES
$registro = new objJqueryadmingrupo($Conexao, true);
$registro->loadByCod($_GET["cod"]);

//POST
if (count($_POST) > 0) {
    $deletar = issetpost('delete');

    if ($deletar) {
        try {
            $exec = $registro->Delete();

            if ($exec && $adm_tema != 'branco') {
                header("Location: $cancelLink");
            } else {
                $msg = fEnd_MsgString("O registro foi excluido." . fEnd_closeTheIFrameImDone('jqueryadmingrupo', $registro->getCod()), 'success');
            }
        } catch (jquerycmsException $exc) {
            $msg = fEnd_MsgString("Ocorreram problemas ao deletar o registro.", 'error', $exc->getMessage());
        }
    }
}

//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->insertHtml("<p>Esta ação irá deletar o registro #{$registro->getCod()}. Você tem certeza que deseja fazer isso?</p>");
$form->hidden("delete", "true");

$form->send_cancel("Sim, deletar", $cancelLink, array('class' => 'btn-danger'));
$form->end();

$pageVars = array('pageTitle' => __('table_jqueryadmingrupo'), 'pageAction' => "Deletar", "nav-breadcrumbs" => array(__('table_jqueryadmingrupo') => "index.php"));
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
                            <section class="panel main-panel-pg">
                                <?php include '../lib/masterpage/panel-header.php'; ?>

                                <div class="panel-body">
                                    <?php echo $msg; ?>
                                    <?php echo $form->getForm(); ?>
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