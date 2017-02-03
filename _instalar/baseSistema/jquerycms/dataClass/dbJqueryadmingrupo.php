<?php

require_once "base/dbaseJqueryadmingrupo.php";

class dbJqueryadmingrupo extends dbaseJqueryadmingrupo {

    public static function permissaoAdd($Conexao, $codGrupo, $pathPermissao) {
        $objMenu = objJqueryadminmenu::init($pathPermissao, "patch", false);
        if ($objMenu->getCod()) {
            return dbJqueryadmingrupo2menu::Inserir($Conexao, $codGrupo, $objMenu->getCod());
        }

        return false;
    }

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $titulo, $die = false) {
        $exec = parent::Inserir($Conexao, $titulo, $die);

        dbJqueryadmingrupo::permissaoAdd($Conexao, $exec, "home");
        dbJqueryadmingrupo::permissaoAdd($Conexao, $exec, "arquivos");
        dbJqueryadmingrupo::permissaoAdd($Conexao, $exec, "arquivolist");
        dbJqueryadmingrupo::permissaoAdd($Conexao, $exec, "jqueryimage");
        dbJqueryadmingrupo::permissaoAdd($Conexao, $exec, "jqueryimagelist");
        dbJqueryadmingrupo::permissaoAdd($Conexao, $exec, "jqueryseo");
        dbJqueryadmingrupo::permissaoAdd($Conexao, $exec, "locmapaponto");

        return $exec;
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
