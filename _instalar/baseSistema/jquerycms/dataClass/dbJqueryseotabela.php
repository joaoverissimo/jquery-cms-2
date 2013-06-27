<?php

require_once "base/dbaseJqueryseotabela.php";

class dbJqueryseotabela extends dbaseJqueryseotabela {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $tabela, $titulo, $ordem, $die = false) {
        $filename = ___AppRoot . "jquerycms/seobusca";
        if (!arquivos::existe($filename)) {
            if (!mkdir($filename, 0777)) {
                throw new jquerycmsException("Nao e possivel criar a pasta $filename. Por favor defina temporariamente permissao de escrita para ~/jquerycms/");
            }
        }

        $filename .= "/inc-$tabela.php";
        $dados = '<?php
$obj = new obj' . ucfirst($tabela) . '($Conexao);
$obj->loadByCod($resultado["cod"]);
?>
<li>
    <a href="<?php echo $obj->getRewriteUrl(); ?>" class="seobuca_titulo"><b><?php echo $obj->getTitulo(); ?></b></a><br />
    <?php if ($obj->objSeo()->getDescricao()) : ?>
        <span class="seobuca_descricao"><?php echo $obj->objSeo()->getDescricao(); ?></span> <br />
    <?php endif; ?>
    <?php if (isset($resultado["seo"]) && $obj->objSeo()->getKeysVirgula()) : ?>
        <span class="seobuca_keys"><?php echo $obj->objSeo()->getKeysVirgula(); ?></span>
    <?php endif; ?>
</li>';
        if (!arquivos::criar($dados, $filename)) {
            throw new jquerycmsException("Nao e possivel criar $filename. Por favor permissao de escrita.");
        }

        return parent::Inserir($Conexao, $tabela, $titulo, $ordem, $die);
    }

    public static function Update($Conexao, $cod, $tabela, $titulo, $ordem, $die = false) {

        return parent::Update($Conexao, $cod, $tabela, $titulo, $ordem, $die);
    }

    public static function Deletar($Conexao, $cod) {
        /* $obj = new objJqueryseotabela($Conexao);
          $obj->loadByCod($cod);

         */

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}