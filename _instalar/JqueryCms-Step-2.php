<?php
require_once 'lib/BootStrapSistema.php';
include "_instalar_html/header.html";

$filenameConfig = obterDocumentRoot() . "_instalar/_config.php";

if (count($_POST) > 0) {
    $txtMySqlDataServer = $_POST["txtMySqlDataServer"];
    $txtMySqlDataUser = $_POST["txtMySqlDataUser"];
    $txtMySqlDataPass = $_POST["txtMySqlDataPass"];
    $txtMysqlDataDb = $_POST["txtMysqlDataDb"];
    $txtUrl = $_POST['txtUrl'];
    $txtAdmLogin = $_POST['txtAdmLogin'];
    $txtAdmPass = $_POST['txtAdmPass'];

    if (empty($txtMySqlDataServer) || empty($txtMySqlDataUser) || empty($txtMysqlDataDb)) {
        echo "<h1>Dados invalidos!</h1> É necessario preencher Servidor, Usuario e Nome do Banco de Dados. Opcionalmente, voce pode deixar a senha em branco. <p>Volte através do botão voltar de seu navegador.</p>";
        echo "</div></div></div></body></html>";
        exit();
    }

    $s = stringuize("_instalar/templates/_config.html", array(
        '$txtMySqlDataServer#' => $txtMySqlDataServer,
        '$txtMySqlDataUser#' => $txtMySqlDataUser,
        '$txtMySqlDataPass#' => $txtMySqlDataPass,
        '$txtMysqlDataDb#' => $txtMysqlDataDb,
        '$txtUrl#' => $txtUrl,
        '$txtAdmLogin#' => $txtAdmLogin,
        '$txtAdmPass#' => $txtAdmPass
            ), obterDocumentRoot());

    arquivos::criar($s, $filenameConfig);
}

if (file_exists($filenameConfig)) {
    include '_config.php';

    $txtMySqlDataServer = ___MySqlDataServer;
    $txtMySqlDataUser = ___MySqlDataUser;
    $txtMySqlDataPass = ___MySqlDataPass;
    $txtMysqlDataDb = ___MysqlDataDb;

    $txtAdmLogin = ___AdmUser;
    $txtAdmPass = ___AdmPass;
} else {
    $txtMySqlDataServer = "localhost";
    $txtMySqlDataUser = "root";
    $txtMySqlDataPass = "";
    $txtMysqlDataDb = "test";

    $txtAdmLogin = "";
    $txtAdmPass = "";
}
?>


<h1>Configuração</h1>

<form method="post">

    <h2>Configurações de usuário e senha</h2>
    <div class="field">
        <label for="txtAdmLogin">Seu login no sistema:</label>
        <input type="text" id="txtAdmLogin" name="txtAdmLogin" value="<?php echo $txtAdmLogin; ?>" />
    </div>
    <div class="field">
        <label for="txtAdmPass">Sua senha no sistema:</label>
        <input type="password" id="txtAdmPass" name="txtAdmPass" value="<?php echo $txtAdmPass; ?>" />
    </div>

    <h2>Configurações do Banco de Dados</h2>
    <div class="field">
        <input type="hidden" id="txtUrl" name="txtUrl" />
    </div>
    <script>
        var value = window.location+"";
        value = value.replace("JqueryCms-Step-2.php", "");
        $("#txtUrl").val(value);
    </script>
    <div class="field">
        <label for="txtMySqlDataServer">MySql Server:</label>
        <input type="text" id="txtMySqlDataServer" name="txtMySqlDataServer" value="<?php echo $txtMySqlDataServer; ?>" />
    </div>
    <div class="field">
        <label for="txtMySqlDataUser">Usuário:</label>
        <input type="text" id="txtMySqlDataUser" name="txtMySqlDataUser" value="<?php echo $txtMySqlDataUser; ?>" />
    </div>
    <div class="field">
        <label for="txtMySqlDataPass">Senha:</label>
        <input type="password" id="txtMySqlDataPass" name="txtMySqlDataPass" value="<?php echo $txtMySqlDataPass; ?>" />
    </div>
    <div class="field">
        <label for="txtMysqlDataDb">Nome do Banco de Dados:</label>
        <input type="text" id="txtMysqlDataDb" name="txtMysqlDataDb" value="<?php echo $txtMysqlDataDb; ?>" />
    </div>
    <div class="field">
        <input type="submit" value="Gerar Arquivo de Configuração" />
    </div>
</form>

<?php
$sqlCreateImage = arquivos::ler("sqlCreate.sql");

try {
    $Conexao = new PDO("mysql:host=$txtMySqlDataServer; dbname=$txtMysqlDataDb", "$txtMySqlDataUser", "$txtMySqlDataPass", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    dataExecSqlDireto($Conexao, $sqlCreateImage);

    if (file_exists($filenameConfig))
        echo "<a href='JqueryCms-Step-3.php' class='btn avancar'>Avançar</a>";
} catch (PDOException $e) {
    if (count($_POST) > 0) {
        echo "<h4>Problemas com a conexão mysql:</h4>" . $e->getMessage();
    }
}


include "_instalar_html/footer.html";
?>