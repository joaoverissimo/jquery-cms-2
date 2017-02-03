<?php

function criarDb($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $campo2 =  criarObtemFormFieldsObtemCampo2($Conexao, $tabela, $campos);
    
    $s = stringuize("db.html", array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$DbInserirValores#' => criarDbValores($campos, true),
        '$DbEditarValores#' => criarDbValores($campos, false),
        '$campo2#' => $campo2['Field'],
        '$DbDeleteRel#' => criarDbDeleteObjs($tabela, $campos, $relacoes)
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

function criarDbDeleteObjs($tabela, $campos, $relacoes) {
    $tabelaU = ucfirst($tabela);
    $primarykey = $campos[0]['Field'];
    $s = "\$obj = new obj$tabelaU(\$Conexao);\n\t\t\$obj->loadByCod(\${$campos[0]['Field']});\n\n\t\t";
    $s .= "\$exec = parent::Deletar(\$Conexao, \$$primarykey); \n\n\t\t";

    $coment = true;

    foreach ($relacoes as $relacao) {
        if ($relacao['REFERENCED_TABLE_NAME'] == 'jqueryimage' || $relacao['REFERENCED_TABLE_NAME'] == 'locmapaponto' || $relacao['REFERENCED_TABLE_NAME'] == 'jqueryseo' || $relacao['REFERENCED_TABLE_NAME'] == 'jqueryimagelist') {
            $s .= '$obj->obj' . ucfirst($relacao["COLUMN_NAME"]) . "()->Delete();\n\t\t";
            $coment = false;
        } else {
            $s .= '//$obj->obj' . ucfirst($relacao["COLUMN_NAME"]) . "()->Delete();\n\t\t";
        }
    }

    if ($coment) {
        $s = "/* $s \n\t\t return \$exec;\n\t\t */";
    } else {
        $s .= "\n\t\treturn \$exec;\n\t\t";
    }

    return $s;
}
