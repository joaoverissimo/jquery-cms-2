<?php

require_once "base/dbaseJqueryseorel.php";

class dbJqueryseorel extends dbaseJqueryseorel {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $seo, $palavra, $die = false) {
        if (!$seo || !$palavra) {
            return false;
        }

        $where = new dataFilter(self::_seo, $seo);
        $where->add(self::_palavra, $palavra);

        if (!self::Listar($Conexao, $where)) {
            $objpalavra = new objJqueryseopalavra($Conexao);
            $objpalavra->loadByCod($palavra);
            $objpalavra->setCount($objpalavra->getCount() + 1);
            $objpalavra->Save();

            return parent::Inserir($Conexao, $seo, $palavra, $die);
        } else {
            return false;
        }
    }

    public static function Update($Conexao, $cod, $seo, $palavra, $die = false) {

        return parent::Update($Conexao, $cod, $seo, $palavra, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $obj = new objJqueryseorel($Conexao);
        $obj->loadByCod($cod);

        $objpalavra = new objJqueryseopalavra($Conexao);
        $objpalavra->loadByCod($obj->getPalavra());
        $objpalavra->setCount($objpalavra->getCount() - 1);
        $objpalavra->Save();

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>

    public static function InserirByKeyString($Conexao, $seo, $palavrastr, $die = false) {
        $palavra = 0;
        $registro = dbJqueryseopalavra::Carregar($Conexao, $palavrastr, 'palavra');
        if (!$registro) {
            $palavra = dbJqueryseopalavra::Inserir($Conexao, $palavrastr);
        } else {
            $palavra = $registro['cod'];
        }

        return self::Inserir($Conexao, $seo, $palavra);
    }

}
