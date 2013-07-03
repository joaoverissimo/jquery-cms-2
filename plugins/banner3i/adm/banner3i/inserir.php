<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$registro = new objBanner3i($Conexao);

//POST
if (count($_POST) > 0) {
    try {
        $registro->setImagem(dbJqueryimage::InserirByFileImagems($Conexao, 'imagem'));
        $registro->setLink(issetpost('link'));
        $registro->setTarget(issetpost('target'));
        $registro->setTitulopt(issetpost('titulopt'));
        $registro->setDescricaopt(issetpost('descricaopt'));
        $registro->setExibir(1);
        $registro->setOrdem(999);
        
        $t = new Translator();
        $t->Config();        
        $registro->setTituloes($t->Translate($registro->getTitulopt(), "pt", "es"));
        $registro->setTituloen($t->Translate($registro->getTitulopt(), "pt", "en"));
        $registro->setDescricaoes($t->Translate($registro->getDescricaopt(), "pt", "es"));
        $registro->setDescricaoen($t->Translate($registro->getDescricaopt(), "pt", "en"));
        
        $exec = $registro->Save();

        if ($exec && $adm_tema != 'branco') {
            header("Location: $cancelLink");
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
    
$form->text(__('banner3i.titulopt'), 'titulopt', $registro->getTitulopt(), 1);
$form->text(__('banner3i.descricaopt'), 'descricaopt', $registro->getDescricaopt(), 0);
$form->text(__('banner3i.link'), 'link', $registro->getLink(), 7);
$form->fileImagems(__('banner3i.imagem'), 'imagem', $registro->getImagem(), 1);
$form->select(__('banner3i.target'), 'target', $registro->getTarget(), 1, "_self,_blank", "Na mesma janela,Em uma nova janela");
    
$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_banner3i');?> - Inserir</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
        
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_banner3i');?> <small>Inserir</small></h3>
                </div>
                
                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>