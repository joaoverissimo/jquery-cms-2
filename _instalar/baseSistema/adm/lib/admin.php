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
        header("Location: $adm_folder/login/login-permissao.php?url=" . Fncs_GetCurrentURL(true));
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

function obtemFiltros($ScriptName) {
    $index = strpos($ScriptName, ".php") + 4;
    $ScriptName = substr($ScriptName, 0, $index);

    if (isset($_GET['clear'])) {
        $_SESSION[$ScriptName] = null;
        unset($_SESSION[$ScriptName]);
        return "clear";
    } elseif (isset($_GET['filtraList'])) {
        $_SESSION[$ScriptName] = $_GET;
        return "get";
    } elseif (isset($_SESSION[$ScriptName])) {
        if (issetArray($_SESSION[$ScriptName])) {
            foreach ($_SESSION[$ScriptName] as $key => $value) {
                $_GET[$key] = $value;
            }
        }
        return "session";
    }
}