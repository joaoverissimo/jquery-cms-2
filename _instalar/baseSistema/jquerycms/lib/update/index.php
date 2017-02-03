<?php

ini_set('max_execution_time','6000');
require_once '../config.php';
$filename = ___AppRoot . "byCdnToUnzip.zip";

//Etapa 01 - libera permissoes de escrita
if (!isset($_GET['etapa'])) {
    $dir = ___AppRoot;
    $result = array();

    if (is_dir($dir)) {
        $iterator = new RecursiveDirectoryIterator($dir);
        foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) {
            if (!$file->isFile()) {
                $result[] = $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename();
            }
        }
    }

    if (issetArray($result)) {
        foreach ($result as $file) {
            chmod($file, 0755);
            echo $file . "<br />";
        }
    }

    echo "<meta http-equiv='refresh' content='0;url=index.php?etapa=2'>";
    echo "<a href='index.php?etapa=2'>Avancar ></a>";
    exit();
}

//Etapa 02 - Faz download do arquivo definido pelo cdn
if (isset($_GET['etapa']) && $_GET['etapa'] == 2) {
    if (arquivos::existe($filename)) {
        arquivos::deletar($filename);
    }

    if (!arquivos::downloadFile(___cdn, $filename)) {
        echo "Problemas ao efetuar download";
        exit();
    }


    echo "<meta http-equiv='refresh' content='1;url=index.php?etapa=3'>";
    echo "<a href='index.php?etapa=3'>Avancar ></a>";
    exit();
}

//Etapa 03 - descompacta o arquivo
if (isset($_GET['etapa']) && $_GET['etapa'] == 3) {
    var_dump(arquivos::UnzipFile($filename, ___AppRoot));

    if (arquivos::existe($filename)) {
        arquivos::deletar($filename);
    }
    
    echo "<meta http-equiv='refresh' content='1;url=index.php?etapa=4'>";
    echo "<a href='index.php?etapa=4'>Avancar ></a>";
    exit();
}

//Etapa 04 - re-define permissoes
if (isset($_GET['etapa']) && $_GET['etapa'] == 4) {
    $dir = ___AppRoot;
    $result = array();

    if (is_dir($dir)) {
        $iterator = new RecursiveDirectoryIterator($dir);
        foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) {
            if (!$file->isFile()) {
                $result[] = $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename();
            }
        }
    }

    if (issetArray($result)) {
        foreach ($result as $file) {
            chmod($file, 644);
            //echo $file . "<br />";
        }
    }

    echo "Atualizacao completa!";
}    