<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

Fncs_ValidaQueryString("jqueryimagelist", "../jqueryimagelist/index.php");
$jqueryimagelist = $_GET['jqueryimagelist'];

if ($adm_tema != 'branco') {
    $cancelLink = "index.php?jqueryimagelist=$jqueryimagelist";
}

//CONEXÃO E VALORES
$registro = new objJqueryimagelistitem($Conexao);
$registro->setJqueryimagelist($jqueryimagelist);
$registro->setTitulo("");
$registro->setDescricao("");
$registro->setLink("");
$registro->setTarget("_self");
$registro->setOrdem(dbJqueryimagelistitem::getMax($Conexao, true));
$registro->setPrincipal(0);


//Set Labels
$lblimage = 'Imagem';
if (isset($_GET['lblimage'])) {
    $lblimage = $_GET['lblimage'];
}

$lbltitulo = __('jqueryimagelistitem.titulo');
if (isset($_GET['lbltitulo'])) {
    $lbltitulo = $_GET['lbltitulo'];
}

$lbllink = __('jqueryimagelistitem.link');
if (isset($_GET['lbllink'])) {
    $lbllink = $_GET['lbllink'];
}

$lbltarget = __('jqueryimagelistitem.target');
if (isset($_GET['lbltarget'])) {
    $lbltarget = $_GET['lbltarget'];
}

$lbldescricao = __('jqueryimagelistitem.descricao');
if (isset($_GET['lbldescricao'])) {
    $lbldescricao = $_GET['lbldescricao'];
}

$exibelink = true;
if (isset($_GET['exibelink'])) {
    $exibelink = $_GET['exibelink'];
}

$exibetarget = true;
if (isset($_GET['exibetarget'])) {
    $exibetarget = $_GET['exibetarget'];
}

$exibedescricao = true;
if (isset($_GET['exibedescricao'])) {
    $exibedescricao = $_GET['exibedescricao'];
}

//POST
if (count($_POST) > 0) {
    $registro->setTitulo(issetpost('titulo'));

    if ($exibelink)
        $registro->setLink(issetpost('link'));

    if ($exibetarget && $exibelink)
        $registro->setTarget(issetpost('target'));

    if ($exibedescricao)
        $registro->setDescricao(issetpost('descricao'));

    try {
        $registro->setJqueryimage(dbJqueryimage::InserirByFileImagems($Conexao, 'jqueryimage'));

        $exec = $registro->Save();

        if ($exec && $adm_tema != 'branco') {
            header("Location: index.php?jqueryimagelist=$jqueryimagelist");
        } else {
            $msg = fEnd_MsgString("O registro foi inserido." . fEnd_closeTheIFrameImDone('jqueryimagelistitem', $registro->getCod()), 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}


//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->fileImagems($lblimage, 'jqueryimage', $registro->getJqueryimage(), 1, $Conexao);
$form->text($lbltitulo, 'titulo', $registro->getTitulo(), 0);

if ($exibedescricao)
    $form->text($lbldescricao, 'descricao', $registro->getDescricao(), 0);
if ($exibelink)
    $form->text($lbllink, 'link', $registro->getLink(), 7);
if ($exibetarget && $exibelink)
    $form->select($lbltarget, 'target', $registro->getTarget(), 0, '_self, _blank', 'Na mesma janela, Abrir em outra janela');

$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryimagelistitem'); ?> - Inserir</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><small>Inserir</small></h3>
                </div>

                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>