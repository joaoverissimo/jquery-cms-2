<?php /* @var $obj objJqueryadminuser */?><!doctype html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Boas vindas <?php echo ___sisTitulo; ?></h1>
        <p>Olá <?php echo $obj->getNome(); ?>, como vai?</p>
        <p>Seu cadastro no sistema <?php echo ___sisTitulo; ?> foi realizado com sucesso. A seguir seus dados de acesso:</p>
        <div style="padding: 20px; border: 1px solid #ccc;margin-bottom: 15px;">
            <p><b>E-mail:</b> <?php echo $obj->getMail(); ?></p>
            <p><b>Senha:</b> <?php echo $obj->getSenha(); ?></p>
            <p><b>Acesse em:</b> <a href="<?php echo ___siteUrl; ?>adm/" target="_blank"><?php echo str_replace("www", "", str_replace("http://", "", ___siteUrl)); ?>adm/</a></p>
        </div>
        <p>
            Atenciosamente, <br />
            Equipe <?php echo ___sisTitulo; ?>
        </p>
    </body>
</html>