<~?php
//REQUIRE E CONEXÃO
[..]

//CONEXÃO E VALORES
$registro = new obj...($Conexao, true);
$registro->loadByCod($_GET["cod"]);
$cancelLink = "index.php?<?php echo $relacao;?>=" . $registro->get<?php echo $relacaoU;?>();
