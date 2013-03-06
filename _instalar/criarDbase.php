<?php

function criarDbase($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $primarykey = $campos[0]['Field'];
    $temlate = obterDocumentRoot() . "_instalar/templates/";

    $s = stringuize("dbase.html", array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$DbInserirValores#' => criarDbValores($campos, true),
        '$DbEditarValores#' => criarDbValores($campos, false),
        '$dbasecampos#' => criarDbaseCampos($campos, "const _Field = \"$tabela.Field\";\n\t", false, ''),
        '$dbaseInsertParametros#' => criarDbaseCampos($campos, "`Field`, ", true, "`Field`"),
        '$dbaseInsertParametrosBinds#' => criarDbaseCamposInserirBinds($campos, true),
        '$dbaseInsertParametrosValues#' => criarDbaseCampos($campos, "//\$Field - allow db null: Null \n\t\t\t if (!isset(\$Field) && 'YES' == 'Null') \n\t\t\t\t \$statement->bindValue(\":Field\", null); \n\t\t\telseif (!isset(\$Field)) \n\t\t\t\t \$statement->bindValue(\":Field\", \"\");\n\t\t\telse \n\t\t\t\t \$statement->bindValue(\":Field\", \$Field);\n\n\t\t\t", true, ''),
        '$dbaseUpdateParametros#' => criarDbaseCamposUpdateBinds($campos, true),
        '$dbaseUpdateParametroKey#' => str_replace("valor", $primarykey, "`valor` = :valor;"),
        '$dbaseUpdateParametrosValues#' => criarDbaseCampos($campos, "//\$Field - allow db null: Null \n\t\t\t if (!isset(\$Field) && 'YES' == 'Null') \n\t\t\t\t \$statement->bindValue(\":Field\", null); \n\t\t\telseif (!isset(\$Field)) \n\t\t\t\t \$statement->bindValue(\":Field\", \"\");\n\t\t\telse \n\t\t\t\t \$statement->bindValue(\":Field\", \$Field);\n\n\t\t\t", false, ''),
        '$dbaseListValidFields#' => criarDbaseCampos($campos, "$tabela.Field, ", false, "$tabela.Field"),
        '$dbaseStringuize#' => criarDbaseCampos($campos, "\"Field\", ", false, "\"Field\""),
        '$dbaseLeftJoinFields#' => criarDbaseLeftJoinFields($Conexao, $campos, $relacoes),
        '$dbaseLeftValidFields#' => criarDbaseLeftValidFields($Conexao, $campos, $relacoes),
        '$dbaseLeftJoin#' => criarDbaseLeftJoin($campos, $relacoes),
        '$dbaseCorrigeValoresSimples#' => criarDbaseCorrigeSimples($campos),
        '$dbaseCorrigeValoresAll#' => criarDbaseCorrigeAll($campos),
            )
            , $temlate);

    $filename = $outputFolder . "jquerycms/dataClass/base/dbase" . ucfirst($tabela) . ".php";
    arquivos::criar($s, $filename);
}

function criarDbaseLeftJoinFields($Conexao, $campos, $relacoes) {
    $s = "";

    foreach ($campos as $campo) {
        if (criarObtemFormFieldsIsRelacionado($campo['Field'], $relacoes)) {
            $relacao = criarObtemFormFieldsObtemRelacao($campo['Field'], $relacoes);

            $sub_tabela = $relacao['REFERENCED_TABLE_NAME'];
            $sub_campos = obtemCampos($Conexao, $sub_tabela);
            $sub_name = $relacao['COLUMN_NAME'];
            $temp = criarDbaseCampos($sub_campos, ",\n\t\t\t\t " . $sub_name . "_rel.Field as " . $sub_name . "_Field", false, ",\n\t\t\t\t " . $sub_name . "_rel.Field as " . $sub_name . "_Field ");

            $s .= $temp;
        }
    }

    if ($s)
        return $s;
}

function criarDbaseLeftValidFields($Conexao, $campos, $relacoes) {
    $s = "";

    foreach ($campos as $campo) {
        if (criarObtemFormFieldsIsRelacionado($campo['Field'], $relacoes)) {
            $relacao = criarObtemFormFieldsObtemRelacao($campo['Field'], $relacoes);

            $sub_tabela = $relacao['REFERENCED_TABLE_NAME'];
            $sub_campos = obtemCampos($Conexao, $sub_tabela);
            $sub_name = $relacao['COLUMN_NAME'];
            $temp = criarDbaseCampos($sub_campos, ", " . $sub_name . "_rel.Field ", false, ", " . $sub_name . "_rel.Field ");

            $s .= $temp;
        }
    }

    if ($s)
        return $s;
}

function criarDbaseLeftJoin($campos, $relacoes) {
    $s = "";
    $separador = "";
    $template = "LEFT JOIN REFERENCED_TABLE_NAME COLUMN_NAME_rel ON TABLE_NAME.COLUMN_NAME = COLUMN_NAME_rel.REFERENCED_COLUMN_NAME \n\t\t\t\t";

    foreach ($campos as $campo) {
        if (criarObtemFormFieldsIsRelacionado($campo['Field'], $relacoes)) {
            $relacao = criarObtemFormFieldsObtemRelacao($campo['Field'], $relacoes);

            $temp = $template;
            $temp = str_replace("REFERENCED_TABLE_NAME", $relacao['REFERENCED_TABLE_NAME'], $temp);
            $temp = str_replace("REFERENCED_COLUMN_NAME", $relacao['REFERENCED_COLUMN_NAME'], $temp);
            $temp = str_replace("COLUMN_NAME", $relacao['COLUMN_NAME'], $temp);
            $temp = str_replace("TABLE_NAME", $relacao['TABLE_NAME'], $temp);
            $s .= $temp;
        }
    }

    if ($s)
        return $s;
}

function criarDbaseCampos($campos, $template, $pular, $templateF) {
    if ($templateF == '')
        $templateF = $template;

    $total = count($campos);
    $index = 1;

    $s = "";
    foreach ($campos as $value) {

        if (isset($value["Field"]))
            $value["UpperCampo"] = ucfirst($value["Field"]);

        if (!$pular) {
            if ($total == $index)
                $s .= stringuizeStr($templateF, $value);
            else
                $s .= stringuizeStr($template, $value);
        }

        $index++;
        $pular = false;
    }

    $index = 0;
    return $s;
}

function criarDbaseCamposInserirBinds($campos, $pular) {
    $templateF = '';

    $total = count($campos);
    $index = 1;

    $s = "";
    foreach ($campos as $value) {
        $type = $value['Type'];

        if ($type == "datetime") {
            $template = "STR_TO_DATE(:Field,'%d/%m/%Y %H:%i:%s'), ";
            $templateF = "STR_TO_DATE(:Field,'%d/%m/%Y %H:%i:%s')";
        } elseif ($type == "date") {
            $template = "STR_TO_DATE(:Field,'%d/%m/%Y'), ";
            $templateF = "STR_TO_DATE(:Field,'%d/%m/%Y')";
        } else {
            $template = ":Field, ";
            $templateF = ":Field";
        }


        if (!$pular) {
            if ($total == $index)
                $s .= stringuizeStr($templateF, $value);
            else
                $s .= stringuizeStr($template, $value);
        }

        $index++;
        $pular = false;
    }

    $index = 0;
    return $s;
}

function criarDbaseCamposUpdateBinds($campos, $pular) {
    $templateF = '';

    $total = count($campos);
    $index = 1;

    $s = "";
    foreach ($campos as $value) {
        $type = $value['Type'];

        if ($type == "datetime") {
            $template = "`Field` = STR_TO_DATE(:Field,'%d/%m/%Y %H:%i:%s'),\n\t\t\t\t\t";
            $templateF = "`Field` = STR_TO_DATE(:Field,'%d/%m/%Y %H:%i:%s')\n";
        } elseif ($type == "date") {
            $template = "`Field` = STR_TO_DATE(:Field,'%d/%m/%Y'),\n\t\t\t\t\t";
            $templateF = "`Field` = STR_TO_DATE(:Field,'%d/%m/%Y')\n";
        } else {
            $template = "`Field` = :Field,\n\t\t\t\t\t";
            $templateF = "`Field` = :Field\n";
        }


        if (!$pular) {
            if ($total == $index)
                $s .= stringuizeStr($templateF, $value);
            else
                $s .= stringuizeStr($template, $value);
        }

        $index++;
        $pular = false;
    }

    $index = 0;
    return $s;
}

function criarDbaseCorrigeSimples($campos) {
    $s = "";

    foreach ($campos as $field) {
        $templateDateTime = "if (\$dados['Field']) \n\t\t\t \$dados['Field'] = Fncs_LerDataTime(\$dados['Field']); \n\t\t";
        $templateDate = "if (\$dados['Field']) \n\t\t\t \$dados['Field'] = Fncs_LerData(\$dados['Field']); \n\t\t";

        if ($field['Type'] == "datetime")
            $s .= stringuizeStr($templateDateTime, $field);
        elseif ($field['Type'] == "date")
            $s .= stringuizeStr($templateDate, $field);
    }

    return $s;
}

function criarDbaseCorrigeAll($campos) {
    $s = "";

    foreach ($campos as $field) {
        $templateDateTime = "if (\$dados[\$key]['Field']) \n\t\t\t\t \$dados[\$key]['Field'] = Fncs_LerDataTime(\$value['Field']); \n\t\t\t\t";
        $templateDate = "if (\$dados[\$key]['Field']) \n\t\t\t\t \$dados[\$key]['Field'] = Fncs_LerData(\$dados[\$key]['Field']); \n\t\t\t";

        if ($field['Type'] == "datetime")
            $s .= stringuizeStr($templateDateTime, $field);
        elseif ($field['Type'] == "date")
            $s .= stringuizeStr($templateDate, $field);
    }

    return $s;
}
