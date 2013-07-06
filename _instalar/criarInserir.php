<?php

function criarInserir($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $temlate = obterDocumentRoot() . "_instalar/templates/";
    $s = stringuize("inserir.html", array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$inserirValoresPost#' => criarInserirObtemValoresPost($campos, $relacoes),
        '$editarValoresVariaveis#' => criarEdiarObtemValoresVariaveis($campos),
        '$formfields#' => criarInserirObtemFormFields($Conexao, $campos, $relacoes, $tabela),
        '$inserirCtrls#' => criarInserirCtrls($campos, $relacoes),
        '$inserirCtrlsHead#' => criarInserirCtrlsHead($campos, $relacoes)
            )
            , $temlate);

    $filename = $outputFolder . "adm/" . $tabela . "/inserir.php";
    arquivos::criar($s, $filename);
}

function criarInserirObtemValoresPost($campos, $relacoes) {
    global $Conexao;
    $s = "";
    $pular = true;

    foreach ($campos as $value) {
        $temp = "";
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (!$pular) {
            if (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimage")) {
                $template = "\$registro->setCampoUpper(dbJqueryimage::InserirByFileImagems(\$Conexao, 'Field'));\n\t\t";
            } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
                $template = "\n\t\t\$ctrlCampoUpper->SaveByPost();\n\t\t\$registro->setCampoUpper(\$ctrlCampoUpper->codPontoMapa);\n\t\t//\$registro->setCampoUpper(dbLocmapaponto::Inserir(\$Conexao, 0, 0, 0, 0, 0, 3, 0));\n\t\t\n\t\t";
            } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
                $template = "";
                $firstString = criarObtemFormFieldsObtemCampo2($Conexao, '', $campos);
                if (isset($firstString) && isset($firstString['Field'])) {
                    $template .= "\n\t\t\$ctrlCampoUpper->keyTitle = issetpost('{$firstString['Field']}');";
                }
                $template .= "\n\t\t\$registro->setCampoUpper(\$ctrlCampoUpper->inserirByPost()); \n\t\t//\$registro->setCampoUpper(dbJqueryseo::Inserir(\$Conexao, '', ''));\n\t\t";
            } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
                $template = "\$registro->setCampoUpper(dbJqueryimagelist::Inserir(\$Conexao, 0));\n\t\t";
            } elseif ($value['Type'] == "int(11)" || $value['Type'] == "tinyint(1)") {
                $template = "\$registro->setCampoUpper(issetpostInteger('Field'));\n\t\t";
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

function criarInserirObtemFormFields($Conexao, $campos, $relacoes, $tabela) {
    $s = "";
    $pular = true;
    $tabelaFistUpper = ucfirst($tabela);

    //TEMPLATES    
    $templateVarChar = "\$form->text(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateBoolean = "\$form->checkbox(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateLongText = "\$form->textarea(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateDate = "\$form->calendario(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateDateTime = "\$form->datatime(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), #validate#);\n";
    $templateRelation = "\$form->selectDb(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), '1', \$Conexao, '#tabela#', '#REFERENCED_COLUMN_NAME#', '#campo2#');\n";
    $templateFileImage = "\$form->fileImagems(__('$tabela.Field'), 'Field', \$registro->getCampoUpper(), 1);\n";
    $templateLocMapa = "\$form->insertHtml(autoform2::LabelControlGroup('Posição do mapa', \$ctrlCampoUpper->getCtrl()));\n";
    $templateSeo = "\$form->insertHtml(\$ctrlCampoUpper->getCtrl());\n";
    $templateImageList = "";

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
                    $temp = ""; #nao possui controle para insercao
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

function criarInserirCtrls($campos, $relacoes) {
    $s = "";

    foreach ($campos as $value) {
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
            $temp = "\$ctrl#CampoUpper# = new CtrlMapaPontoLatLng(\$Conexao, '#Field#'); \n\$ctrl#CampoUpper#->loadByParams('DF', 'Brasilia', '', '', '');\n";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $temp = str_replace("#Field#", $value['Field'], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
            $temp = "\$ctrl#CampoUpper# = new CtrlJquerySeo(\$Conexao, 0, 'jqueryseoctrl#CampoUpper#');\n";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
            $s .= ""; #nao possui controle para insercao
        }
    }

    return $s;
}

function criarInserirCtrlsHead($campos, $relacoes) {
    $s = "";

    foreach ($campos as $value) {
        $value["CampoUpper"] = ucfirst($value['Field']);

        if (obtemRelacaoMachTable($value['Field'], $relacoes, "locmapaponto")) {
            $temp = "<?php echo \$ctrl#CampoUpper#->getHead(); ?>\n\t\t";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryseo")) {
            $temp = "<?php echo \$ctrl#CampoUpper#->getHead(); ?>\n\t\t";
            $temp = str_replace("#CampoUpper#", $value["CampoUpper"], $temp);
            $s .= $temp;
        } elseif (obtemRelacaoMachTable($value['Field'], $relacoes, "jqueryimagelist")) {
            $s .= ""; #nao possui controle para insercao
        }
    }

    return $s;
}