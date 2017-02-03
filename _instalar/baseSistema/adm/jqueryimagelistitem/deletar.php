<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");
Fncs_ValidaQueryString("jqueryimagelist", "../jqueryimagelist/index.php");
$jqueryimagelist = $_GET['jqueryimagelist'];

if ($adm_tema != 'branco') {
    $cancelLink = "index.php?jqueryimagelist=$jqueryimagelist";
}

//CONEXÃO E VALORES
$registro = new objJqueryimagelistitem($Conexao, true);
$registro->loadByCod($_GET["cod"]);

//POST
if (count($_POST) > 0) {
    $deletar = issetpost('delete');

    if ($deletar) {
        try {
            $exec = $registro->Delete();

            if ($exec && $adm_tema != 'branco') {
                header("Location: index.php?jqueryimagelist=$jqueryimagelist");
            } else {
                $msg = fEnd_MsgString("O registro foi excluido." . fEnd_closeTheIFrameImDone('jqueryimagelistitem', $registro->getCod()), 'success');
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
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryimagelistitem'); ?> - Deletar</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryimagelistitem'); ?> <small>Deletar</small></h3>
                </div>

                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>