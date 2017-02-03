<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../config.php';

$msg = "";
$adm_folder = '/adm/';
$adm_tema = 'branco';


$ctrlname = "";
if (isset($_GET['ctrlname'])) {
    $ctrlname = $_GET['ctrlname'];
}

if (!isset($_GET['jqueryimagelist'])) {
    echo "Não foi possível encontrar a lista de imagens. <br />";
    echo "<script>window.parent.{$ctrlname}_resizeModalJquery(290, 100);</script>";
    echo "<button onclick='window.parent.{$ctrlname}_closeModalJquery();'>Fechar</button>";
    exit();
}

$jqueryimagelist = dbJqueryimagelist::decode($_GET['jqueryimagelist']);

//CONEXÃO E VALORES
$registro = new objJqueryimagelistitem($Conexao, true);
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

$exibelink = false;
if (isset($_GET['exibelink'])) {
    $exibelink = $_GET['exibelink'];
}

$exibetarget = false;
if (isset($_GET['exibetarget'])) {
    $exibetarget = $_GET['exibetarget'];
}

$exibedescricao = false;
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

        if ($exec) {
          $msg = fEnd_MsgString("O registro foi salvo.<script>setTimeout(function() {window.parent.{$ctrlname}_closeModalJquery()}, 1150);</script>", 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->fileImagems($lblimage, 'jqueryimage', $registro->getJqueryimage(), 0, $Conexao);
$form->text($lbltitulo, 'titulo', $registro->getTitulo(), 0);
if ($exibedescricao)
    $form->text($lbldescricao, 'descricao', $registro->getDescricao(), 0);
if ($exibelink)
    $form->text($lbllink, 'link', $registro->getLink(), 7);
if ($exibetarget && $exibelink)
    $form->select($lbltarget, 'target', $registro->getTarget(), 0, '_self, _blank', 'Na mesma janela, Abrir em outra janela');

$form->send_cancel('Salvar', '#', array('btn2add' => "onclick='window.parent.{$ctrlname}_closeModalJquery();'"));
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include '../../../adm/lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
        <script>
            $(document).ready(function(){
                window.parent.<?php echo $ctrlname;?>_resizeModalJquery($("body").width() + 100 , $("body").height() + 100 );
            });
        </script>
    </head>
    <body>        
        <?php include '../../../adm/lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><small>Inserir</small></h3>
                </div>

                <?php echo $msg; ?>
                <?php echo $form->getForm(); ?>
            </div>
        </div>
        <?php include '../../../adm/lib/masterpage/footer.php'; ?>
    </body>
</html>