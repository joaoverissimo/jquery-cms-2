<?php

/* Trabalhar com os seguintes parametros:
 * <img src="img_simple.php?img=15&o=w&i=150" />
 * img: id da imagem
 * o: orientacao da imagem (w paisagem, h retrato)
 * i: px ajustados a orientacao
 */
require_once 'jquerycms/config.php';
$Conexao = CarregarConexao();

$obj = new objJqueryimage($Conexao);
$obj->loadByCod($_GET["img"]);

header('Content-Type: image/jpeg');
$image = new SimpleImage();
$image->load($obj->getFileName());

if ($image->getWidth() > $image->getHeight()) {
    //Se for retrato
    $image->resizeToWidth($_GET["width"]);
} else {
    //Se for paisagem
    $image->resizeToHeight($_GET["height"]);
}

$image->output();