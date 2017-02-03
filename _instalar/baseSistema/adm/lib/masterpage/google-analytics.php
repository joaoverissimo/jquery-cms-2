<?php
if (!isset($googleAnalyticsInstancia)) {
    throw new jquerycmsException("Erro ao inserir o código do google analytics.");
}
?>

<?php if (saas_getHost() != "wsoft") : ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-91025672-1', 'auto');

    <?php if ($googleAnalyticsInstancia == "web-cliente" && freeImobVariables::siteAnalytics()) : ?>
            ga('create', '<?php echo freeImobVariables::siteAnalytics(); ?>', 'auto', 'clientTracker');
    <?php endif; ?>

        ga('set', 'dimension1', '<?php echo @saas_getCliente(); ?>');
        ga('set', 'dimension2', '<?php echo @saas_getVersao($Conexao); ?>');
    <?php if (isset($currentUser) && $currentUser instanceof objJqueryadminuser) : ?>
            ga('set', 'dimension3', '<?php echo @$currentUser->getMail(); ?>');
            ga('set', 'dimension4', '<?php echo @$currentUser->objGrupo()->getTitulo(); ?>');
    <?php endif; ?>
        ga('set', 'dimension5', '<?php echo $googleAnalyticsInstancia; ?>');

        ga('send', 'pageview');

    <?php if ($googleAnalyticsInstancia == "web-cliente" && freeImobVariables::siteAnalytics()): ?>
            ga('clientTracker.send', 'pageview');
    <?php endif; ?>
    </script>


    <?php if ($googleAnalyticsInstancia == "web-cliente"): ?>
        <!-- Hotjar Tracking Code for http://maite.floripalancamentos.com.br/ -->
        <script>
            (function (h, o, t, j, a, r) {
                h.hj = h.hj || function () {
                    (h.hj.q = h.hj.q || []).push(arguments)
                };
                h._hjSettings = {hjid: 397322, hjsv: 5};
                a = o.getElementsByTagName('head')[0];
                r = o.createElement('script');
                r.async = 1;
                r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
                a.appendChild(r);
            })(window, document, '//static.hotjar.com/c/hotjar-', '.js?sv=');
        </script>
    <?php endif; ?>
<?php endif; ?>