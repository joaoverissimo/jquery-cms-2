<?php

//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

if (!isset($_POST['prm'])) {
    die('<option>Formulário inválido</option>');
}

$prm = $_POST['prm'];
$prm = base64_decode($prm);
$arrArg = unserialize($prm);

$current = $arrArg['value'];
if (isset($_POST['current'])) {
    $current = $_POST['current'];
}

if (arquivos::existe("insercao-dinamica/{$arrArg['tabela']}.php")) {
    include "insercao-dinamica/{$arrArg['tabela']}.php";
    die();
}

echo autoform2::retornarSelectDbItens($Conexao, $arrArg['tabela'], $arrArg['campo1Val'], $arrArg['campo2Txt'], $current, $arrArg['where'], $arrArg['orderBy'], $arrArg['AdicionarValorEmBranco']);
