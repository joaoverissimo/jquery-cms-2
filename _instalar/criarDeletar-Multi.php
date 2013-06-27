<?php

function criarDeletar_Multi($tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("deletar-multi.html", array (
                                         '$tabela#' => $tabela,
                                         '$tabelaFistUpper#' => ucfirst($tabela),
                                         '$templatesFolder#' => $realFolder . "templates/",
                                         '$primarykey#' => $campos[0]['Field'],
                                         '$primarykeyUpper#' => ucfirst($campos[0]['Field'])
                                        )
                    , $temlate);
    
    $filename = $outputFolder . "adm/" . $tabela . "/deletar-multi.php";
    arquivos::criar($s, $filename);
    
}