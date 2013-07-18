<?php

require_once "base/dbaseJqueryseopalavra.php";

class dbJqueryseopalavra extends dbaseJqueryseopalavra {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $palavra, $url = "", $count = 0, $die = false) {
        $url = toRewriteString($palavra);
        if ($palavra && $url) {            
            $obj = new objJqueryseopalavra($Conexao, false);
            if (!$obj->loadByCod($url, 'url')) {
                //Somente insere se a palavra nao existir
                return parent::Inserir($Conexao, trim($palavra), $url, $count, $die);
            }
        }

        return false;
    }

    public static function Update($Conexao, $cod, $palavra, $url, $count, $die = false) {
        $url = toRewriteString($palavra);
        return parent::Update($Conexao, $cod, $palavra, $url, $count, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $obj = new objJqueryseopalavra($Conexao);
        $obj->loadByCod($cod);

        $where = new dataFilter(dbJqueryseorel::_palavra, $cod);
        dbJqueryseorel::DeletarWhere($Conexao, $where);

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}