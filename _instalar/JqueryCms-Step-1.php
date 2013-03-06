<?php

require_once 'lib/BootStrapSistema.php';
include "_instalar_html/header.html";
?>


<h1>Bem vindo ao Jquery Cms</h1>

<p>Este assistente irá conduzi-lo durante a instalação.</p>
<p>Você já deve ter seu banco de dados criado, com suas respectivas tabelas e relacionamentos. Não se preocupe com o caso de ter de criar mais tabelas no futuro, basta voltar a este assistente e criar apenas as tabelas novas.</p>
<p class="small"><b>Nota de Instalação 1: </b>este instalador irá criar arquivos na pasta raiz. Por isso é necessário alterar as permissões da pasta principal de seu site.</p>
<p class="small"><b>Nota de Instalação 2: </b>dentro da pasta "_instalar" existe o arquivo "<b>baseSistema.zip</b>". Se por algum motivo as extenções de suporte a arquivos .zip estiverem desativadas, você pode extrair o arquivo manualmente.</p>

<a href='JqueryCms-Step-2.php' class='btn avancar'>Avançar</a>
<?php
include "_instalar_html/footer.html";
?>