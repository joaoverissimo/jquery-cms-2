<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";
    

//Lista os dados
$where = '';
//$where = new dataFilter('equipe.cod', 0, dataFilter::op_different);

$orderBy = new dataOrder('equipe.ordem', 'asc');

$paginaAtual = 0;
if (isset($_REQUEST['page']))
    $paginaAtual = $_REQUEST['page'];

$pager = new dataPager($paginaAtual, 15, $Conexao, 'equipe', $where);

$dados = dbEquipe::ObjsList($Conexao, $where, $orderBy, $pager->getLimit());
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_equipe'); ?> - Listar</title>

        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_equipe'); ?> <small>Listar</small></h3>
                </div>
                
                <div class="btn-toolbar">
                    <a href="inserir.php" class="btn btn-primary"><i class="icon-plus icon-white"></i> Inserir</a>
                    <a href="ordem.php" class="btn btn-primary"><i class="icon-move icon-white"></i> Ordem</a>
                    
                    <form class="navbar-form pull-right">
                        <input type="text" id="filtraList" placeholder="Busca rápida...">
                    </form>
                </div>
                
                <?php
                if ($dados !== false) {
                    //Template from /adm/equipe/templates/lista.html
                    echo dbEquipe::template($dados, 'lista.html');
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
