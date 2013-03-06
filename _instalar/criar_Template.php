<?php

function criarFend($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    
    criarFendCriar($tabela, $realFolder, $campos, "fendIndex.html", $outputFolder . $tabela . ".php");
    criarFendCriar($tabela, $realFolder, $campos, "fendIndexPhp.html", $outputFolder . $tabela . "_php.php");
    
    criarFendCriar($tabela, $realFolder, $campos, "fendDetalhe.html", $outputFolder . $tabela . "-detalhe.php");
    criarFendCriar($tabela, $realFolder, $campos, "fendDetalhePhp.html", $outputFolder . $tabela . "-detalhe_php.php");
}

function criarFendCriar($tabela, $realFolder, $campos, $template, $fileout) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize($template, array (
                                         '$tabela#' => $tabela,
                                         '$tabelaFistUpper#' => ucfirst($tabela),
                                         '$templatesFolder#' => $realFolder . "templates/",
                                         '$campovigula#' => criarDbaseCampos($campos, "\$Field, ", false, "\$Field"),
                                         '$primarykey#' => $campos[0]['Field']
                                        )
                    , $temlate);
    
    arquivos::criar($s, $fileout);
}