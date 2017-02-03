<?php
//REQUIRE e VALIDA PÁGINA
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';

$msg = "";

//CONEXÃO E VALORES

   
//POST
if (count($_POST) > 0) {
    if (issetArray($_POST['multi'])) {
        foreach ($_POST['multi'] as $cod) {
            try {
                $registro = new objJqueryseo($Conexao, true);
                $registro->loadByCod($cod);
                $registro->Delete();
                $msg .= fEnd_MsgString("O registro #$cod foi excluido com sucesso." . fEnd_closeTheIFrameImDone('jqueryseo', $registro->getCod()), 'success');
            } catch (jquerycmsException $exc) {
                $msg .= fEnd_MsgString("Ocorreram problemas ao deletar o registro #$cod.", 'warning', $exc->getMessage());
            }
        }
    }
}
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryseo');?> - Deletar</title>

        <?php include '../lib/masterpage/head.php'; ?>
    </head>
    <body>        
        <?php include '../lib/masterpage/header.php'; ?>

        <div class="main">
            <div class="inner">
                <div class="page-header">
                    <h3><?php echo __('table_jqueryseo');?> <small>Deletar Selecionados</small></h3>
                </div>
                
                <div>
                <?php 
                    if ($msg) {
                        echo $msg; 
                    } else {
                        echo "Nenhum registro selecionado";
                    }
                ?>
                </div>
                
                <a href="index.php" class="btn btn-primary"><i class="icon-arrow-left icon-white"></i> Voltar</a>
            </div>
        </div>
        <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>