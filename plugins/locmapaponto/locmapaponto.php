<?php
require_once 'jquerycms/config.php';

//order
$orderbyfield = "locmapaponto.cod";
$orderbyvalue = "asc";

if (isset($_REQUEST['orderbyfield']))
    $orderbyfield = $_REQUEST['orderbyfield'];

if (isset($_REQUEST['orderbyvalue']))
    $orderbyvalue = $_REQUEST['orderbyvalue'];

$orderBy = new dataOrder($orderbyfield, $orderbyvalue);

//Where
$where = "";

if (isset($_REQUEST['cod'])) {
    $where = new dataFilter("locmapaponto.cod", $_REQUEST['cod']);
}

if (isset($_REQUEST['lat'])) {
    $where = new dataFilter("locmapaponto.lat", $_REQUEST['lat'], dataFilter::op_likeMatches);
}

//Limit & pager
$paginaAtual = 0;
if (isset($_REQUEST['page']))
    $paginaAtual = $_REQUEST['page'];

$pager = new dataPager($paginaAtual, 15, $Conexao, 'locmapaponto', $where);

//Obtem a lista
$dados = dbLocmapaponto::ObjsListLeft($Conexao, $where, $orderBy, $pager->getLimit());
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_locmapaponto'); ?> - Listar</title>

        <?php include 'masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include 'masterpage/header.php'; ?>

        <div class="container" style="margin-top: 50px;">
            <section id="lista">
                <div class="page-header">
                    <h1><?php echo __('table_locmapaponto'); ?> <small>Listar</small></h1>
                </div>


                <?php
                    $obj = new objLocmapaponto($Conexao);
                    $obj->loadByCod(5);
                    
                    echo "<h1>Mapa Ponto</h1>";
                    echo $obj->getGmapsSiples("teste");
                    echo "<h1>Mapa View</h1>";
                    echo $obj->getGmapsStreetView("200px", false);
                ?>



                <?php
                if ($dados === false) :
                    echo "Não existem registros.";
                else :
                    ?>

                    <ul class="jquerylista">
                        <?php foreach ($dados as $obj) : ?>
                            <li>
                                <p><?php echo __('locmapaponto.cod'); ?> <span><?php echo $obj->getCod(); ?></span></p>
                                <p><?php echo __('locmapaponto.lat'); ?> <span><?php echo $obj->getLat(); ?></span></p>
                                <p><?php echo __('locmapaponto.lng'); ?> <span><?php echo $obj->getLng(); ?></span></p>
                                <p><?php echo __('locmapaponto.heading'); ?> <span><?php echo $obj->getHeading(); ?></span></p>
                                <p><?php echo __('locmapaponto.pitch'); ?> <span><?php echo $obj->getPitch(); ?></span></p>
                                <p><?php echo __('locmapaponto.zoom'); ?> <span><?php echo $obj->getZoom(); ?></span></p>
                                <p><?php echo __('locmapaponto.comportamento'); ?> <span><?php echo $obj->getComportamento(); ?></span></p>
                                <a href="locmapaponto-detalhe.php?cod=<?php echo $obj->getCod(); ?>" class="btn btn-mini">(+) Detalhes</a>
                            </li>                            
                        <?php endforeach; ?>
                    </ul>

                    <div class="clearfix"></div>
                <?php endif; ?>


                <?php echo $pager->getPager(); ?>

            </section>

            <?php include 'masterpage/footer.php'; ?>
        </div>
    </body>
</html>