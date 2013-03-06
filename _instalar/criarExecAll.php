<?php

require 'criarDbase.php';

function criarExecAll($Conexao, $temlate, $dados, $outputFolder, $meuDb, $httpCreator) {

    criarBootStrapData($Conexao, $temlate, $dados, $outputFolder, $meuDb, $httpCreator);
    criarBootStrapObjFend($temlate, $dados, $outputFolder, $meuDb);
    criarLogin($outputFolder);
    criarHome($outputFolder, $dados);
    criarLibConfig($outputFolder);
    criarPermissoes($Conexao, $dados, $outputFolder, $meuDb);
    unzipFolderBase($outputFolder);
    posUnzip($outputFolder, $dados, $meuDb);
}

function criarBootStrapData($Conexao, $temlate, $dados, $outputFolder, $meuDb, $httpCreator) {
    $bootStrapData = stringuize("bootStrapData.html", null, $temlate);

    foreach ($dados as $value) {
        $tabela = $value["Tables_in_$meuDb"];
        $tabelaFistUpper = ucfirst($tabela);

        $bootStrapData .= "require_once 'db$tabelaFistUpper.php';\n";

        //cria a tabela
        echo file_get_contents($httpCreator . "index.php?tabela=$tabela&meudb=$meuDb&execAll=1");
    }

    arquivos::criar($bootStrapData, $outputFolder . "jquerycms/dataClass/bootStrapData.php");
}

function criarBootStrapObjFend($temlate, $dados, $outputFolder, $meuDb) {
    $s = stringuize("bootStrapObjFend.html", null, $temlate);

    foreach ($dados as $value) {
        $tabela = $value["Tables_in_$meuDb"];
        $tabelaFistUpper = ucfirst($tabela);

        $s .= "require_once 'obj$tabelaFistUpper.php';\n";
    }

    arquivos::criar($s, $outputFolder . "jquerycms/dataObj/bootStrapObjFend.php");
}

function criarLogin($outputFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("login.html", array(
        '$___AdmUser#' => ___AdmUser,
        '$___AdmPass#' => ___AdmPass
            )
            , $temlate);

    $filename = $outputFolder . "adm/login.php";
    arquivos::criar($s, $filename);
}

function criarHome($outputFolder, $dados) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    global $meuDb;

    $links = "";
    foreach ($dados as $tabela) {
        $tabelaNome = $tabela["Tables_in_$meuDb"];
        $tabelaStr = ucfirst($tabela["Tables_in_$meuDb"]);
        $links .= "<li><a href='/adm/$tabelaNome/index.php'>$tabelaStr</a></li>\n\t";
    }

    $linksIndexAdmin = "";
    foreach ($dados as $tabela) {
        $tabelaNome = $tabela["Tables_in_$meuDb"];
        $tabelaStr = ucfirst($tabela["Tables_in_$meuDb"]);
        $linksIndexAdmin .= "\n\t<li>\n\t\t<a href='/adm/$tabelaNome/index.php'>\n\t\t\t<img src='/adm/lib/temas/admin/icones/ico-page.png' />\n\t\t\t<span>$tabelaStr</span>\n\t\t</a>\n\t</li>\n\t";
    }

    $s = stringuize("indexAdmin.html", array('$links#' => $linksIndexAdmin), $temlate);
    $filename = $outputFolder . "adm/index.php";
    arquivos::criar($s, $filename);
}

function criarLibConfig($outputFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("lib_config.html", array(
        '$___MySqlDataServer#' => ___MySqlDataServer,
        '$___MysqlDataDb#' => ___MysqlDataDb,
        '$___MySqlDataUser#' => ___MySqlDataUser,
        '$___MySqlDataPass#' => ___MySqlDataPass
            )
            , $temlate);

    $filename = $outputFolder . "jquerycms/config.php";
    arquivos::criar($s, $filename);
}

function unzipFolderBase($outputFolder) {
    $fileIn = "baseSistema.zip";
    $fileOut = $outputFolder;

    $zip = new dUnzip2($fileIn);

    $zip->unzipAll($outputFolder);
    /* $zip = new ZipArchive;

      $filename = $outputFolder . "_instalar/baseSistema.zip";

      if ($zip->open($filename) === TRUE) {
      $zip->extractTo($outputFolder);
      $zip->close();

      return true;
      } else
      return false; */
}

function criarPermissoesAddMenu($Conexao, $tabela, $dados, $outputFolder, $meuDb) {
    $tabelaU = ucfirst($tabela);

    $in = $outputFolder . "_instalar/lib/ico-page.png";
    $out = $outputFolder . "jquerycms/upload/images/ico-$tabela.png";
    copy($in, $out);

    dataExecSqlDireto($Conexao, "INSERT INTO `jqueryimage`(`valor`) VALUES ('ico-$tabela.png')", false);
    $img = dataExecSqlDireto($Conexao, "SELECT MAX(cod) AS getmax FROM `jqueryimage`", false);

    dataExecSqlDireto($Conexao, "INSERT INTO `jqueryadminmenu`(`codmenu`, `titulo`, `patch`, `icon`, `addhtml`, `ordem`) 
                                     VALUES                       (0,'$tabelaU','$tabela',{$img['getmax']},'',1)", false);

    $menu = dataExecSqlDireto($Conexao, "SELECT MAX(cod) AS getmax FROM `jqueryadminmenu`", false);
    dataExecSqlDireto($Conexao, "INSERT INTO `jqueryadmingrupo2menu`(`jqueryadmingrupo`, `jqueryadminmenu`) 
                                     VALUES                             (1,{$menu['getmax']})", false);

    dataExecSqlDireto($Conexao, "INSERT INTO `jqueryadmingrupo2menu`(`jqueryadmingrupo`, `jqueryadminmenu`) 
                                     VALUES                             (1,{$menu['getmax']})", false);

    if (strpos($tabela, "jquery") === false) {
        dataExecSqlDireto($Conexao, "INSERT INTO `jqueryadmingrupo2menu`(`jqueryadmingrupo`, `jqueryadminmenu`) 
                                     VALUES                             (2,{$menu['getmax']})", false);
    }
}

function criarPermissoes($Conexao, $dados, $outputFolder, $meuDb) {
    //Prepara o db
    dataExecSqlDireto($Conexao, "TRUNCATE TABLE  `jqueryadmingrupo2menu`", false);
    dataExecSqlDireto($Conexao, "DELETE FROM `jqueryadminmenu` WHERE `patch` LIKE 'jquery%'", false);
    dataExecSqlDireto($Conexao, "DELETE FROM `jqueryadminmenu` WHERE  `codmenu` > 1", false);
    dataExecSqlDireto($Conexao, "DELETE FROM `jqueryadminmenu` WHERE  `cod` > 0", false);

    //Cria o menu home
    criarPermissoesAddMenu($Conexao, "home", $dados, $outputFolder, $meuDb);

    //Cria menus das tabelas
    foreach ($dados as $value) {
        $tabela = $value["Tables_in_$meuDb"];
        criarPermissoesAddMenu($Conexao, $tabela, $dados, $outputFolder, $meuDb);
    }

    //Cria o menu jquerycms e envia todas as tabelas jquery como filhos
    criarPermissoesAddMenu($Conexao, "jquerycms", $dados, $outputFolder, $meuDb);
    $menu = dataExecSqlDireto($Conexao, "SELECT MAX(cod) AS getmax FROM `jqueryadminmenu`", false);
    dataExecSqlDireto($Conexao, "INSERT INTO `jqueryadmingrupo2menu`(`jqueryadmingrupo`, `jqueryadminmenu`) 
                                     VALUES                             (1,{$menu['getmax']})", false);

    dataExecSqlDireto($Conexao, "UPDATE `jqueryadminmenu` SET `codmenu`= {$menu['getmax']} WHERE `patch` LIKE 'jquery%'", false);
    dataExecSqlDireto($Conexao, "UPDATE `jqueryadminmenu` SET `codmenu` = 0 WHERE `cod` = {$menu['getmax']}", false);
}

function posUnzip($outputFolder, $dados, $meuDb) {
    //corrige o frontend header (/masterpage/header.php)
    $header = arquivos::ler($outputFolder . "masterpage/header.php");
    $s = "";
    foreach ($dados as $value) {
        $tabela = $value["Tables_in_$meuDb"];
        $tabelaFistUpper = ucfirst($tabela);

        $s .= "<li><a href='/$tabela.php'>$tabelaFistUpper</a></li>";
    }

    $header = str_replace("#itens#", $s, $header);
    arquivos::criar($header, $outputFolder . "masterpage/header.php");
}