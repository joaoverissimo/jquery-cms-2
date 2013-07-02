<~?php
//REQUIRE E CONEXÃO
[..]

$registro = new obj...($Conexao);
if (isset($_GET['<?php echo $relacao;?>']) && $_GET['<?php echo $relacao;?>']) {
    $registro->set<?php echo $relacaoU;?>($_GET['<?php echo $relacao;?>']);
    $cancelLink = "index.php?<?php echo $relacao;?>={$_GET['<?php echo $relacao;?>']}";
}

[..]

//POST
if (count($_POST) > 0) {
    try {
        if (!$registro->get<?php echo $relacaoU;?>()) {
            $registro->set<?php echo $relacaoU;?>(issetpostInteger('<?php echo $relacao;?>'));
        }

[..]

//FORM
if (!$registro->get<?php echo $relacaoU;?>()) {
    $form->selectDb(__('<?php echo $detalhe; ?>.<?php echo $relacao; ?>'), '<?php echo $relacao; ?>', $registro->get<?php echo $relacaoU; ?>(), 0, $Conexao, '<?php echo $master; ?>', '<?php echo $master_primaryKey; ?>', '<?php echo $master_campo2; ?>');
}