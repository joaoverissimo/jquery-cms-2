<?php

function criarIndex($tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("index.html", array (
                                         '$tabela#' => $tabela,
                                         '$tabelaFistUpper#' => ucfirst($tabela),
                                         '$templatesFolder#' => $realFolder . "templates/",
                                         '$primarykey#' => $campos[0]['Field']
                                        )
                    , $temlate);
    
    $filename = $outputFolder . "adm/" . $tabela . "/index.php";
    arquivos::criar($s, $filename);
}