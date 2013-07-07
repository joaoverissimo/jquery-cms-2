<?php

require_once '../../../jquerycms/config.php';

$current = "";
if (isset($_REQUEST['current'])) {
    $current = $_REQUEST['current'];
}

if (isset($_REQUEST['cep']) && $_REQUEST['cep']) {
    $registro = new objLocendereco($Conexao, false);
    if ($registro->loadByCod($_REQUEST['cep'], "cep")) {
        $arr = array(
            "status" => 1,
            "estado" => $registro->getEstado(),
            "cidade" => $registro->getCidade(),
            "bairro" => $registro->getBairro(),
            "rua" => $registro->getNome(),
            "cep" => $registro->getCep()
        );
        echo json_encode($arr);
        exit();
    }
}

$arr = array ("status" => 0);
echo json_encode($arr);