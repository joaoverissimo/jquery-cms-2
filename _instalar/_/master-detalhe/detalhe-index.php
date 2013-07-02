<~?php
//REQUIRE E CONEXÃO
[..]

//Filtra a lista
[..]

$<?php echo $relacao; ?> = "";
if (isset($_GET['<?php echo $relacao; ?>']) && $_GET['<?php echo $relacao; ?>']) {
$<?php echo $relacao; ?> = $_GET['<?php echo $relacao; ?>'];
}

//Lista os dados
$where = $where = new dataFilter(db<?php echo $detalheU; ?>::_<?php echo $detalhe_primaryKey; ?>, 0, dataFilter::op_different);
if ($filtraList) {
$where->add(db<?php echo $detalheU; ?>::_detalhe_campo2, $filtraList, dataFilter::op_likeMatches);
}

if ($<?php echo $relacao; ?>) {
$where->add(db<?php echo $detalheU; ?>::_<?php echo $relacao; ?>, $<?php echo $relacao; ?>);
}

[..]

$form = new autoform2();
$form->selectDb(__('<?php echo $detalhe; ?>.<?php echo $relacao; ?>'), '<?php echo $relacao; ?>', $<?php echo $relacao; ?>, 0, $Conexao, '<?php echo $master; ?>', '<?php echo $master_primaryKey; ?>', '<?php echo $master_campo2; ?>', '','','Indiferente');
$form->end();

[..]

<div class="page-header">
    <h3><~?php echo $<?php echo $relacao; ?> ? obj<?php echo $masterU; ?>::init($<?php echo $relacao; ?>)->get<?php echo $master_campo2U; ?>() . " - " : ""; ?><~?php echo __('table_<?php echo $detalhe; ?>'); ?> <small>Listar</small></h3>
</div>

<div class="btn-toolbar">
    <a href="../<?php echo $master;?>/index.php" class="btn"><i class="icon-arrow-left"></i> <~?php echo __('table_<?php echo $master;?>'); ?~></a>
    <a href="inserir.php<~?php echo $<?php echo $relacao;?> ? "?<?php echo $relacao;?>=$<?php echo $relacao;?>" : ''; ?>" class="btn btn-primary"><i class="icon-plus icon-white"></i> Inserir</a>
                