<?php

ini_set('max_execution_time', '6000');
ini_set('default_charset', 'UTF-8');

require_once '../../_config.php';
require_once '../../lib/BootStrapSistema.php';
$Conexao = CarregarConexao();

//Obtem a tabela master
if (!isset($_GET['master'])) {
    $sql = "SHOW TABLES FROM $meuDb";
    $dados = dataExecSqlDireto($Conexao, $sql);
    echo "<h1>Selecione a tabela master</h1>";
    foreach ($dados as $value) {
        echo "<a href='index.php?master={$value['Tables_in_simbras']}'>{$value['Tables_in_simbras']}</a> <br>";
    }
    exit();
}

//Define as variaveis
$master = $_GET['master'];
$masterU = ucfirst($master);
$master_campos = obtemCampos($Conexao, $master);
$master_relacoes = obtemRelacoes($Conexao, $master, $meuDb);
$master_primaryKey = $master_campos[0]['Field'];
$master_primaryKeyU = ucfirst($master_primaryKey);
$master_campo2arr = criarObtemFormFieldsObtemCampo2($Conexao, $master, $master_campos);
$master_campo2 = $master_campo2arr['Field'];
$master_campo2U = ucfirst($master_campo2);

//Obtem a tabela detalhe
if (!isset($_GET['detalhe'])) {
    $sql = "SHOW TABLES FROM $meuDb";
    $dados = dataExecSqlDireto($Conexao, $sql);
    echo "<h1>Selecione a tabela detalhe</h1>";
    foreach ($dados as $value) {
        echo "<a href='index.php?master=$master&detalhe={$value['Tables_in_simbras']}'>{$value['Tables_in_simbras']}</a> <br>";
    }
    exit();
}

//Define as variaveis
$detalhe = $_GET['detalhe'];
$detalheU = ucfirst($detalhe);
$detalhe_campos = obtemCampos($Conexao, $detalhe);
$detalhe_relacoes = obtemRelacoes($Conexao, $detalhe, $meuDb);
$detalhe_primaryKey = $detalhe_campos[0]['Field'];
$detalhe_primaryKeyU = ucfirst($detalhe_primaryKey);
$detalhe_campo2arr = criarObtemFormFieldsObtemCampo2($Conexao, $detalhe, $detalhe_campos);
$detalhe_campo2 = $detalhe_campo2arr['Field'];
$detalhe_campo2U = ucfirst($detalhe_campo2);

//Obtem o campo de relacao entre detalhe e master
if (!isset($_GET['relacao'])) {
    $sql = "SHOW FIELDS FROM $meuDb.$detalhe";
    $dados = dataExecSqlDireto($Conexao, $sql);
    echo "<h1>Selecione o campo de relação na tabela detalhe</h1>";
    foreach ($dados as $value) {
        echo "<a href='index.php?master=$master&detalhe=$detalhe&relacao={$value['Field']}'>{$value['Field']}</a> <br>";
    }
    exit();
}

$relacao = $_GET['relacao'];
$relacaoU = ucfirst($relacao);

ob_start();
include 'master-index.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>adm/$master/index.php</div>";

ob_start();
include 'detalhe-index.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>adm/$detalhe/index.php</div>";

ob_start();
include 'detalhe-inserir.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>adm/$detalhe/inserir.php</div>";

ob_start();
include 'detalhe-editar.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>adm/$detalhe/editar.php</div>";

ob_start();
include 'detalhe-deletar.php';
$i = ob_get_clean();
$i = str_replace("<~?php", "<?php", $i);
$i = str_replace("?~>", "?>", $i);
echo "<textarea style='width: 100%; height: 250px;'>$i</textarea><div>adm/$detalhe/deletar.php</div>";

