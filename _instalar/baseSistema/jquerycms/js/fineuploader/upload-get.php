<?php

require_once '../../config.php';

if (!isset($_POST['jqueryimagelist'])) {
    echo "<li>Nao existem imagens</li>";
    exit();
}

$ctrlname = "";
if (isset($_GET['ctrlname'])) {
    $ctrlname = $_GET['ctrlname'];
}

$exibelink = (isset($_GET['exibelink'])) ? $_GET['exibelink'] : 0;
$exibedescricao = (isset($_GET['exibedescricao'])) ? $_GET['exibedescricao'] : 0;
$exibetarget = (isset($_GET['exibetarget'])) ? $_GET['exibetarget'] : 0;

$exibedeletar = (isset($_GET['exibedeletar'])) ? $_GET['exibedeletar'] : 0;
$exibeeditar = (isset($_GET['exibeeditar'])) ? $_GET['exibeeditar'] : 0;
$exibesetdefault = (isset($_GET['exibesetdefault'])) ? $_GET['exibesetdefault'] : 0;


$lblimage = (isset($_GET['lblimage'])) ? $_GET['lblimage'] : 0;
$lbltitulo = (isset($_GET['lbltitulo'])) ? $_GET['lbltitulo'] : 0;
$lbldescricao = (isset($_GET['lbldescricao'])) ? $_GET['lbldescricao'] : 0;
$lbllink = (isset($_GET['lbllink'])) ? $_GET['lbllink'] : 0;
$lbltarget = (isset($_GET['lbltarget'])) ? $_GET['lbltarget'] : 0;
//$lbl = (isset($_GET['lbl'])) ? $_GET['lbl'] : 0;
$link = CtrlJqueryImageList::getLinkAdmin($exibelink, $exibedescricao, $exibetarget, $exibedeletar, $exibeeditar, $exibesetdefault, $lblimage, $lbltitulo, $lbldescricao, $lbllink, $lbltarget);


$jqueryimagelist = dbJqueryimagelist::decode($_POST['jqueryimagelist']);

$where = new dataFilter(dbJqueryimagelistitem::_jqueryimagelist, $jqueryimagelist);
$orderBy = new dataOrder(dbJqueryimagelistitem::_ordem);
$dados = dbJqueryimagelistitem::ObjsList($Conexao, $where, $orderBy);

if ($dados === false) {
    echo "<li class='noimage'>Nao existem imagens.</li>";
    exit();
}

foreach ($dados as $obj) {
    $cod = $obj->getCod();
    $jqueryimagelist = dbJqueryimagelist::encode($obj->getJqueryimagelist());
    $jqueryimagelistitem = dbJqueryimagelist::encode($cod);

    $s = "
            <li id='listItem_{$cod}'>
                <p><img src='{$obj->objJqueryimage()->getSrc()}' alt='{$obj->getTitulo()}' class='img-polaroid'/></p>
                <span>{$obj->getTitulo()}</span>
                <div> ";
    if ($exibedeletar)
        $s .= "<a href='/jquerycms/js/fineuploader/upload-delete.php?jqueryimagelist=$jqueryimagelist&jqueryimagelistitem=$jqueryimagelistitem&ctrlname=$ctrlname' class='btn btn-mini {$ctrlname}_deletar' jqwidth='100' jqheight='80' data-toggle='tooltip' data-placement='top' data-original-title='Deletar'><i class='icon-trash'></i></a>";
    if ($exibesetdefault && $obj->getPrincipal() == 1) {
        $s.="<a href='/jquerycms/js/fineuploader/upload-setdefault.php?jqueryimagelist=$jqueryimagelist&jqueryimagelistitem=$jqueryimagelistitem&ctrlname=$ctrlname' class='btn btn-mini disabled {$ctrlname}_editar' jqwidth='100' jqheight='80' disabled data-toggle='tooltip' data-placement='top' data-original-title='Esta imagem é a miniatura principal desta lista'><i class='icon-star'></i></a>";
    } elseif ($exibesetdefault) {
        $s.="<a href='/jquerycms/js/fineuploader/upload-setdefault.php?jqueryimagelist=$jqueryimagelist&jqueryimagelistitem=$jqueryimagelistitem&ctrlname=$ctrlname' class='btn btn-mini {$ctrlname}_editar' jqwidth='100' jqheight='80' data-toggle='tooltip' data-placement='top' data-original-title='Definir como miniatura'><i class='icon-star'></i></a>";
    }

    if ($exibeeditar)
        $s .= "<a href='/jquerycms/js/fineuploader/upload-editar.php?jqueryimagelist=$jqueryimagelist&jqueryimagelistitem=$jqueryimagelistitem&ctrlname=$ctrlname&$link' class='btn btn-mini {$ctrlname}_editar' jqwidth='600' jqheight='450' data-toggle='tooltip' data-placement='top' data-original-title='Editar'><i class='icon-pencil'></i></a>";
    
    $s .= "</div>
            </li>
          ";
    echo $s;
}