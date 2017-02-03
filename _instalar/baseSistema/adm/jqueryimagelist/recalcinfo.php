<?php

//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$dados = dbJqueryimagelist::ObjsList($Conexao);
if ($dados) {
    foreach ($dados as $obj) {
        $i = $obj->RecalcInfo();
        echo $obj->getCod() . " - $i <br />";
    }
}

echo "Ok";