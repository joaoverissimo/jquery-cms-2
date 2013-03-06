<?php

require_once '../../jquerycms/config.php';

if (isset($_SESSION['jqueryuser'])) {
    $_SESSION['jqueryuser'] = null;
}

header("Location: login.php");