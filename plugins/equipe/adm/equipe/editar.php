<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");

//CONEXÃO E VALORES
$registro = new objEquipe($Conexao, true);
$registro->loadByCod($_GET["cod"]);
    
//POST
if (count($_POST) > 0) {
    $registro->setNome(issetpost('nome'));
    $registro->setDescricao(issetpost('descricao'));
    $registro->setEmail(issetpost('email'));
    $registro->setImagem(dbJqueryimage::UpdateByFileImagems($Conexao, 'imagem'));
    //$registro->setOrdem(issetpostInteger('ordem'));
    
    try {
        $exec = $registro->Save();

        if ($exec && $adm_tema != 'branco') {
            header("Location: index.php");
        } else {
            $msg = fEnd_MsgString("O registro foi salvo.$fEnd_closeTheIFrameImDone", 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

//FORM
$form = new autoform2();
$form->start("cadastro","","POST");
    
$form->text(__('equipe.nome'), 'nome', $registro->getNome(), 1);
$form->textarea(__('equipe.descricao'), 'descricao', $registro->getDescricao(), 1);
$form->text(__('equipe.email'), 'email', $registro->getEmail(), 1);
$form->fileImagems(__('equipe.imagem'), 'imagem', $registro->getImagem(), 0);

    
$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_equipe');?> - Editar</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_equipe');?> <small>Editar</small></h3>
                </div>
                
                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>