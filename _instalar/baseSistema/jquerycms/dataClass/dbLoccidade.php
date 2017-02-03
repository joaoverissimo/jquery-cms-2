<?php

require_once "base/dbaseLoccidade.php";

class dbLoccidade extends dbaseLoccidade {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $nome, $uf, $estado, $cep, $ibge, $die = false) {
        throw new jquerycmsException("Não é possível inserir cidades diretamente. Utilize o recurso de cidades remoto.");
        return parent::Inserir($Conexao, $nome, $uf, $estado, $cep, $ibge, $die);
    }

    public static function Update($Conexao, $cod, $nome, $uf, $estado, $cep, $ibge, $die = false) {
        throw new jquerycmsException("Não é possível editar cidades diretamente. Utilize o recurso de cidades remoto.");
        return parent::Update($Conexao, $cod, $nome, $uf, $estado, $cep, $ibge, $die);
    }

    public static function Deletar($Conexao, $cod) {
        throw new jquerycmsException("Não é possível excluir cidades diretamente. Utilize o recurso de cidades remoto.");
        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}
