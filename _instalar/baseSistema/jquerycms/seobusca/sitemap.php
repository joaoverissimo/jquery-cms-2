<?php
require_once '../config.php';
header("Content-Type:text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
    <?php
    $palavras = dbJqueryseopalavra::ObjsList($Conexao);
    if ($palavras) :
        foreach ($palavras as $palavra) :
            ?>

            <url>
                <loc><?php echo $palavra->getRewriteUrl(true); ?></loc>
                <lastmod><?php echo date('c', time()); ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.5</priority>
            </url>

            <?php
        endforeach;
    endif;
    ?>

</urlset>