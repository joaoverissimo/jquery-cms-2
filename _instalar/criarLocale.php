<?php

function criarLocale($Conexao, $tabela, $campos, $relacoes, $outputFolder, $realFolder) {
    $primarykey = $campos[0]['Field'];

    $s = "table_$tabela;" . ucfirst($tabela) . "\n";
    foreach ($campos as $campo) {
        $field = $campo["Field"];
        $fieldU = ucfirst($campo["Field"]);
        $s.= "$tabela.$field;$fieldU\n";
    }

    $filename = $outputFolder . "locale/pt-br/$tabela.csv";
    arquivos::criar($s, $filename);
}