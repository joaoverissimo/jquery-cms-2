<?php

require_once '../../config.php';

if (!isset($_GET['jqueryimagelist'])) {
    echo json_encode(array("error" => true, "uploadName" => 'Lista de imagens desconhecida'));
}

$jqueryimagelist = dbJqueryimagelist::decode($_GET['jqueryimagelist']);

$registro = new objJqueryimagelistitem($Conexao);
$registro->setJqueryimagelist($jqueryimagelist);
$registro->setTitulo("");
$registro->setDescricao("");
$registro->setLink("");
$registro->setTarget("_self");
$registro->setOrdem(dbJqueryimagelistitem::getMax($Conexao, true));
$registro->setPrincipal(0);

try {
    $registro->setJqueryimage(dbJqueryimage::InserirByFileImagems($Conexao, 'valor'));


    if ($registro->Save()) {
        echo json_encode(array("success" => true, "uploadName" => ''));
    } else {
        echo json_encode(array("error" => true, "uploadName" => ''));
    }
} catch (Exception $exc) {
    echo json_encode(array("error" => true, "uploadName" => $exc->getMessage()));
}




