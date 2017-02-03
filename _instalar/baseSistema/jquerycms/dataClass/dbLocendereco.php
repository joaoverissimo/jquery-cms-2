<?php

require_once "base/dbaseLocendereco.php";

class dbLocendereco extends dbaseLocendereco {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $nome, $cep, $bairro, $cidade, $estado, $die = false) {
        throw new jquerycmsException("Não é possível inserir logradouros diretamente. Utilize o recurso de logradouros remoto.");
        return parent::Inserir($Conexao, $nome, $cep, $bairro, $cidade, $estado, $die);
    }

    public static function Update($Conexao, $cod, $nome, $cep, $bairro, $cidade, $estado, $die = false) {
        throw new jquerycmsException("Não é possível editar logradouros diretamente. Utilize o recurso de logradouros remoto.");
        return parent::Update($Conexao, $cod, $nome, $cep, $bairro, $cidade, $estado, $die);
    }

    public static function Deletar($Conexao, $cod) {
        throw new jquerycmsException("Não é possível excluir logradouros diretamente. Utilize o recurso de logradouros remoto.");
        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}
