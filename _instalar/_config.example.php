<?php

    $meuDb = "aptoplanta";
    $httpCreator = "http://localhost/_instalar/";    
    
    $outputFolder = $_SERVER['DOCUMENT_ROOT']; //pasta onde os arquivo serão salvos
    if ($outputFolder[strlen($outputFolder) - 1] != "/")
        $outputFolder.= "/";
        
    define('___MySqlDataServer', "localhost");
    define('___MySqlDataUser', "root");
    define('___MySqlDataPass', "admin");
    define('___MysqlDataDb', "aptoplanta");
    
    define('___AdmUser', "admin");
    define('___AdmPass', "admin");
    