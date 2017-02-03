<?php

require_once "base/dbaseJqueryadminuser.php";

class dbJqueryadminuser extends dbaseJqueryadminuser {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $nome, $mail, $senha, $grupo, $die = false) {

        return parent::Inserir($Conexao, $nome, $mail, $senha, $grupo, $die);
    }

    public static function Update($Conexao, $cod, $nome, $mail, $senha, $grupo, $die = false) {
        $obj = objJqueryadminuser::init($cod);
        $alterouSenha = $obj->getSenha() <> $senha;

        $exec = parent::Update($Conexao, $cod, $nome, $mail, $senha, $grupo, $die);

        if ($alterouSenha && $exec) {
            $obj = objJqueryadminuser::init($cod);
            mailJqueryadminuser::enviarAlterarSenha($obj);
        }

        return $exec;
    }

    public static function Deletar($Conexao, $cod) {
        /* $obj = new objJqueryadminuser($Conexao);
          $obj->loadByCod($cod);

          $obj->objGrupo()->Delete();
         */

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>

    public static function auth($Conexao, $mail, $senha) {
        if (!isset($mail) || !isset($senha) || !$mail || !$senha)
            return false;

        $where = new dataFilter("jqueryadminuser.mail", $mail);
        $where->add("jqueryadminuser.senha", $senha);

        $registro = self::Listar($Conexao, $where, "", "0,1");
        if ($registro !== false && isset($registro[0]["cod"])) {
            $_SESSION["jqueryuser"] = $registro[0]["cod"];
            return true;
        }

        return false;
    }

}
