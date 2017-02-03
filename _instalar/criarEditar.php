<?php

function criarEditar($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("editar.html", array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$editarValoresPost#' => criarEdiarObtemValoresPost($campos, $relacoes),
        '$editarValoresVariaveis#' => criarEdiarObtemValoresVariaveis($campos),
        '$formfields#' => criarObtemFormFields($Conexao, $campos, $relacoes, $tabela),
        '$editarCtrls#' => criarEditarCtrls($campos, $relacoes),
        '$editarCtrlsHead#' => criarEditarCtrlsHead($campos, $relacoes)
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
    $templateBoolean = "\$form->checkbox(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateLongText = "\$form->textarea(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateDate = "\$form->calendario(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateDateTime = "\$form->datatime(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateRelation = "\$form->selectDb(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), '', \$Conexao, '#tabela#', '#REFERENCED_COLUMN_NAME#', '#campo2#');\n";
    $templateFileImage = "\$form->fileImagems(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), 0);\n";
    $templateLocMapa = "\$form->insertHtml(autoform2::LabelControlGroup('Posição do mapa', \$ctrlCampoUpper->getCtrl()));\n";
    $templateSeo = "\$form->insertHtml(\$ctrlCampoUpper->getCtrl());\n";
    $templateImageList = "\$form->insertHtml(\$ctrlCampoUpper->getCtrl());\n";
    $templateFloat = "\$form->floatReal(__('$tabela.Field'), 'Field', \$registro->getCampoUpperRS(), #validate#);\n";

    //FAZ O LOOP
    foreach ($campos as $value) {
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (!$pular) {
            if (!criarObtemFormFieldsIsRelacionado($value['Field'], $relacoes)) {
                $type = $value['Type'];

                if (str_contains($type, "varchar")) {
                    $temp = stringuizeStr($templateVarChar, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 0, 1);
                } elseif (str_contains($type, "tinyint")) {
                    $temp = stringuizeStr($templateBoolean, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 0, 0);
                } elseif (str_contains($type, "int")) {
                    $temp = stringuizeStr($templateVarChar, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 2, 3);
                } elseif ($type == "longtext" || $type == "text") {
                    $temp = stringuizeStr($templateLongText, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 0, 1);
                } elseif ($type == "date") {
                    $temp = stringuizeStr($templateDate, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 9, 10);
                } elseif ($type == "datetime") {
                    $temp = stringuizeStr($templateDateTime, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 11, 12);
                } elseif (str_contains($type, "float") || str_contains($type, "decimal")) {
                    $temp = stringuizeStr($templateFloat, $value);
                    $temp = criarEdiarObtemFormFieldsObtemValidate($temp, $value['Null'], 0, 1);
                }

                if ($value['Default'] != "")
                    $temp = "//" . $temp;
            } else {
                if (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimage")) {
                    $temp = stringuizeStr($templateFileImage, $value);
                } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
                    $temp = stringuizeStr($templateLocMapa, $value);
                } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
                    $temp = stringuizeStr($templateSeo, $value);
                } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
                    $temp = stringuizeStr($templateImageList, $value);
                } else {
                    $temp = stringuizeStr($templateRelation, $value);
                    $relacao = criarObtemFormFieldsObtemRelacao($value['Field'], $relacoes);
                    $campo2 = criarObtemFormFieldsObtemCampo2($Conexao, $relacao['REFERENCED_TABLE_NAME']);

                    $temp = str_replace("#tabela#", $relacao['REFERENCED_TABLE_NAME'], $temp);
                    $temp = str_replace("#campo1#", $value['Field'], $temp);
                    $temp = str_replace("#REFERENCED_COLUMN_NAME#", $relacao['REFERENCED_COLUMN_NAME'], $temp);
                    $temp = str_replace("#campo2#", $campo2['Field'], $temp);
                }
            }

            //ADICIONA
            $s .= $temp;
            $temp = "";
        }

        $pular = false;
    }

    return $s;
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

function criarEdiarObtemValoresPost($campos, $relacoes) {
    $s = "";
    $pular = true;

    foreach ($campos as $value) {
        $temp = "";
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (!$pular) {
            if (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimage")) {
                $template = "\$registro->setCampoUpper(dbJqueryimage::UpdateByFileImagems(\$Conexao, 'Field'));\n\t\t";
            } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
                $template = "\n\t\t\$ctrlCampoUpper->SaveByPost();\n\t\t\n\t\t";
            } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
                $template = "\n\t\t\$registro->setCampoUpper(\$ctrlCampoUpper->updateByPost());\n\t\t";
            } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
                $template = ""; #salva por ajax, nao necessita salvar
            } elseif (($value['Type'] == "int(11)" || $value['Type'] == "tinyint(1)") && $value['Null'] == "NO") {
                $template = "\$registro->setCampoUpper(issetpostInteger('Field'));\n\t\t";
            } elseif (($value['Type'] == "int(11)" || $value['Type'] == "tinyint(1)") && $value['Null'] == "YES") {
                $template = "\$registro->setCampoUpper(issetpostInteger('Field', true));\n\t\t";
            } elseif ($value['Null'] == "YES") {
                $template = "\$registro->setCampoUpper(issetpost('Field', true));\n\t\t";
            } else {
                $template = "\$registro->setCampoUpper(issetpost('Field'));\n\t\t";
            }

            $temp .= stringuizeStr($template, $value);

            if ($value['Default'] != "") {
                $temp = "//" . $temp;
            }

            $s .= $temp;
        }

        $pular = false;
    }
    return $s;
}

function criarEditarCtrls($campos, $relacoes) {
    $s = "";

    foreach ($campos as $value) {
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
            $temp = "\$ctrl#CampoUpper# = new CtrlMapaPontoLatLng(\$Conexao, '#Field#'); \n\$ctrl#CampoUpper#->loadByCod(\$registro->get#CampoUpper#());";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $temp = str_replace("#Field#", $value['Field'], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
            $temp = "\$ctrl#CampoUpper# = new CtrlJquerySeo(\$Conexao, \$registro->get#CampoUpper#(), 'jqueryseoctrl#CampoUpper#');\n";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
            $temp = "\$ctrl#CampoUpper# = new CtrlJqueryImageList('ctrl#CampoUpper#', \$registro->get#CampoUpper#());\n";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        }
    }

    return $s;
}

function criarEditarCtrlsHead($campos, $relacoes) {
    $s = "";

    foreach ($campos as $value) {
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
            $temp = "<?php echo \$ctrl#CampoUpper#->getHead(); ?>\n\t";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
            $temp = "<?php echo \$ctrl#CampoUpper#->getHead(); ?>\n\t\t";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
            $temp = "<?php echo \$ctrl#CampoUpper#->getHead(); ?>\n\t\t";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        }
    }

    return $s;
}
