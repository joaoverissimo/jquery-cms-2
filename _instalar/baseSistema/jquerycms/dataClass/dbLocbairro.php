<?php

require_once "base/dbaseLocbairro.php";

class dbLocbairro extends dbaseLocbairro {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $nome, $cidade, $uf, $die = false) {
        throw new jquerycmsException("Não é possível inserir bairros diretamente. Utilize o recurso de bairros remoto.");
        return parent::Inserir($Conexao, $nome, $cidade, $uf, $die);
    }

    public static function Update($Conexao, $cod, $nome, $cidade, $uf, $die = false) {
        throw new jquerycmsException("Não é possível editar bairros diretamente. Utilize o recurso de bairros remoto.");
        return parent::Update($Conexao, $cod, $nome, $cidade, $uf, $die);
    }

    public static function Deletar($Conexao, $cod) {
        throw new jquerycmsException("Não é possível excluir bairros diretamente. Utilize o recurso de bairros remoto.");
        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}
