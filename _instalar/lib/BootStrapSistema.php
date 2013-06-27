<?php

$realFolder = "adm/"; //pasta que os arquivo efetivamente estarão instalados na aplicação
define('___AppRoot', obterDocumentRoot());

require_once dirname(__FILE__) . '/FncsSistema.php';
require_once dirname(__FILE__) . '/frontEnd.php';
require_once dirname(__FILE__) . '/arquivos.php';
require_once dirname(__FILE__) . '/fend.php';

function CarregarConexao() {
    $phpDataServer = ___MySqlDataServer;
    $phpDataDb = ___MysqlDataDb;
    $phpDataUser = ___MySqlDataUser;
    $phpDataPass = ___MySqlDataPass;

    $Conexao = new PDO("mysql:host=$phpDataServer; dbname=$phpDataDb", "$phpDataUser", "$phpDataPass");
    return $Conexao;
}

function dataExecSqlDireto($Conexao, $sql, $fetchAll = true) {
    try {

        $statement = $Conexao->prepare($sql);
        $statement->execute();
        if ($fetchAll)
            $dados = $statement->fetchAll(PDO::FETCH_ASSOC);
        else
            $dados = $statement->fetch(PDO::FETCH_ASSOC);

        return $dados;
    } catch (Exception $exc) {
        PsgExceptions($exc);
        return false;
    }
}

function obtemCampos($Conexao, $tabela) {
    $sql = "SHOW COLUMNS FROM `$tabela`";
    $result = dataExecSqlDireto($Conexao, $sql);

    return $result;
}

function obtemRelacoes($Conexao, $tabela, $meuDb) {
    $sql = "select * from information_schema.key_column_usage WHERE
               `key_column_usage`.`CONSTRAINT_SCHEMA` = '$meuDb' AND
               `key_column_usage`.`table_name` = '$tabela' AND 
               `referenced_table_name` is not null;";
    $result = dataExecSqlDireto($Conexao, $sql);

    foreach ($result as $key => $value) {
        $result[$key]["UpperCampo"] = ucfirst($value["REFERENCED_TABLE_NAME"]);
    }

    return $result;
}

function obtemRelacoesInversa($Conexao, $tabela, $meuDb) {
    $sql = "select * from information_schema.key_column_usage WHERE
                `key_column_usage`.`CONSTRAINT_SCHEMA` = '$meuDb' AND
                `key_column_usage`.`REFERENCED_TABLE_NAME` = '$tabela' AND 
                `referenced_table_name` is not null;";
    $result = dataExecSqlDireto($Conexao, $sql);

    foreach ($result as $key => $value) {
        $result[$key]["UpperCampo"] = ucfirst($value["REFERENCED_TABLE_NAME"]);
    }

    return $result;
}

function obterDocumentRoot() {
    $AppRoot = $_SERVER['DOCUMENT_ROOT'];
    if ($AppRoot[strlen($AppRoot) - 1] != "/")
        $AppRoot.= "/";

    return $AppRoot;
}

function verificaPasta($folder) {
    if (!file_exists($folder))
        return mkdir($folder);
}

function obtemRelacaoMachTable($Field, $relacoes, $tabela) {
    foreach ($relacoes as $relacao) {
        if ($relacao['REFERENCED_TABLE_NAME'] == $tabela && $relacao['COLUMN_NAME'] == $Field) {
            return true;
        }
    }

    return false;
}


function criarObtemFormFieldsObtemRelacao($campo, $relacoes) {
    $retorno = null;

    foreach ($relacoes as $value) {
        if ($value["COLUMN_NAME"] == $campo)
            $retorno = $value;
    }

    return $retorno;
}

function criarObtemFormFieldsIsRelacionado($campo, $relacoes) {
    $retorno = false;

    foreach ($relacoes as $value) {
        if ($value["COLUMN_NAME"] == $campo)
            $retorno = true;
    }

    return $retorno;
}

function criarObtemFormFieldsObtemCampo2($Conexao, $REFERENCED_TABLE_NAME, $campos = null) {
    if (!$campos) {
        $campos = obtemCampos($Conexao, $REFERENCED_TABLE_NAME);
    }

    foreach ($campos as $campo) {
        if (strpos($campo['Type'], "varchar") !== false)
            return $campo;
    }

    return $campos[0];
}