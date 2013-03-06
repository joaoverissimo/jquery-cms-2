<?php

ini_set('max_execution_time', '6000');

//CONEXAO E PARAMETROS
require_once '_config.php';
require_once 'lib/BootStrapSistema.php';
require_once "lib/zip/dUnzip2.inc.php";

$Conexao = CarregarConexao();

$sql = "SHOW TABLES FROM $meuDb";
$dados = dataExecSqlDireto($Conexao, $sql);

include "_instalar_html/header.html";

echo "<h1><a href='JqueryCms-Step-4.php?execAll=1'>Criar todos</a></h1>";

foreach ($dados as $value) {
    $tabela = $value["Tables_in_" . $meuDb];
    echo "<h3><a href='index.php?tabela=$tabela&meudb=$meuDb'>Criar/ Re-Criar $tabela</a></h3>";
}


if (isset($_REQUEST['execAll'])) {
    echo "<div class='execAll'>";
    $temlate = obterDocumentRoot() . "_instalar/templates/";

    //CRIA O DIRETORIO root/lib/dataClass        
    verificaAllPastas($outputFolder);


    include 'criarExecAll.php';
    criarExecAll($Conexao, $temlate, $dados, $outputFolder, $meuDb, $httpCreator);

    echo "</div><a href='JqueryCms-Step-5.php' class='btn avancar'>Avançar</a>";
}

include "_instalar_html/footer.html";