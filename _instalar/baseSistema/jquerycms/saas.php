<?php

$saas_versoes = array(
    "v01.00.01.000" => array(
        "v01.00.01.000_001 - instalar jquery cms"
    )
);

function saas_getRegex() {
    return "/(.*)\.(.*)(.info|.com.br|.net.br)/";
}

function saas_getHost($httpHost = false) {
    if (!$httpHost) {
        $httpHost = $_SERVER['HTTP_HOST'];
    }
    preg_match(saas_getRegex(), $httpHost, $output_array);

    if (isset($output_array) && is_array($output_array) && count($output_array) > 0) {
        return $output_array[2];
    }

    return false;
}

function saas_getCliente() {
    if (!isset($_SERVER['HTTP_HOST'])) {
        throw new Exception("É necessário que a variável _SERVER[HTTP_HOST] esteja definida");
    }

    $httpHost = $_SERVER['HTTP_HOST'];

    $hostFreeImob = saas_getHost(___siteUrlHome);
    $hostAtual = saas_getHost($httpHost);
    if ($hostFreeImob == $hostAtual || $hostAtual == "wsoft") {
        $httpHost = $_SERVER['HTTP_HOST'];
        preg_match(saas_getRegex(), $httpHost, $output_array);

        if (isset($output_array) && is_array($output_array) && count($output_array) > 0) {
            return $output_array[1];
        }
    }

    include 'saas-dominios.php';

    if (isset($saas_dominio[$httpHost])) {
        return $saas_dominio[$httpHost];
    }

    return false;
}

function saas_getUrlCanonical() {
    $httpHost = $_SERVER['HTTP_HOST'];
    return "http://$httpHost/";
}

function saas_syncDb($Conexao, $forcar = false) {
    global $saas_versoes;

    if (!isset($_SESSION['syncdb']) || $forcar) {

        ini_set("memory_limit", "160M");
        ini_set('max_execution_time', 30000);

        dataExecSqlDireto($Conexao, "CREATE TABLE IF NOT EXISTS zsqlversao (script varchar(255) NOT NULL, dataexec datetime NOT NULL, PRIMARY KEY (script)) ENGINE=InnoDB DEFAULT CHARSET=utf8;", false);

        //echo '<div style=" background-color: red; color: white; font-size: 40px; display: block; padding: 20px; ">Rodou sync</div>';

        foreach ($saas_versoes as $verao => $scripts) {
            if (issetArray($scripts)) {
                foreach ($scripts as $script) {
                    $data = date("Y-m-d H:i:s");

                    $dados = dataExecSqlDireto($Conexao, "SELECT 1 AS rt FROM zsqlversao where script = '$script' LIMIT 0,1;" . "\n", false);
                    if (!isset($dados['rt'])) {
                        $sql = arquivos::ler(___AppRoot . "zSql/$verao/$script.sql");

                        $statement = $Conexao->prepare($sql);
                        if (!$statement->execute()) {
                            $erro = $statement->errorInfo();
                            die("Problemas com a instalação de seu sistema. <br />Ocorreu erro com a sincronização do script '{$script}'. <br />Por gentiliza contate o suporte. Erro:<br /> {$erro [2]}");
                        } else {
                            $statement->closeCursor();
                            dataExecSqlDireto($Conexao, "INSERT INTO zsqlversao (script, dataexec) VALUE ('$script', '$data');", false);
                        }
                    }
                }
            }
        }

        $_SESSION['syncdb'] = $script;
    }
}

function saas_getVersao($Conexao) {
    $dados = dataExecSqlDireto($Conexao, "SELECT max(versao) as rt FROM zchangelog", false);
    if (isset($dados['rt']) && $dados['rt']) {
        return $dados['rt'];
    }

    return "v00.00.00.000";
}
