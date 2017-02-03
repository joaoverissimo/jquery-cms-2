<?php

class mailJqueryadminuser {

    public static function enviarAlterarSenha(objJqueryadminuser $obj, $arr = array()) {
        $template = ___AppRoot . "jquerycms/emailClass/jqueryadminuser/template-alterar-senha.php";

        //Abre, captura e limpa buffer de saida
        ob_start();
        include $template;
        $mensagemHTML = ob_get_clean();

        $assunto = ___sisTitulo . " - Alteracao de senha";
        $para = $obj->getMail();

        return Fncs_EnviarEmailAwsSes($para, $mensagemHTML, $assunto);
    }

    public static function enviarBoasVindas(objJqueryadminuser $obj, $arr = array()) {
        $template = ___AppRoot . "jquerycms/emailClass/jqueryadminuser/template-boas-vindas.php";

        //Abre, captura e limpa buffer de saida
        ob_start();
        include $template;
        $mensagemHTML = ob_get_clean();

        $assunto = ___sisTitulo . " - Dados para acesso";
        $para = $obj->getMail();

        return Fncs_EnviarEmailAwsSes($para, $mensagemHTML, $assunto);
    }

    public static function enviarEsqueciSenha(objJqueryadminuser $obj, $arr = array()) {
        $template = ___AppRoot . "jquerycms/emailClass/jqueryadminuser/template-esqueci-senha.php";

        //Abre, captura e limpa buffer de saida
        ob_start();
        include $template;
        $mensagemHTML = ob_get_clean();

        $assunto = ___sisTitulo . " - Recuperar senha";
        $para = $obj->getMail();

        return Fncs_EnviarEmailAwsSes($para, $mensagemHTML, $assunto);
    }

}
