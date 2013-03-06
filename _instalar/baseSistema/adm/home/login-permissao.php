<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";


//Lista os dados
$where = '';
//$where = new dataFilter('post.cod', 0, dataFilter::op_different);

$orderBy = new dataOrder('post.cod', 'desc');

$paginaAtual = 0;
if (isset($_REQUEST['page']))
    $paginaAtual = $_REQUEST['page'];

$pager = new dataPager($paginaAtual, 15, $Conexao, 'post', $where);

$dados = dbPost::ObjsList($Conexao, $where, $orderBy, $pager->getLimit());
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_post'); ?> - Listar</title>

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
