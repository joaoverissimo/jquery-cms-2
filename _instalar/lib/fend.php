<?php

function returnFendPaginacaoSqlLimit($recordsPorPagina, $paginaAtual) {
    $i = $recordsPorPagina * $paginaAtual;
    return "$i, $recordsPorPagina";
}

function returnFendPaginacao($countId, $recordsPorPagina) {
    
    $retorno = "";

    if ($countId > $recordsPorPagina) {
        $i = ceil($countId / $recordsPorPagina);

        for ($index = 0; $index < $i; $index++) {
            $url_self = returnFendPaginacaoUrlQueryString("pag" , $index);
            $s = " <a href='$url_self'>[$index]</a> ";
            $retorno .= $s;
        }

        return $retorno . " - Total de Registros: $countId";
    } else
        return "";
}

function returnFendPaginacaoUrlQueryString($parametro, $valor) {
    $self = $_SERVER["PHP_SELF"];

    if($_SERVER["QUERY_STRING"]) {

        $querys = $_SERVER["QUERY_STRING"];

        if (str_contains($querys, $parametro)) {
            //tiro=1&pag=1
            $parametros = explode("&", $querys);
            $querys = "";
            $concatenador = "";
            foreach ($parametros as $value) {
                $values = explode("=", $value);

                if ($values[0] != $parametro) {
                    $querys .= $concatenador . $values[0] . "=" . $values[1];
                    $concatenador = "&";
                }
            }

        }

        if ($querys)
            $querys .= "&";

        return $self . "?" . $querys . $parametro . "=" . $valor;

    } else
        return $self . "?" . $parametro . "=" . $valor;
    }