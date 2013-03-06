<?php

function criarDb($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("db.html", array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$DbInserirValores#' => criarDbValores($campos, true),
        '$DbEditarValores#' => criarDbValores($campos, false),
        '$DbDeleteRel#' => criarDbDeleteObjs($campos, $relacoes)
            )
            , $temlate);

    $filename = $outputFolder . "jquerycms/dataClass/db" . ucfirst($tabela) . ".php";
    arquivos::criar($s, $filename);
}

function criarDbValores($campos, $pular = true) {
    $s = "";
    $template = "\$Field, ";
    $templateF = "\$Field";

    $total = count($campos);
    $index = 1;

    foreach ($campos as $value) {

        if (!$pular) {
            if ($total == $index)
                $s .= stringuizeStr($templateF, $value);
            else
                $s .= stringuizeStr($template, $value);
        }

        $pular = false;
        $index++;
    }

    return $s;
}

function criarDbDeleteObjs($campos, $relacoes) {
    $s = "";
    foreach ($relacoes as $relacao) {
        $s .= '$obj->obj' . ucfirst($relacao["COLUMN_NAME"]) . "()->Delete();\n\t\t";
    }
    return $s;
}