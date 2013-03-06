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
        foreach ($_POST as $jqueryadminmenu => $value) {
            $obj = new objJqueryadmingrupo2menu($Conexao);
            $obj->setJqueryadmingrupo($registro->getCod());
            $obj->setJqueryadminmenu($jqueryadminmenu);
            $obj->Save();
        }

        $exec = true;
        if ($exec && $adm_tema != 'branco') {
            header("Location: index.php");
        } else {
            $msg = fEnd_MsgString("O registro foi inserido.$fEnd_closeTheIFrameImDone", 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

$template = array(
    "htmlstart" => "<ul class='#ulclass#'>\n",
    "htmlend" => "</ul>",
    "li" => "\n\t<li><label><input type='checkbox' id='permissao_getCod' name='getCod'> <span>getTitulo</span></label></li>",
    "lisub" => "\n\t<li><label><input type='checkbox' id='permissao_getCod' name='getCod'> <span>getTitulo</span></label>#getmenu#</li>",
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
$form->insertHtml(dbJqueryadminmenu::getMenu($Conexao, $adm_folder, 0, 0, $template));


$form->send_cancel("Salvar", $cancelLink);
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryadmingrupo'); ?> - Editar</title>

        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>

        <script type="text/javascript" src="/jquerycms/js/jquery-tree/jquery.tree.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/jquerycms/js/jquery-tree/jquery.tree.min.css"/>
        <script>
            $(document).ready(function(){
                $('.treecheckbox').tree({ components: [/*'ajax'*/, 'checkbox', 'collapse', /*'contextmenu'*/, 'dnd', 'select'] });
                
<?php if ($permissoes) : foreach ($permissoes as $obj) : ?>
                                        
                $('#permissao_<?php echo $obj->objJqueryadminmenu()->getCod(); ?>').attr('checked','checked');
                                    
    <?php endforeach;
endif;
?>
    });
        </script>
    </head>
    <body>        
<?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryadmingrupo'); ?> <small>Permissões de <?php echo $registro->getTitulo(); ?></small></h3>
                </div>

                <?php echo $msg; ?>
<?php echo $form->getForm(); ?>
            </div>
        </div>
<?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>