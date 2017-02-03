<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";
Fncs_ValidaQueryString("cod", "index.php");

//CONEXÃO E VALORES
$registro = new objJqueryadmingrupo($Conexao, true);
$registro->loadByCod($_GET["cod"]);

//POST
if (count($_POST) > 0) {

    try {

        $where = new dataFilter("jqueryadmingrupo2menu.jqueryadmingrupo", $registro->getCod());
        dbJqueryadmingrupo2menu::DeletarWhere($Conexao, $where);
        if (isset($_POST['permissoes']) && $_POST['permissoes']) {
            $permissoes = explode(",", $_POST['permissoes']);

            foreach ($permissoes as $jqueryadminmenu) {
                $obj = new objJqueryadmingrupo2menu($Conexao);
                $obj->setJqueryadmingrupo($registro->getCod());
                $obj->setJqueryadminmenu($jqueryadminmenu);
                $obj->Save();
            }
        }

        $exec = true;
        if ($exec && $adm_tema != 'branco') {
            $msg = fEnd_MsgString("O registro foi salvo com sucesso.", 'success');
        } else {
            $msg = fEnd_MsgString("O registro foi inserido." . fEnd_closeTheIFrameImDone('jqueryadmingrupo', $registro->getCod()), 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

$template = array(
    "htmlstart" => "<ul class='#ulclass#'>\n",
    "htmlend" => "</ul>",
    "li" => "\n\t<li id='getCod' data-titulo='getTitulo'><label><span>getTitulo</span></label></li>",
    "lisub" => "\n\t<li id='getCod' data-titulo='getTitulo' data-jstree='{ \"opened\" : true }'><label><span>getTitulo</span></label>#getmenu#</li>",
    "nivelclass" => array(
        0 => "treecheckbox",
        1 => ""
    )
);



$where = new dataFilter("jqueryadmingrupo2menu.jqueryadmingrupo", $registro->getCod());
$permissoes = dbJqueryadmingrupo2menu::ObjsListLeft($Conexao, $where);

//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->fieldset("Permissões Ativas");
$form->insertHtml("<input type='hidden' id='permissoes' name='permissoes'></input>");
$form->insertHtml("<div id='tree' class='jstree-open'>" . dbJqueryadminmenu::getMenu($Conexao, $adm_folder, 0, 0, $template)) . "</div>";
$form->fieldsetOut();

$form->send_cancel("Salvar", $cancelLink);
$form->end();


$pageVars = array('pageTitle' => __('table_jqueryadmingrupo'), 'pageAction' => "Permissões de {$registro->getTitulo()}", "nav-breadcrumbs" => array(__('table_jqueryadmingrupo') => "index.php"));
?><!doctype html>
<html class="fixed sidebar-left-xs">
    <head>
        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>

        <script type="text/javascript" src="/jquerycms/js/jstree/jstree.js"></script>
        <link rel="stylesheet" type="text/css" href="/jquerycms/js/jstree/jstree.css"/>
        <script>
            $(document).ready(function () {
                $('#tree').jstree({
                    "core": {
                        "themes": {
                            "variant": "large"
                        }
                    },
                    "plugins": ["checkbox"]
                });

<?php if ($permissoes) : ?>
    <?php foreach ($permissoes as $obj) : ?>
        <?php if (!$obj->objJqueryadminmenu()->obtemJqueryadminmenuRel()): ?>
                            $("#tree").jstree("select_node", "#<?php echo $obj->objJqueryadminmenu()->getCod(); ?>");
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($currentUser->getGrupo() != 1) : ?>
                    $("li[data-titulo='Jquerycms']").hide();
<?php endif; ?>

                $("#cadastro").submit(function () {
                    var checked_ids = $('#tree').jstree('get_selected');
                    $("#tree").find(".jstree-undetermined").each(function (i, element) {
                        checked_ids.push($(element).closest("li").attr("id"));
                    });

                    $("#permissoes").val(checked_ids);
                });

            });
        </script>
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
                            <section class="panel main-panel-pg">
                                <?php include '../lib/masterpage/panel-header.php'; ?>

                                <div class="panel-body">
                                    <?php echo $msg; ?>
                                    <?php echo $form->getForm(); ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>
            </div>

            <?php include '../lib/masterpage/footer.php'; ?>
        </section>
    </body>
</html>