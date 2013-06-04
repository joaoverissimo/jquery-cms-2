<?php
require_once 'jquerycms/config.php';
$cache = new SimpleCachePhp(__FILE__);

$orderBy = new dataOrder("jqueryseotabela.ordem");
$dados = dbJqueryseotabela::ObjsListLeft($Conexao, '', $orderBy);

$palavra = false;
$where = "0 = 1";
if (isset($_GET['palavra']) && $_GET['palavra']) {
    $palavra = $_GET['palavra'];
    $palavra = mysql_real_escape_string($palavra);
    $palavrastr = $palavra;
    $where = "palavra LIKE '%$palavra%'";
	
	$objPalavra = new objJqueryseopalavra($Conexao, false);
    if ($objPalavra->loadByCod(toRewriteString($palavra), 'url')) {
        header("Location: " . $objPalavra->getRewriteUrl());
    }
}

if (isset($_GET['url']) && $_GET['url']) {
    $palavra = $_GET['url'];
    $palavra = mysql_real_escape_string($palavra);
    $objpalavra = new objJqueryseopalavra($Conexao, false);
    $objpalavra->loadByCod($_GET['url'], 'url');
    $palavrastr = $objpalavra->getPalavra();
    $where = "url = '$palavra'";
}

if ($palavra && !$palavrastr) {
    $palavrastr = $palavra;
}
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryseotabela'); ?> - Listar</title>

        <?php include 'masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include 'masterpage/header.php'; ?>

        <div class="container" style="margin-top: 50px;">
            <section id="lista">
                <div class="page-header">
                    <?php if (isset($_GET['palavra'])) : ?>
                        <h1>Busca por <?php echo $palavrastr; ?></h1>
                    <?php else : ?>
                        <h1><?php echo $palavrastr; ?></h1>
                    <?php endif; ?>
                </div>

                <h4>Sua pesquisa:</h4>
                <?php echo CtrlJquerySeoBusca::getCtrl('busca', $palavrastr); ?>

                <?php
                if ($dados === false) :
                    echo "Não existem tabelas associadas ao obj seo (tabela jqueryseotabela).";
                else :
                    ?>
                    <?php if (!$palavra) : ?>
                        Nenhum resultado para sua pesquisa.
                        <?php
                    else :
                        foreach ($dados as $objtabela) :
                            $sql = "SELECT {$objtabela->getTabela()}.* FROM `{$objtabela->getTabela()}` LEFT JOIN jqueryseo ON jqueryseo.cod = `{$objtabela->getTabela()}`.seo WHERE seo IN (SELECT jqueryseorel.seo FROM jqueryseorel LEFT JOIN jqueryseopalavra ON jqueryseopalavra.cod = jqueryseorel.palavra WHERE jqueryseorel.palavra IN (SELECT cod FROM jqueryseopalavra WHERE $where))"; // OR jqueryseo.titulo LIKE '%$palavrastr%'
                            $resultados = dataExecSqlDireto($Conexao, $sql);
                            if (issetArray($resultados)) :
                                ?>

                                <h3><?php echo $objtabela->getTitulo(); ?></h3>
                                <ul class="seo_resultados">
                                    <?php
                                    foreach ($resultados as $resultado) {
                                        include "jquerycms/seobusca/inc-{$objtabela->getTabela()}.php";
                                    }
                                    ?>
                                </ul>
                                <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                <?php endif; ?>

                <script>
                    jQuery.fn.highlight = function (str, className) {
                        var regex = new RegExp(str, "g");

                        return this.each(function () {
                            this.innerHTML = this.innerHTML.replace(regex, "<span class=\"" + className + "\">" + str + "</span>");
                        });
                    };

                    $(document).ready(function(){
                        $('.seo_resultados .seobuca_keys').highlight('<?php echo $palavrastr; ?>', 'seobuca_destaque');
                        if ($(".seo_resultados li").length == 0) {
                            $("#seobuca_form").after('<p>Nenhum resultado foi encontrado.</p>');
                        }
                    });
                </script>
                <style>
                    .seobuca_destaque {background: rgb(255, 253, 211);}
                    .seobuca_keys {font-size: small;}
                </style>

            </section>

            <?php include 'masterpage/footer.php'; ?>
        </div>
    </body>
</html>
<?php $cache->CacheEnd(); ?>