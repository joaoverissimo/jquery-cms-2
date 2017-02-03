<?php

require_once "base/dbaseLocalizacao.php";

class dbLocalizacao extends dbaseLocalizacao {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $rua, $numero, $complemento, $cep, $bairro, $cidade, $estado, $die = false) {

        return parent::Inserir($Conexao, $rua, $numero, $complemento, $cep, $bairro, $cidade, $estado, $die);
    }

    public static function Update($Conexao, $cod, $rua, $numero, $complemento, $cep, $bairro, $cidade, $estado, $die = false) {

        return parent::Update($Conexao, $cod, $rua, $numero, $complemento, $cep, $bairro, $cidade, $estado, $die);
    }

    public static function Deletar($Conexao, $cod) {
        /* $obj = new objLocalizacao($Conexao);
          $obj->loadByCod($cod);

          $exec = parent::Deletar($Conexao, $cod);

          //$obj->objEstado()->Delete();
          //$obj->objBairro()->Delete();
          //$obj->objCidade()->Delete();

          return $exec;
         */

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}
