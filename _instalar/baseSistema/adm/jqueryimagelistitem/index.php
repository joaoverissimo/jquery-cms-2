<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";
    
Fncs_ValidaQueryString("jqueryimagelist", "../jqueryimagelist/index.php");
$jqueryimagelist = $_GET['jqueryimagelist'];

//Lista os dados
$where = new dataFilter('jqueryimagelistitem.jqueryimagelist', $jqueryimagelist);

$orderBy = new dataOrder('jqueryimagelistitem.cod', 'desc');

$paginaAtual = 0;
if (isset($_REQUEST['page']))
    $paginaAtual = $_REQUEST['page'];

$pager = new dataPager($paginaAtual, 15, $Conexao, 'jqueryimagelistitem', $where);

$dados = dbJqueryimagelistitem::ObjsList($Conexao, $where, $orderBy, $pager->getLimit());
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryimagelistitem'); ?> - Listar</title>

        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryimagelistitem'); ?> <small>Listar</small></h3>
                </div>
                
                <div class="btn-toolbar">
                    <a href="inserir.php?jqueryimagelist=<?php echo $jqueryimagelist; ?>" class="btn btn-primary"><i class="icon-plus icon-white"></i> Inserir</a>
                    
                    <form class="navbar-form pull-right">
                        <input type="text" id="filtraList" placeholder="Busca rápida...">
                    </form>
                </div>
                
                <?php
                if ($dados !== false) {
                    //Template from /adm/jqueryimagelistitem/templates/lista.html
                    echo dbJqueryimagelistitem::template($dados, 'lista.html');
                } else {
                    echo "Não existem registros.";
                }
                ?>
                
                <?php echo $pager->getPager(); ?>
                
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>
