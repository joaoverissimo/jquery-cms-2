<?php

require_once '../../jquerycms/config.php';

if (isset($_SESSION['jqueryuser'])) {
    $_SESSION['jqueryuser'] = null;
    $_SESSION['syncdb'] = null;

    saas_syncDb($Conexao);
}

header("Location: login.php");
