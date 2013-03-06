<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$registro = new objJqueryimage($Conexao);

//POST
if (count($_POST) > 0) {
    
    try {
        $exec = dbJqueryimage::InserirByFileImagems($Conexao, 'valor');

        if ($exec && $adm_tema != 'branco') {
            header("Location: index.php");
        } else {
            $msg = fEnd_MsgString("O registro foi inserido.$fEnd_closeTheIFrameImDone", 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}


//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->fileImagems(__('jqueryimage.valor'), 'valor', $registro->getValor(), 1);


$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryimage'); ?> - Inserir</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryimage'); ?> <small>Inserir</small></h3>
                </div>

                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>