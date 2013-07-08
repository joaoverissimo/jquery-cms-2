<?php
require_once '../../jquerycms/config.php';
$ctrl = new CtrlLocalizacaoV2($Conexao);

if (count($_POST) > 0) {
    $exec = $ctrl->SaveByPost();
    echo "O registro inserido na tabela localizacao foi: <a href='exemplo_v2.php?cod=$exec'>{$ctrl->registro->getCod()}</a>";
}


if (isset($_GET['cod'])) {
    $ctrl->loadByCod($_GET['cod']);
} else {
    //$ctrl->loadByParams(88054450, 24, 8452, 13932, "Heityor", 383, "bloco b, 102");
}

//$ctrl->estado = 24;
//$ctrl->cidade = 8452;
//$ctrl->bairro = 13932;
//$ctrl->rua = "Rua Heitor Bittencourt";
//$ctrl->numero= "383";
//$ctrl->complemento = "bloco b, 102";
//$ctrl->cep = "88054450";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include_once '../../masterpage/head.php'; ?>
        <?php echo $ctrl->getHead(); ?>
        <link rel='stylesheet' type='text/css' href='/jquerycms/js/autoform.css' />
        <script>
            $(document).ready(function(){
                $("#cadastro").validate({
                    highlight: function(label) {
                        $(label).siblings('.help-inline').removeClass('help-inline').addClass('help-block');
                        $(label).closest('.control-group').addClass('error');
                    },
                    success: function(label) {
                        label.closest('.control-group').removeClass('error');
                    }
                });
            });
        </script>
    </head>
    <body>
        <h1>Controle localização v2 - 88054-450</h1>
        <form method="post" id="cadastro">
            <?php echo $ctrl->getCtrl(); ?>
            <button type="submit">Enviar</button>
        </form>
        <button onclick='$("#ctrl_loc_cep").val("88054000").change();'>Define cep</button>
    </body>
</html>
