<?php

//Checa o login
$currentURL = Fncs_GetCurrentURL(false);
if (!isset($_SESSION['jqueryuser']) && !str_contains($currentURL, $loginURL)) {
    header("Location: $loginURL?redirect=$currentURL");
    exit();
}

//Obtem o current user
if (isset($_SESSION['jqueryuser'])) {
    $currentUser = new objJqueryadminuser($Conexao);
    $currentUser->loadByCod($_SESSION['jqueryuser']);
    if (!$currentUser->validatePermissions()) {
        header("Location: $adm_folder/home/login-permissao.php");
        exit();
    }
}

//Define o tema
$adm_tema = "default";
$cancelLink = "index.php";
if (isset($_GET['tema']) && $_GET['tema']) {
    $adm_tema = $_GET['tema'];
    $cancelLink = "";
}