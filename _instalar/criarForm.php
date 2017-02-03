<?php

function criarForm($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    //Tabela
    criarFormTabela($Conexao, $tabela, $campos, $outputFolder . "adm/$tabela/form/panel-body.php", "adm_tabela_form_panel_body.html", $relacoes);
    criarFormTabela($Conexao, $tabela, $campos, $outputFolder . "adm/$tabela/form/page-btn-toolbar-default.php", "adm_tabela_form_toolbar.html", $relacoes);
}

function criarFormTabela($Conexao, $tabela, $campos, $filename, $template, $relacoes) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";

    $s = stringuize($template, array(
        '$tabela#' => $tabela,
        '$primarykey#' => $campos[0]['Field'],
        '$primarykeyU#' => ucfirst($campos[0]['Field']),
        '$upperPrimarykey#' => ucfirst($campos[0]['Field']),
        '$campovigula#' => criarDbaseCampos($campos, "\$Field, ", false, "\$Field"),
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$template_campos_th#' => criarDbaseCampos($campos, "<th>__($tabela.Field)</th>\n\t\t", false, "<th>__($tabela.Field)</th>"),
        '$template_campos_td#' => criarDbaseCampos($campos, "<td>getUpperCampo</td>\n\t\t\t", false, "<td>getUpperCampo</td>"),
        '$template_campos_ass#' => criarFormTabela_Relacoes($Conexao, $campos, $relacoes),
        '$tableListaThead#' => criarIndexTableListaThead($tabela, $campos, $relacoes),
        '$tableListaItem#' => criarIndexTableListaItem($tabela, $campos, $relacoes)
            )
            , $temlate);

    arquivos::criar($s, $filename);
}

function criarFormTabela_Relacoes($Conexao, $campos, $relacoes) {
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
