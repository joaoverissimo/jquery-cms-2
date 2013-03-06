<?php

require_once "base/dbaseEquipe.php";

class dbEquipe extends dbaseEquipe {
// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $nome, $descricao, $email, $imagem, $ordem, $die = false) {
        
        return parent::Inserir($Conexao, $nome, $descricao, $email, $imagem, $ordem, $die);
    }
    
    public static function Update($Conexao, $cod, $nome, $descricao, $email, $imagem, $ordem, $die = false) {
        
        return parent::Update($Conexao, $cod, $nome, $descricao, $email, $imagem, $ordem, $die);
    }
    
    public static function Deletar($Conexao, $cod) {
        $obj = new objEquipe($Conexao);
        $obj->loadByCod($cod);
        $obj->objImagem();
        
        $exec =  parent::Deletar($Conexao, $cod);
        $obj->objImagem()->Delete();
        
        
        return $exec;
    }
// </editor-fold>

    
    
}