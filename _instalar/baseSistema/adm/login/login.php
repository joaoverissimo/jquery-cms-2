<?php
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

//SETUP
$mail = "";

//POST
if (isset($_REQUEST['mail']) && isset($_REQUEST['senha'])) {
    $mail = isset($_REQUEST['mail']) && $_REQUEST['mail'] ? $_REQUEST['mail'] : "";
    $senha = isset($_REQUEST['senha']) && $_REQUEST['senha'] ? $_REQUEST['senha'] : "";

    try {
        if (dbJqueryadminuser::auth($Conexao, $mail, $senha)) {
            if (isset($_GET["redirect"])) {
                header("Location: {$_GET["redirect"]}");
            } else {
                header("Location: $adm_folder/home/index.php");
            }
            exit();
        } else {
            $msg = fEnd_MsgString("Login ou senha inválidos.", 'error');
        }
    } catch (Exception $exc) {
        $msg = $exc->getTraceAsString();
    }
}

if (isset($_REQUEST['mail-recuperar'])) {
    $mail = isset($_REQUEST['mail-recuperar']) && $_REQUEST['mail-recuperar'] ? $_REQUEST['mail-recuperar'] : "";

    try {
        $obj = new objJqueryadminuser($Conexao, false);
        if ($obj->loadByCod($mail, "mail")) {
            mailJqueryadminuser::enviarEsqueciSenha($obj);
            $msg = fEnd_MsgString("Dados de acesso enviados por e-mail.", 'success');
        } else {
            $msg = fEnd_MsgString("E-mail não está cadastrado.", 'error');
        }
    } catch (Exception $exc) {
        $msg = $exc->getTraceAsString();
    }
}

//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->text(__('jqueryadminuser.mail'), 'mail', $mail, 4);
$form->textPassword(__('jqueryadminuser.senha'), 'senha', "", 1);

$form->send_cancel("Login", "/", array('class' => 'btn-danger'));
$form->end();


$pageVars = array('pageTitle' => "Login", 'pageAction' => "Listar", "nav-breadcrumbs" => array());
?><!doctype html>
<html class="fixed sidebar-left-xs">
    <head>
        <?php include '../lib/masterpage/head.php'; ?>
        <?php echo $form->getHead(); ?>

        <script>
            $(document).ready(function () {
                $("#btn-esqueceu-senha").click(function () {
                    $("#form-entrar").hide();
                    $("#form-recuperar").show();
                    var email = $("#mail").val();
                    $("#mail-recuperar").val(email);
                });

                $("#btn-esqueceu-voltar").click(function () {
                    $("#form-entrar").show();
                    $("#form-recuperar").hide();
                    var email = $("#mail-recuperar").val();
                    $("#mail").val(email);
                });
            });
        </script>
    </head>
    <body class="page-no-nav"> 
        <!-- start: page -->
        <section class="body-sign">
            <div class="center-sign">
                <!--pull-left-->
                <?php include '../lib/masterpage/logo.php'; ?>

                <div class="panel panel-sign">
                    <div class="panel-title-sign mt-xl text-right">
                        <h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> Entrar</h2>
                    </div>
                    <div class="panel-body">
                        <form method="post" id="form-entrar">
                            <?php echo $msg; ?>

                            <div class="form-group mb-lg">
                                <label>E-mail</label>
                                <div class="input-group input-group-icon">
                                    <input name="mail" id="mail" type="text" value="<?php echo $mail; ?>" class="form-control input-lg" />
                                    <span class="input-group-addon">
                                        <span class="icon icon-lg">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group mb-lg">
                                <div class="clearfix">
                                    <label class="pull-left">Senha</label>
                                    <a href="#" class="pull-right" id="btn-esqueceu-senha">Esqueceu sua senha?</a>
                                </div>
                                <div class="input-group input-group-icon">
                                    <input name="senha" type="password" id="senha" class="form-control input-lg" />
                                    <span class="input-group-addon">
                                        <span class="icon icon-lg">
                                            <i class="fa fa-lock"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary hidden-xs">Entrar</button>
                                    <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Entrar</button>
                                </div>
                            </div>
                        </form>
                        <form method="post" id="form-recuperar" style="display: none">

                            <?php echo $msg; ?>

                            <div class="form-group mb-lg">
                                <label>E-mail</label>
                                <div class="input-group input-group-icon">
                                    <input name="mail-recuperar" id="mail-recuperar" type="text" value="<?php echo $mail; ?>" class="form-control input-lg" />
                                    <span class="input-group-addon">
                                        <span class="icon icon-lg">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary hidden-xs">Recuperar senha</button>
                                    <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Recuperar senha</button>
                                    <br />
                                    <a href="#" class="pull-right" id="btn-esqueceu-voltar">Voltar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>

        <?php
        $googleAnalyticsInstancia = "admin";
        require_once ___AppRoot . '/adm/lib/masterpage/google-analytics.php';
        ?>

    </body>
</html>