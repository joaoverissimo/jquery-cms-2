<?php

function criarFend($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {

    criarFendCriar($tabela, $realFolder, $campos, "fendIndex.html", $outputFolder . $tabela . ".php");
    criarFendCriar($tabela, $realFolder, $campos, "fendDetalhe.html", $outputFolder . $tabela . "-detalhe.php");
}

function criarFendCriar($tabela, $realFolder, $campos, $template, $fileout) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";

    //Obtem firs string
    if (isset($campos[1]['Field']))
        $firsStringField = $campos[1]['Field'];
    else 
        $firsStringField = $campos[0]['Field'];
    
    $firsted = false;
    foreach ($campos as $value) {
        if (str_contains($value['Type'], "varchar")) {
            if (!$firsted) {
                $firsStringField = $value['Field'];
                $firsted = true;
            }
        }
    }

    //Obtem fieldvals
    $fieldvals = array();
    foreach ($campos as $value) {
        $field = $value['Field'];
        $fieldU = ucfirst($field);
        $fieldvals[] = "<p><?php echo __('$tabela.$field'); ?> <span><?php echo \$obj->get$fieldU(); ?></span></p>";
    }
    $fieldvals = join("\n\t\t\t\t\t\t\t\t", $fieldvals);

    //fieldvalsDetalhe
    $fieldvalsDetalhe = array();
    foreach ($campos as $value) {
        $field = $value['Field'];
        $fieldU = ucfirst($field);
        $fieldvalsDetalhe[] = "<li>\n\t\t\t\t\t\t<h3><?php echo __('$tabela.$field'); ?> </h3>\n\t\t\t\t\t\t<p><?php echo \$obj->get$fieldU(); ?></p>\n\t\t\t\t\t</li>";
    }
    $fieldvalsDetalhe = join("\n\t\t\t\t\t", $fieldvalsDetalhe);

    $s = stringuize($template, array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$campovigula#' => criarDbaseCampos($campos, "\$Field, ", false, "\$Field"),
        '$primarykey#' => $campos[0]['Field'],
        '$primarykeyU#' => ucfirst($campos[0]['Field']),
        '$fieldvals#' => $fieldvals,
        '$fieldvalsDetalhe#' => $fieldvalsDetalhe,
        '$firsStringField#' => $firsStringField,
            )
            , $temlate);

    arquivos::criar($s, $fileout);
}