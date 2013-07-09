<?php

function criarIndex($tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    global $Conexao;
    $campo2 =  criarObtemFormFieldsObtemCampo2($Conexao, $tabela, $campos);
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("index.html", array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$primarykeyU#' => ucfirst($campos[0]['Field']),
        '$campo2#' => $campo2['Field'],
        '$tableListaThead#' => criarIndexTableListaThead($tabela, $campos, $relacoes),
        '$tableListaItem#' => criarIndexTableListaItem($tabela, $campos, $relacoes)
        )
            , $temlate);


    $filename = $outputFolder . "adm/" . $tabela . "/index.php";
    arquivos::criar($s, $filename);
}

function criarIndexTableListaThead($tabela, $campos, $relacoes) {
    $s = "";
    $tab = "\t\t\t\t\t\t\t\t";
    foreach ($campos as $value) {
        if (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
            /* $s .= "<th><?php echo __('$tabela.{$value['Field']}');?></th> \n$tab"; */
            $s .= ""; #nao exibe na lista
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
            $s .= ""; #nao exibe na lista
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
            $s .= "<th><?php echo __('$tabela.{$value['Field']}');?></th> \n$tab";
        } else {
            $s .= "<th><?php echo __('$tabela.{$value['Field']}');?></th> \n$tab";
        }
    }

    return $s;
}

function criarIndexTableListaItem($tabela, $campos, $relacoes) {
    global $Conexao;
    $s = "";
    $tab = "\t\t\t\t\t\t\t\t";

    foreach ($campos as $value) {
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimage")) {
            $template = "<td><?php if (\$registro->objCampoUpper()->getExiste()) { echo \"<img src='{\$registro->objCampoUpper()->getSrc(70, 35)}' />\"; } ?></td>\n$tab";
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
            /* $template = "<td><img src='<?php echo \$registro->objCampoUpper()->getGmapsStaticImage(14, 70, 35);?>' /></td>\n$tab"; */
            $template = ""; #nao exibe na lista
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
            $template = ""; #nao exibe na lista
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
            unset($value['Default']);
            $template = "<td><?php if (\$registro->objCampoUpper()->objImageFirstOrDefault()) { echo \"<img src='{\$registro->objCampoUpper()->objImageFirstOrDefault()->getSrc(70, 35)}' />\"; } ?></td>";
        } elseif (criarObtemFormFieldsIsRelacionado($value['Field'], $relacoes)) {
            $relacao = criarObtemFormFieldsObtemRelacao($value['Field'], $relacoes);
            $campo2 = criarObtemFormFieldsObtemCampo2($Conexao, $relacao['REFERENCED_TABLE_NAME']);
            $campo2 = ucfirst($campo2['Field']);
            $template = "<td><?php echo \$registro->objCampoUpper()->get{$campo2}(); ?></td>\n$tab";
        } else {
            $template = "<td><?php echo \$registro->getCampoUpper(); ?></td>\n$tab";
        }

        $s .= stringuizeStr($template, $value);
    }

    return $s;
}