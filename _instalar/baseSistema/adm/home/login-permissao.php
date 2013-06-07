<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

?><!DOCTYPE HTML>
<html>
    <head>
        <title>Permissões inválidas</title>

        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3>Permissões inválidas!</h3>
                </div>

                <p>Atualmente seu grupo não permite acesso a este recurso do sistema.</p>
                <p>Solicite ao administrador para conceder-lhe as pemissões de acesso, ou, faça <a href="login.php">login</a> com outra conta.</p>

            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>