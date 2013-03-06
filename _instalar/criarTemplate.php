<?php

function criarTemplate($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    //Tabela
    criarTemplateTabela($Conexao, $tabela, $campos, $outputFolder . "adm/$tabela/templates/__reference.html", "template_tabela.html", $relacoes);
    criarTemplateTabela($Conexao, $tabela, $campos, $outputFolder . "adm/$tabela/templates/lista.html", "template_lista.html", $relacoes);    
}

function criarTemplateTabela($Conexao, $tabela, $campos, $filename, $template, $relacoes) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";

    $s = stringuize($template, array(
        '$tabela#' => $tabela,
        '$primarykey#' => $campos[0]['Field'],
        '$upperPrimarykey#' => ucfirst($campos[0]['Field']),
        '$campovigula#' => criarDbaseCampos($campos, "\$Field, ", false, "\$Field"),
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$template_campos_th#' => criarDbaseCampos($campos, "<th>__($tabela.Field)</th>\n\t\t", false, "<th>__($tabela.Field)</th>"),
        '$template_campos_td#' => criarDbaseCampos($campos, "<td>getUpperCampo</td>\n\t\t\t", false, "<td>getUpperCampo</td>"),
        '$template_campos_ass#' => criarTemplateTabela_Relacoes($Conexao, $campos, $relacoes)
            )
            , $temlate);

    arquivos::criar($s, $filename);
}

function criarTemplateTabela_Relacoes($Conexao, $campos, $relacoes) {
    $s = "";
    foreach ($campos as $campo) {
        if (criarObtemFormFieldsIsRelacionado($campo['Field'], $relacoes)) {
            $relacao = criarObtemFormFieldsObtemRelacao($campo['Field'], $relacoes);
            $objName = "obj" . ucfirst($relacao["COLUMN_NAME"]);
            $s .= "<li><b>$objName</b></li>\n\t";
            $camposAssociados = obtemCampos($Conexao, $relacao["REFERENCED_TABLE_NAME"]);
            $s .= criarDbaseCampos($camposAssociados, "<li>" . $objName . "->getUpperCampo</li>\n\t", false, "<li>" . $objName . "->getUpperCampo</li>\n\n\t");
        }
    }

    return $s;
}
