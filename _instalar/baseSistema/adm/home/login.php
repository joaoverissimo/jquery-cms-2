<?php
require_once '../../jquerycms/config.php';
require_once '../lib/admin.php';
$msg = "";

//SETUP
$mail = "";

//POST
if (count($_POST) > 0) {
    $mail = issetpost("mail");
    $senha = issetpost("senha");

    try {
        if (dbJqueryadminuser::auth($Conexao, $mail, $senha)) {
            if(isset($_GET["redirect"])) {
                header("Location: {$_GET["redirect"]}");
            } else {
                header("Location: index.php");
            }            
            exit();
        } else {
            $msg = fEnd_MsgString("Login ou senha inválidos.", 'error');
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}

//FORM
$form = new autoform2();
$form->start("cadastro", "", "POST");

$form->text(__('jqueryadminuser.mail'), 'mail', $mail, 4);
$form->textPassword(__('jqueryadminuser.senha'), 'senha', "", 1);

$form->send_cancel("Login", "/", array('class' => 'btn-danger'));
$form->end();
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo __('table_jqueryadminuser'); ?> - Listar</title>

<?php include '../lib/masterpage/head.php'; ?>
<?php echo $form->getHead(); ?>

        <script>
            $(document).ready(function(){
                $(".navbar").hide(); 
                $(".actions").hide(); 
                $(".inner h1").css('text-align', 'center'); 
            });
        </script>
    </head>
    <body>        
<?php include '../lib/masterpage/header.php'; ?>

        <div class="login">
        <?php echo $msg; ?>
<?php echo $form->getForm(); ?>
        </div>

            <?php include '../lib/masterpage/footer.php'; ?>
    </body>
</html>
