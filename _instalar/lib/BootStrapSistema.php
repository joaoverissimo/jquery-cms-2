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