<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");

//CONEXÃO E VALORES
$registro = new objJqueryadminmenu($Conexao, true);
$registro->loadByCod($_GET["cod"]);

//POST
if (count($_POST) > 0) {
    $registro->setCodmenu(issetpostInteger('codmenu'));
    $registro->setTitulo(issetpost('titulo'));
    $registro->setPatch(issetpost('patch'));
    $registro->setAddhtml(issetpost('addhtml'));

    try {
        $registro->setIcon(dbJqueryimage::UpdateByFileImagems($Conexao, 'icon'));
        $exec = $registro->Save();

        if ($exec && $adm_tema != 'branco') {
            header("Location: index.php");
        } else {
            $msg = fEnd_MsgString("O registro foi inserido." . fEnd_closeTheIFrameImDone('jqueryadminmenu', $registro->getCod()), 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->insertHtml(dbJqueryadminmenu::getAutoFormField("Menu para ordenar", "codmenu", "", 1, $Conexao));
$form->text(__('jqueryadminmenu.titulo'), 'titulo', $registro->getTitulo(), 1);
$form->text(__('jqueryadminmenu.patch'), 'patch', $registro->getPatch(), 1);
$form->fileImagems(__('jqueryadminmenu.icon'), 'icon', $registro->getIcon(), 0);
$form->text(__('jqueryadminmenu.addhtml'), 'addhtml', $registro->getAddhtml(), 0);


$form->send_cancel("Salvar", $cancelLink);
$form->end();

$pageVars = array('pageTitle' => __('table_jqueryadminmenu'), 'pageAction' => "Editar", "nav-breadcrumbs" => array(__('table_jqueryadminmenu') => "index.php"));
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