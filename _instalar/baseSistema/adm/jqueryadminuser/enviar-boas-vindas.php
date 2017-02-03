<?php

require_once '../../jquerycms/config.php';

if (!isset($_GET['cod'])) {
    die("Código inválido");
}

$registro = new objJqueryadminuser($Conexao, true);
$registro->loadByCod($_GET["cod"]);

echo mailJqueryadminuser::enviarBoasVindas($registro);