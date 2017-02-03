<?php
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";
/* @var $currentUser objJqueryadminuser */

$registro = new objJqueryadminuser($Conexao, true);
$registro->loadByCod($currentUser->getCod());

//POST
if (count($_POST) > 0) {
    $registro->setNome(issetpost('nome'));
    $registro->setMail(issetpost('mail'));
    $registro->setSenha(issetpost('senha'));

    try {
        $exec = $registro->Save();

        if ($exec && $adm_tema != 'branco') {
            header("Location: index.php");
        } else {
            $msg = fEnd_MsgString("O registro foi salvo." . fEnd_closeTheIFrameImDone('jqueryadminuser', $registro->getCod()), 'success');
        }
    } catch (jquerycmsException $exc) {
        $msg = fEnd_MsgString("Ocorreram problemas ao inserir o registro.", 'error', $exc->getMessage());
    }
}

//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->text(__('jqueryadminuser.nome'), 'nome', $registro->getNome(), 1);
$form->text(__('jqueryadminuser.mail'), 'mail', $registro->getMail(), 1);
$form->textPassword(__('jqueryadminuser.senha'), 'senha', $registro->getSenha(), 1);

$form->send_cancel("Salvar", $cancelLink);
$form->end();

$pageVars = array('pageTitle' => "Minha conta", 'pageAction' => "Editar", "nav-breadcrumbs" => array());
?><!doctype html>
<html class="fixed sidebar-left-xs">
    <head>
        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>
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
                            <section class="panel">
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