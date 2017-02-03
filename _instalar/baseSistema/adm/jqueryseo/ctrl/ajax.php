<?php

require_once '../../../jquerycms/config.php';
$cache = new SimpleCachePhp(__FILE__);

if (isset($_GET['term'])) {
    $where = new dataFilter(dbJqueryseopalavra::_palavra, $_GET['term'], dataFilter::op_likeMatches);
    $orderBy = new dataOrder(dbJqueryseopalavra::_palavra);
    $dados = dbJqueryseopalavra::ObjsList($Conexao, $where, $orderBy, "0,5");

    $arr = array();
    if ($dados) {
        foreach ($dados as $obj) {
            $arr[] = $obj->getPalavra();
        }

        echo json_encode($arr);
    }
}

$cache->CacheEnd();