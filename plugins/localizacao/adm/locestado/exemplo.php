<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$destaque = "8452, 8700, 8589";



$msg = "";

$ctrl = new CtrlLocalizacao($Conexao, "joao", $adm_folder,$destaque);
$ctrl->estado = 24;
$ctrl->cidade = 8452;

$exibeEstado = false;
$exibeCidade = true;
$exibeBairro = true;
$exibeRua = true;
$exibeNumero = true;
$exibeComplemento = true;
$ctrl->PostRecebeValores();
    
//POST
if (count($_POST) > 0) {
    print_r($ctrl);
}


//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->text(__('jqueryadmingrupo.titulo'), 'titulo', "teste", 1);
$form->insertHtml($ctrl->getAutoFormField($exibeEstado, $exibeCidade, $exibeBairro, $exibeRua, $exibeNumero, $exibeComplemento));

$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title>Teste - Editar</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
        <?php echo $ctrl->getHead(); ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryadmingrupo'); ?> <small>Editar</small></h3>
                </div>

                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>