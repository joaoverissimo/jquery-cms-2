<?php

function criarInserir($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("inserir.html", array (
                                         '$tabela#' => $tabela,
                                         '$tabelaFistUpper#' => ucfirst($tabela),
                                         '$templatesFolder#' => $realFolder . "templates/",
                                         '$primarykey#' => $campos[0]['Field'],
                                         '$editarValoresPost#' => criarEdiarObtemValoresPost($campos),
                                         '$editarValoresVariaveis#' => criarEdiarObtemValoresVariaveis($campos),
                                         '$formfields#' => criarObtemFormFields($Conexao, $campos, $relacoes, $tabela)
                                        )
                    , $temlate);
    
    $filename = $outputFolder . "adm/" . $tabela . "/inserir.php";
    arquivos::criar($s, $filename);
    
}
