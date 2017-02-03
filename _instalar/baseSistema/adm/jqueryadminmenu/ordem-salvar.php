<?php

require_once '../../jquerycms/config.php';

$i = 0;
foreach ($_POST['order'] as $listItem) {
    $obj = new objJqueryadminmenu($Conexao);
    $obj->loadByCod($listItem);

    $obj->setOrdem($i);
    $obj->Save();
    $i++;
}

echo "";