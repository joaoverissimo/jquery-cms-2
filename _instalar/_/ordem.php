<?php

ini_set('max_execution_time', '6000');
ini_set('default_charset', 'UTF-8');

require_once '../_config.php';
require_once '../lib/BootStrapSistema.php';
$Conexao = CarregarConexao();

//Obtem a tabela
if (!isset($_GET['tabela'])) {
    $sql = "SHOW TABLES FROM $meuDb";
    $dados = dataExecSqlDireto($Conexao, $sql);
    echo "<h1>Selecione a tabela a ser ordenada</h1>";
    foreach ($dados as $value) {
        echo "<a href='ordem.php?tabela={$value['Tables_in_simbras']}'>{$value['Tables_in_simbras']}</a> <br>";
    }
    exit();
}

//Define as variaveis
$tabela = $_GET['tabela'];
$tabelaU = ucfirst($tabela);
$campos = obtemCampos($Conexao, $tabela);
$relacoes = obtemRelacoes($Conexao, $tabela, $meuDb);
$primaryKey = $campos[0]['Field'];
$primaryKeyU = ucfirst($primaryKey);
$campo2arr = criarObtemFormFieldsObtemCampo2($Conexao, $tabela, $campos);
$campo2 = $campo2arr['Field'];
$campo2U = ucfirst($campo2);

ob_start();
include 'ordem/ordem.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>ordem.php</div>";

ob_start();
include 'ordem/ordem-salvar.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>ordem-salvar.php</div>";

ob_start();
include 'ordem/index.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>index.php</div>";
