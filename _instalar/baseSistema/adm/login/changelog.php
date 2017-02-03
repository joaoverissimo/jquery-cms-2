<?php
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

$dadosVersoes = dataExecSqlDireto($Conexao, "SELECT cod, versao, tipo, titulo, link, data FROM zchangelog WHERE 1=1 ORDER BY versao DESC");
$versoes = array();

if (issetArray($dadosVersoes)) {
    foreach ($dadosVersoes as $registro) {
        $versoes[$registro['versao']][] = $registro;
    }
}

$pageVars = array('pageTitle' => "Histórico de versões", 'pageAction' => "Listar", "nav-breadcrumbs" => array());
?><!doctype html>
<html class="fixed sidebar-left-xs">
    <head>
        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body> 
        <section class="body">
            <?php include '../lib/masterpage/header.php'; ?>

            <div class="inner-wrapper">
                <?php include '../lib/masterpage/navbar.php'; ?>

                <section role="main" class="content-body">
                    <?php include '../lib/masterpage/page-header.php'; ?>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="timeline timeline-simple changelog">
                                <div class="tm-body">
                                    <ol class="tm-items">
                                        <?php if (issetArray($versoes)): ?>
                                            <?php foreach ($versoes as $versao) : ?>
                                                <li>
                                                    <div class="tm-box">
                                                        <h4><?php echo $versao[0]['versao']; ?></h4> – <span class="release-date"><?php echo Fncs_LerData($versao[0]['data']); ?></span>
                                                        <ul class="list-unstyled">
                                                            <?php if (issetArray($versao)) : ?>
                                                                <?php foreach ($versao as $versaoInfo) : ?>
                                                                    <li>
                                                                        <?php
                                                                        if ($versaoInfo['tipo'] == "I") {
                                                                            echo '<span class="label label-success">Adicionado</span>';
                                                                        } elseif ($versaoInfo['tipo'] == "A") {
                                                                            echo '<span class="label label-warning">Atualizado</span>';
                                                                        } elseif ($versaoInfo['tipo'] == "C") {
                                                                            echo '<span class="label label-danger">Correção</span>';
                                                                        }

                                                                        echo " - ";

                                                                        if ($versaoInfo['link']) {
                                                                            echo "<a href='{$versaoInfo['link']}' target='_blank'>{$versaoInfo['titulo']}</a>";
                                                                        } else {
                                                                            echo $versaoInfo['titulo'];
                                                                        }
                                                                        ?>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>
                                                <div class="tm-box">
                                                    <h4>1.0.0</h4> – <span class="release-date">Sem registro de versões</span>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ol>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>

            <?php include '../lib/masterpage/footer.php'; ?>
        </section>
    </body>
</html>