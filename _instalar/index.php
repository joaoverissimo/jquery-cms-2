<?php

ini_set('max_execution_time', '6000');

//CONEXAO E PARAMETROS
if (file_exists('_config.php'))
    require_once '_config.php';

require_once 'lib/BootStrapSistema.php';

Creator_Fncs_ValidaQueryString("tabela", "JqueryCms-Step-1.php");
$tabela = $_REQUEST["tabela"];

//OBTEM CAMPOS
$Conexao = CarregarConexao();
$campos = obtemCampos($Conexao, $tabela);
$relacoes = obtemRelacoes($Conexao, $tabela, $meuDb);

//CRIA O DIRETORIO
//root/adm
verificaAllPastas($outputFolder, $tabela);

//ECHO
echo "<div><b>Tabela $tabela</b></div>";

//CRIA A INDEX.PHP
require_once 'criarIndex.php';
criarIndex($tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Index</div>";

//CRIA A EDITAR.PHP
require_once 'criarEditar.php';
criarEditar($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Editar</div>";

//CRIA A INSERIR.PHP
require_once 'criarInserir.php';
criarInserir($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Inserir</div>";

//CRIA A DELETAR.PHP
require_once 'criarDeletar.php';
criarDeletar($tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Deletar</div>";

//CRIA A DELETAR-MULTI.PHP
require_once 'criarDeletar-Multi.php';
criarDeletar_Multi($tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Deletar-Multi</div>";

//CRIA DB
require_once 'criarDb.php';
criarDb($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Db</div>";

//CRIA DBASE
require_once 'criarDbase.php';
criarDbase($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Dbase</div>";

//CRIA TEMPLATES
require_once 'criarForm.php';
criarForm($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Templates</div>";

//CRIA LOCALE
require_once 'criarLocale.php';
criarLocale($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Locale</div>";

//CRIA FBASE
require_once 'criarFbase.php';
criarFbase($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder);
echo "<div>Criado Dbase</div>";


