<?php

if (isset($currentUser)) {
    echo dbJqueryadminmenu::getMenuValidate($Conexao, $adm_folder, 0, 0, null, $currentUser);
}