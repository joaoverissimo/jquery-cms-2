<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$registro = new objJqueryseopalavra($Conexao);



//POST
if (count($_POST) > 0) {
    try {
        $registro->setPalavra(issetpost('palavra'));
        $registro->setUrl(issetpost('url'));
        $registro->setCount(issetpostInteger('count'));
        
        
        $exec = $registro->Save();

        if ($exec && $adm_tema != 'branco') {
            header("Location: $cancelLink");
        } else {
            $msg = fEnd_MsgString("O registro foi inserido." . fEnd_closeTheIFrameImDone('jqueryseopalavra', $registro->getCod()), 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

    
//FORM
$form = new autoform2();
$form->start("cadastro","","POST");
    
$form->text(__('jqueryseopalavra.palavra'), 'palavra', $registro->getPalavra(), 1);
$form->text(__('jqueryseopalavra.url'), 'url', $registro->getUrl(), 1);
$form->text(__('jqueryseopalavra.count'), 'count', $registro->getCount(), 3);

    
$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryseopalavra');?> - Inserir</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
        
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryseopalavra');?> <small>Inserir</small></h3>
                </div>
                
                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>