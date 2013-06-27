<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");

//CONEXÃO E VALORES
$registro = new objImovel($Conexao, true);
$registro->loadByCod($_GET["cod"]);
    
//////////////////////////////////////////////////////
/////////// INICIA O CONTROLE PELO CÓDIGO ////////////
//////////////////////////////////////////////////////
$ctrl = new CtrlMapaPontoView($Conexao, "mapa");
$ctrl->loadByCod($registro->getLocmapaponto());
//////////////////////////////////////////////////////

//POST
if (count($_POST) > 0) {
    $registro->setTitulo(issetpost('titulo'));
    
    try {
        //////////////////////////////////////////////////////
        /////////// SALVA PELO POST E DEFINE O CODIGO ////////
        //////////////////////////////////////////////////////
        $ctrl->SaveByPost();
        $registro->setLocmapaponto($ctrl->codPontoMapa);
        //////////////////////////////////////////////////////
        
        $exec = $registro->Save();

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
$form->start("cadastro","","POST");
    
$form->text(__('imovel.titulo'), 'titulo', $registro->getTitulo(), 1);

//////////////////////////////////////////////////////
/////////// INSERE O CONTROLE AO FORM ////////////////
//////////////////////////////////////////////////////
$form->insertHtml($ctrl->getAutoFormField());
//////////////////////////////////////////////////////
    
$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_imovel');?> - Editar</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
        
        <?php 
            //////////////////////////////////////////////////////
            /////////// AICIONA OS JS DO CONTROLE AO HEAD ////////
            //////////////////////////////////////////////////////
            echo $ctrl->getHead(); 
            //////////////////////////////////////////////////////
        ?>
        
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_imovel');?> <small>Editar</small></h3>
                </div>
                
                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>