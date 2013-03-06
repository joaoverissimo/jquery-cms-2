<?php

function criarFbase($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $primarykey = $campos[0]['Field'];
    $temlate = obterDocumentRoot() . "_instalar/templates/";

    foreach ($relacoes as $key => $relacao) {
        $relacoes[$key]["OBJNAME"] = ucfirst($relacao["COLUMN_NAME"]);
    }

    $arrayValores = array(
        '$tabela#' => $tabela,
        '$tabelaFistUpper#' => ucfirst($tabela),
        '$templatesFolder#' => $realFolder . "templates/",
        '$primarykey#' => $campos[0]['Field'],
        '$dbasecampos#' => criarDbaseCampos($campos, "protected \$Field;\n\t", false, ''),
        '$dbasepublics#' => criarDbaseCampos($relacoes, "protected \$_objOBJNAME;\n\t", false, ''),
        '$fbaseAssComentarios#' => criarDbaseCampos($relacoes, " * @property objUpperCampo \$_objUpperCampo\n", false, " * @property objUpperCampo \$_objUpperCampo"),
        '$fbaseGetAndSet#' => criarDbaseCampos($campos, "public function getUpperCampo() { \n\t\t return \$this->Field; \n\t} \n\n\tpublic function setUpperCampo(\$Field) { \n\t\t \$this->Field = \$Field; \n\t\t return \$this; \n\t}\n\n\t", false, ''),
        '$fbaseAssObjs#' => criarFbaseAssObjs($relacoes),
        '$criarFbaseLoadLeftByArray#' => criarFbaseLoadLeftByArray($relacoes),
        '$fbaseLoadByArrayFields#' => criarDbaseCampos($campos, "if (isset(\$registro[\$prefixArr . 'Field'])) {\n\t\t\t\$this->setUpperCampo(\$registro[\$prefixArr . 'Field']); \n\t\t\t\$load = true;\n\t\t} \n\n\t\t", false, ''),
        '$fbaseInserirValores#' => criarDbaseCampos($campos, "\$this->getField(),", true, "\$this->getField()"),
        '$fbaseEditarValores#' => criarDbaseCampos($campos, "\$this->getField(),", false, "\$this->getField()"),
        '$fbaseRelInversas#' => criarFbaseRelInversas($Conexao, $tabela)
    );

    $s = stringuize("fbase.html", $arrayValores, $temlate);
    $filename = $outputFolder . "jquerycms/dataObj/base/fbase" . ucfirst($tabela) . ".php";
    arquivos::criar($s, $filename);

    $s = stringuize("fobj.html", $arrayValores, $temlate);
    $filename = $outputFolder . "jquerycms/dataObj/obj" . ucfirst($tabela) . ".php";
    arquivos::criar($s, $filename);

}

function criarFbaseAssObjs($relacoes) {
    $obj = criarDbaseCampos($relacoes, "
    /**
     * Obtem a associacao entre TABLE_NAME.COLUMN_NAME => REFERENCED_TABLE_NAME.REFERENCED_COLUMN_NAME
     * @return objUpperCampo
     */
    public function objOBJNAME(){
    	if (! isset(\$this->_objOBJNAME)) { 
    		\$obj = new objUpperCampo(\$this->Conexao, \$this->die); 
    		\$obj->loadByCod(\$this->COLUMN_NAME); 
    		\$this->_objOBJNAME = \$obj; 
    	} 
    	
    	return \$this->_objOBJNAME; 
    }
		 
		", false, '');

    return $obj;
}

function criarFbaseLoadLeftByArray($relacoes) {
    $obj = criarDbaseCampos($relacoes, "
        \$obj = new objUpperCampo(\$this->Conexao, false);
        \$obj->loadByArray(\$registro, 'COLUMN_NAME_');
        \$this->_objOBJNAME = \$obj;       		 
		", false, '');

    return $obj;
}

function criarFbaseLeftJoinFields($Conexao, $campos, $relacoes) {
    $s = "";

    foreach ($campos as $campo) {
        if (criarObtemFormFieldsIsRelacionado($campo['Field'], $relacoes)) {
            $relacao = criarObtemFormFieldsObtemRelacao($campo['Field'], $relacoes);

            $sub_tabela = $relacao['REFERENCED_TABLE_NAME'];
            $sub_campos = obtemCampos($Conexao, $sub_tabela);
            $temp = criarDbaseCampos($sub_campos, ",\n\t\t" . $sub_tabela . ".Field as " . $sub_tabela . "_Field", false, ",\n\t\t" . $sub_tabela . ".Field as " . $sub_tabela . "_Field ");

            $s .= $temp;
        }
    }

    if ($s)
        return $s;
}

function criarFbaseLeftJoin($campos, $relacoes) {
    $s = "";
    $separador = "";
    $template = "\" LEFT JOIN REFERENCED_TABLE_NAME ON \". self::tabela . \".COLUMN_NAME = REFERENCED_TABLE_NAME.REFERENCED_COLUMN_NAME\" .";
    foreach ($campos as $campo) {
        if (criarObtemFormFieldsIsRelacionado($campo['Field'], $relacoes)) {
            $relacao = criarObtemFormFieldsObtemRelacao($campo['Field'], $relacoes);

            $temp = $template;
            $temp = str_replace("REFERENCED_TABLE_NAME", $relacao['REFERENCED_TABLE_NAME'], $temp);
            $temp = str_replace("REFERENCED_COLUMN_NAME", $relacao['REFERENCED_COLUMN_NAME'], $temp);
            $temp = str_replace("COLUMN_NAME", $relacao['COLUMN_NAME'], $temp);

            $s .= $temp;
        }
    }

    if ($s)
        return $s;
}

function criarFbaseCampos($campos, $template, $pular, $templateF) {
    if ($templateF == '')
        $templateF = $template;

    $total = count($campos);
    $index = 1;

    $s = "";
    foreach ($campos as $value) {

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


function criarFbaseRelInversas($Conexao, $tabela) {
    $relacoesInversas = obtemRelacoesInversa($Conexao, $tabela, $_GET['meudb']);
    foreach ($relacoesInversas as $key => $relacao) {
        $relacoesInversas[$key]["OBJNAME"] = ucfirst($relacao["TABLE_NAME"]);
    }

    $s = "";
    foreach ($relacoesInversas as $relacao) {
        if ($relacao["REFERENCED_TABLE_NAME"] == $tabela) {
            $s .= "\t/**\n\t* Relacao {$relacao["TABLE_NAME"]}.{$relacao["COLUMN_NAME"]} -> {$relacao["REFERENCED_TABLE_NAME"]}.{$relacao['REFERENCED_COLUMN_NAME']}\n\t* @return obj" . ucfirst($relacao["TABLE_NAME"]) . "[]\n\t*/";
            $s .= "\n\t" . '/*public function obtem' . $relacao["OBJNAME"] . 'Rel ($orderByField = "", $orderByOrientation = "", $limit = "") {
' . "\t\t" . '$orderBy = new dataOrder($orderByField, $orderByOrientation);
' . "\t\t" . '$where = new dataFilter("' . $relacao["TABLE_NAME"] . '.' . $relacao["COLUMN_NAME"] . '", $this->get' . ucfirst($relacao['REFERENCED_COLUMN_NAME']) . '());
' . "\t\t" . '$dados = db' . $relacao["OBJNAME"] . '::ObjsList($this->Conexao, $where, $orderBy, $limit);
' . "\t\t" . 'return $dados;
' . "\t" . '}*/' . "\n\n";
        }
    }

    return $s;
}