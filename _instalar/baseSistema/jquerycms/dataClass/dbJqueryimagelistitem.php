<?php

require_once "base/dbaseJqueryimagelistitem.php";

class dbJqueryimagelistitem extends dbaseJqueryimagelistitem {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die = false) {

        return parent::Inserir($Conexao, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die);
    }

    public static function Update($Conexao, $cod, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die = false) {

        return parent::Update($Conexao, $cod, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $obj = new objJqueryimagelistitem($Conexao);
        $obj->loadByCod($cod);

        $exec = parent::Deletar($Conexao, $cod);

        $obj->objJqueryimage()->Delete();

        return $exec;
    }

// </editor-fold>
}