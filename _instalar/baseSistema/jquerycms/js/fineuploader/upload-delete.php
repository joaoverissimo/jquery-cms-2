<?php

require_once '../../config.php';

$ctrlname = "";
if (isset($_GET['ctrlname'])) {
    $ctrlname = $_GET['ctrlname'];
}

if (!isset($_GET['jqueryimagelist']) || !isset($_GET['jqueryimagelistitem'])) {
    echo "Não foi possível encontrar esta imagem. <br />";
    echo "<script>window.parent.{$ctrlname}_resizeModalJquery(290, 100);</script>";
    echo "<button onclick='window.parent.{$ctrlname}_closeModalJquery();'>Fechar</button>";
    exit();
}

$jqueryimagelist = dbJqueryimagelist::decode($_GET['jqueryimagelist']);
$jqueryimagelistitem = dbJqueryimagelist::decode($_GET['jqueryimagelistitem']);

try {
    $obj = new objJqueryimagelistitem($Conexao);
    $obj->loadByCod($jqueryimagelistitem);
} catch (Exception $exc) {
    echo "Não foi possível encontrar o registro.";
    echo "<script>window.parent.{$ctrlname}_resizeModalJquery(290, 100);</script>";
    echo "<button onclick='window.parent.{$ctrlname}_closeModalJquery();'>Fechar</button>";
    exit();
}


if ($obj->getJqueryimagelist() == $jqueryimagelist) {
    if ($obj->Delete()) {
        echo "<img src='processing.gif' alt='' style='display: block; margin: 0 auto; margin-top: 25px;' />";
        echo "<script>setTimeout(function() {window.parent.{$ctrlname}_closeModalJquery();},150);</script>";
        exit();
    }
}

echo "Não foi possível realizar esta operação. <br />";
echo "<script>window.parent.{$ctrlname}_resizeModalJquery(290, 100);</script>";
echo "<button onclick='window.parent.{$ctrlname}_closeModalJquery();'>Fechar</button>";
