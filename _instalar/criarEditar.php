<?php

function criarEditar($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("editar.html", array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$editarValoresPost#' => criarEdiarObtemValoresPost($campos),
        '$editarValoresVariaveis#' => criarEdiarObtemValoresVariaveis($campos),
        '$formfields#' => criarObtemFormFields($Conexao, $campos, $relacoes, $tabela)
            )
            , $temlate);

    $filename = $outputFolder . "adm/" . $tabela . "/editar.php";
    arquivos::criar($s, $filename);
}

function criarObtemFormFields($Conexao, $campos, $relacoes, $tabela) {
    $s = "";
    $pular = true;
    $tabelaFistUpper = ucfirst($tabela);

//TEMPLATES    
    $templateVarChar = "\$form->text(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateLongText = "\$form->textarea(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateDate = "\$form->calendario(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateDateTime = "\$form->datatime(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateRelation = "\$form->selectDb(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), '', \$Conexao, '#tabela#', '#REFERENCED_COLUMN_NAME#', '#campo2#');\n";
//FAZ O LOOP
    foreach ($campos as $value) {
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (!$pular) {

            if (!criarObtemFormFieldsIsRelacionado($value['Field'], $relacoes)) {

                $type = $value['Type'];

                if (str_contains($type, "varchar")) {
                    $temp = stringuizeStr($templateVarChar, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 0, 1);
                } elseif (str_contains($type, "int")) {
                    $temp = stringuizeStr($templateVarChar, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 2, 3);
                } elseif ($type == "longtext") {
                    $temp = stringuizeStr($templateLongText, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 0, 1);
                } elseif ($type == "date") {
                    $temp = stringuizeStr($templateDate, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 9, 10);
                } elseif ($type == "datetime") {
                    $temp = stringuizeStr($templateDateTime, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 11, 12);
                }

                if ($value['Default'] != "")
                    $temp = "//" . $temp;
            } else {
                $temp = stringuizeStr($templateRelation, $value);
                $relacao = criarObtemFormFieldsObtemRelacao($value['Field'], $relacoes);
                $campo2 = criarObtemFormFieldsObtemCampo2($Conexao, $relacao['REFERENCED_TABLE_NAME']);

                $temp = str_replace("#tabela#", $relacao['REFERENCED_TABLE_NAME'], $temp);
                $temp = str_replace("#campo1#", $value['Field'], $temp);
                $temp = str_replace("#REFERENCED_COLUMN_NAME#", $relacao['REFERENCED_COLUMN_NAME'], $temp);
                $temp = str_replace("#campo2#", $campo2['Field'], $temp);
            }

//ADICIONA
            $s .= $temp;
            $temp = "";
        }

        $pular = false;
    }

    return $s;
}

function criarObtemFormFieldsObtemRelacao($campo, $relacoes) {
    $retorno = null;

    foreach ($relacoes as $value) {
        if ($value["COLUMN_NAME"] == $campo)
            $retorno = $value;
    }

    return $retorno;
}

function criarObtemFormFieldsIsRelacionado($campo, $relacoes) {
    $retorno = false;

    foreach ($relacoes as $value) {
        if ($value["COLUMN_NAME"] == $campo)
            $retorno = true;
    }

    return $retorno;
}

function criarObtemFormFieldsObtemCampo2($Conexao, $REFERENCED_TABLE_NAME) {
    $campos = obtemCampos($Conexao, $REFERENCED_TABLE_NAME);
    foreach ($campos as $campo) {
        if (strpos($campo['Type'], "varchar") !== false)
            return $campo;
    }

    return $campos[0];
}

function criarEdiarObtemFormFieldsObtemValidate($string, $valueNull, $norequired, $required) {
    if ($valueNull == "NO")
        $string = str_replace("#validate#", $required, $string);
    else
        $string = str_replace("#validate#", $norequired, $string);

    return $string;
}

function criarEdiarObtemValoresVariaveis($campos) {
    $s = "";
    $pular = true;
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

function criarEdiarObtemValoresPost($campos) {
    $s = "\t";
    $pular = true;
    $template = "\$registro->setCampoUpper(issetpost('Field'));\n\t";
    $templateI = "\$registro->setCampoUpper(issetpostInteger('Field'));\n\t";

    foreach ($campos as $value) {
        $temp = "";
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (!$pular) {
            if ($value['Type'] == "int(11)")
                $temp .= stringuizeStr($templateI, $value);
            else
                $temp .= stringuizeStr($template, $value);

            if ($value['Default'] != "")
                $temp = "//" . $temp;

            $s .= $temp;
        }

        $pular = false;
    }
    return $s;
}
