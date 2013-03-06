<?php

require_once "base/dbaseJqueryadmingrupo.php";

class dbJqueryadmingrupo extends dbaseJqueryadmingrupo {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $titulo, $die = false) {

        return parent::Inserir($Conexao, $titulo, $die);
    }

    public static function Update($Conexao, $cod, $titulo, $die = false) {

        return parent::Update($Conexao, $cod, $titulo, $die);
    }

    public static function Deletar($Conexao, $cod) {
        /* $obj = new objJqueryadmingrupo($Conexao);
          $obj->loadByCod($cod);

         */
        if ($cod <= 2) {
            throw new jquerycmsException("Não é permitido remover o grupo Jquery e o grupo Adminstradores!");
        }


        $where = new dataFilter("jqueryadmingrupo2menu.jqueryadmingrupo", $cod);
        dbJqueryadmingrupo2menu::DeletarWhere($Conexao, $where);

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}