<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");

//CONEXÃO E VALORES
$registro = new objBanner3i($Conexao, true);
$registro->loadByCod($_GET["cod"]);



//POST
if (count($_POST) > 0) {
    try {
        $registro->setImagem(dbJqueryimage::UpdateByFileImagems($Conexao, 'imagem'));
        $registro->setTitulopt(issetpost('titulopt'));
        $registro->setTituloes(issetpost('tituloes'));
        $registro->setTituloen(issetpost('tituloen'));
        $registro->setDescricaopt(issetpost('descricaopt'));
        $registro->setDescricaoes(issetpost('descricaoes'));
        $registro->setDescricaoen(issetpost('descricaoen'));
        $registro->setLink(issetpost('link'));
        $registro->setTarget(issetpost('target'));
        $registro->setExibir(issetpostInteger('exibir'));        
        
        $exec = $registro->Save();

        if ($exec && $adm_tema != 'branco') {
            header("Location: $cancelLink");
        } else {
            $msg = fEnd_MsgString("O registro foi salvo.$fEnd_closeTheIFrameImDone", 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

$tabs = new autoform2tabs('nav_tabs', '');
$tabs->addTab("Português");
$tabs->addTab("Espanhol");
$tabs->addTab("Inglês");

//FORM
$form = new autoform2();
$form->start("cadastro","","POST");
    

$form->fieldset("Conteúdo");
$form->insertHtml($tabs->start());
$form->text(__('banner3i.titulopt'), 'titulopt', $registro->getTitulopt(), 0);
$form->text(__('banner3i.descricaopt'), 'descricaopt', $registro->getDescricaopt(), 0);

$form->insertHtml($tabs->tab());
$form->text(__('banner3i.tituloes'), 'tituloes', $registro->getTituloes(), 0);
$form->text(__('banner3i.descricaoes'), 'descricaoes', $registro->getDescricaoes(), 0);

$form->insertHtml($tabs->tab());
$form->text(__('banner3i.tituloen'), 'tituloen', $registro->getTituloen(), 0);
$form->text(__('banner3i.descricaoen'), 'descricaoen', $registro->getDescricaoen(), 0);
$form->insertHtml($tabs->end());
$form->fieldsetOut();

$form->fieldset("Dados");
$form->text(__('banner3i.link'), 'link', $registro->getLink(), 7);
$form->fileImagems(__('banner3i.imagem'), 'imagem', $registro->getImagem(), 0);
$form->select(__('banner3i.target'), 'target', $registro->getTarget(), 1, "_self,_blank", "Na mesma janela,Em uma nova janela");
$form->checkbox(__('banner3i.exibir'), 'exibir', $registro->getExibir(), 0);
$form->fieldsetOut();
    
$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_banner3i');?> - Editar</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
        
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_banner3i');?> <small>Editar</small></h3>
                </div>
                
                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>