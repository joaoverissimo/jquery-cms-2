<?php

require_once '../../config.php';

if (!isset($_POST['jqueryimagelist'])) {
    echo "Lista desconhecida";
    exit();
}

if (!isset($_POST['order'])) {
    echo "Ordem descohecida";
    exit();
}

if (!issetArray($_POST['order'])) {
    echo "Ordem mal formatada";
    exit();
}

$jqueryimagelist = dbJqueryimagelist::decode($_POST['jqueryimagelist']);

$i = 0;
foreach ($_POST['order'] as $listItem) {
    $obj = new objJqueryimagelistitem($Conexao);
    $obj->loadByCod($listItem);

    if ($obj->getJqueryimagelist() == $jqueryimagelist) {
        $obj->setOrdem($i);
        $obj->Save();
        $i++;
    }
}

echo "";