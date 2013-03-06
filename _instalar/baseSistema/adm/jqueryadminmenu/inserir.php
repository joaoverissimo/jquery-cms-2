<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$registro = new objJqueryadminmenu($Conexao);

//POST
if (count($_POST) > 0) {
    $registro->setCodmenu(issetpostInteger('codmenu'));
    $registro->setTitulo(issetpost('titulo'));
    $registro->setPatch(issetpost('patch'));
    $registro->setAddhtml(issetpost('addhtml'));
    $registro->setOrdem(issetpostInteger('ordem'));

    try {
        $registro->setIcon(dbJqueryimage::InserirByFileImagems($Conexao, 'icon'));
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
$form->start("cadastro", "", "POST");

$form->selectDb(__('jqueryadminmenu.codmenu'), 'codmenu', $registro->getCodmenu(), '', $Conexao, 'jqueryadminmenu', 'cod', 'titulo');
$form->text(__('jqueryadminmenu.titulo'), 'titulo', $registro->getTitulo(), 1);
$form->text(__('jqueryadminmenu.patch'), 'patch', $registro->getPatch(), 1);
$form->fileImagems(__('jqueryadminmenu.icon'), 'icon', $registro->getIcon(), 1);
$form->text(__('jqueryadminmenu.addhtml'), 'addhtml', $registro->getAddhtml(), 0, "Adiciona html ao item de menu");
$form->text(__('jqueryadminmenu.ordem'), 'ordem', $registro->getOrdem(), 3);


$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryadminmenu'); ?> - Inserir</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryadminmenu'); ?> <small>Inserir</small></h3>
                </div>

                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>