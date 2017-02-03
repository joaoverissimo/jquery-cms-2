<?php
//REQUIRE E CONEXÃO
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";
    
//Filtra a lista
$filtraList = "";
if (isset($_GET['filtraList']) && $_GET['filtraList']) {
    $filtraList = $_GET['filtraList'];
}

//Lista os dados
$where = '';
if ($filtraList) {
    //$where = new dataFilter(dbJqueryseopalavra::_cod, 0, dataFilter::op_different);
    $where = new dataFilter(dbJqueryseopalavra::_palavra, $filtraList, dataFilter::op_likeMatches);
}

$orderBy = new dataOrder('jqueryseopalavra.cod', 'desc');

$paginaAtual = 0;
if (isset($_REQUEST['page']))
    $paginaAtual = $_REQUEST['page'];

$pager = new dataPager($paginaAtual, 15, $Conexao, 'jqueryseopalavra', $where);

$dados = dbJqueryseopalavra::ObjsListLeft($Conexao, $where, $orderBy, $pager->getLimit());

$form = new autoform2();
$form->start("cadastro", "", "GET", array('class' => 'form-inline '));
$form->text("Busca rapida", "filtraList", $filtraList);
$form->send_cancel("<i class='fa fa-filter'></i> Filtrar", "index.php", array('btn2class' => 'btn-xs btn-default'), "Limpar Filtros");
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryseopalavra'); ?> - Listar</title>
        <link rel='stylesheet' type='text/css' href='/jquerycms/js/autoform.css' />
        
        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryseopalavra'); ?> <small>Listar</small></h3>
                </div>
                
                <div class="btn-toolbar">
                    <a href="inserir.php" class="btn btn-primary"><i class="fa fa-plus"></i> Inserir</a>
                    
                    <?php echo $form->getForm(); ?>
                </div>
                
                <?php if ($dados !== false) : ?>
                <form method="post" action="deletar-multi.php">
                    <table class="table table-hover table-striped" id="tablelista">
                        <thead>
                            <tr>
                                <th class="th-multi"></th>
                                <th><?php echo __('jqueryseopalavra.cod');?></th> 
                                <th><?php echo __('jqueryseopalavra.palavra');?></th> 
                                <th><?php echo __('jqueryseopalavra.url');?></th> 
                                <th><?php echo __('jqueryseopalavra.count');?></th> 
                                
                                <th class="th-actions"></th>
                            </tr>
                        </thead>
                            <?php foreach($dados as $registro) : ?>
                            <tr>
                                <td><label><input type="checkbox" name="multi[]" value="<?php echo $registro->getCod(); ?>" class="multi-input" /></td>
                                <td><?php echo $registro->getCod(); ?></td>
                                <td><?php echo $registro->getPalavra(); ?></td>
                                <td><?php echo $registro->getUrl(); ?></td>
                                <td><?php echo $registro->getCount(); ?></td>
                                
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-primary" href="editar.php?cod=<?php echo $registro->getCod(); ?>"><i class="fa fa-pencil"></i> <?php echo __('editar'); ?></a>
                                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="deletar.php?cod=<?php echo $registro->getCod(); ?>"><i class="fa fa-trash-o"></i> <?php echo __('deletar'); ?></a></li>
                                        </ul>
                                    </div>  
                                </td>
                            </tr>
                            <?php endforeach; ?>
                    </table>
                
                    <div class="multi-actions">
                        <label class="text-muted"><input type="checkbox" id="multi_all" /> Selecionar/Desselecionar</label>
                        <button type="submit" class="btn btn-xs" id="multi_submit">Deletar Selecionados</button>
                    </div>
                </form>
                <?php else : ?>
                    Não existem registros.
                <?php endif; ?>
                
                <?php echo $pager->getPager(); ?>
                
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>
