<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");

//CONEXÃO E VALORES
$registro = new objJqueryadmingrupo2menu($Conexao, true);
$registro->loadByCod($_GET["cod"]);
    
//POST
if (count($_POST) > 0) {
    $registro->setJqueryadmingrupo(issetpostInteger('jqueryadmingrupo'));
    $registro->setJqueryadminmenu(issetpostInteger('jqueryadminmenu'));
    
    try {
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
    
$form->selectDb(__('jqueryadmingrupo2menu.jqueryadmingrupo'), 'jqueryadmingrupo', $registro->getJqueryadmingrupo(), '', $Conexao, 'jqueryadmingrupo', 'cod', 'titulo');
$form->insertHtml(dbJqueryadminmenu::getAutoFormField(__('jqueryadmingrupo2menu.jqueryadminmenu'), 'jqueryadminmenu', $registro->getJqueryadminmenu(), '0', $Conexao));

    
$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryadmingrupo2menu');?> - Editar</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryadmingrupo2menu');?> <small>Editar</small></h3>
                </div>
                
                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>