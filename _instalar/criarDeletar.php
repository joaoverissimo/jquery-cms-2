<?php

function criarDeletar($tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("deletar.html", array (
                                         '$tabela#' => $tabela,
                                         '$tabelaFistUpper#' => ucfirst($tabela),
                                         '$templatesFolder#' => $realFolder . "templates/",
                                         '$primarykey#' => $campos[0]['Field'],
                                         '$primarykeyUpper#' => ucfirst($campos[0]['Field'])
                                        )
                    , $temlate);
    
    $filename = $outputFolder . "adm/" . $tabela . "/deletar.php";
    arquivos::criar($s, $filename);
    
}