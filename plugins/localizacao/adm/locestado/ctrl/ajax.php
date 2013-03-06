<?php

require_once '../../../jquerycms/config.php';

$current = "";
if (isset($_GET['current'])) {
    $current = $_GET['current'];
}

if (isset($_GET['estado']) && $_GET['estado']) {
    $where = new dataFilter("loccidade.estado", $_GET['estado']);
    $orderBy = new dataOrder('loccidade.nome');
    $dados = dbLoccidade::ObjsList($Conexao, $where, $orderBy);
    if (!$dados) {
        echo "<option>Não existem registros disponíveis</option>";
        exit();
    } else {
        echo "<option>Selecione...</option>";
        foreach ($dados as $obj) {
            if ($obj->getCod() == $current) {
                echo "<option value='{$obj->getCod()}' selected>{$obj->getNome()}</option>";
            } else {
                echo "<option value='{$obj->getCod()}'>{$obj->getNome()}</option>";
            }
        }
        exit();
    }
}

if (isset($_GET['cidade']) && $_GET['cidade']) {
    $where = new dataFilter("locbairro.cidade", $_GET['cidade']);
    $orderBy = new dataOrder('locbairro.nome');
    $dados = dbLocbairro::ObjsList($Conexao, $where, $orderBy);
    if (!$dados) {
        echo "<option>Não existem registros disponíveis</option>";
        exit();
    } else {
        echo "<option>Selecione...</option>";
        foreach ($dados as $obj) {
            if ($obj->getCod() == $current) {
                echo "<option value='{$obj->getCod()}' selected>{$obj->getNome()}</option>";
            } else {
                echo "<option value='{$obj->getCod()}'>{$obj->getNome()}</option>";
            }
        }
        exit();
    }
}

echo "<option>Não existem registros disponíveis</option>";