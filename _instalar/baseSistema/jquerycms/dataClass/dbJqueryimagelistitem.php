<?php

require_once "base/dbaseJqueryimagelistitem.php";

class dbJqueryimagelistitem extends dbaseJqueryimagelistitem {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die = false) {
        if (!$ordem) {
            $jqueryimagelist = intval($jqueryimagelist);
            $dados = dataExecSqlDireto($Conexao, "SELECT max(ordem) as rt FROM jqueryimagelistitem WHERE jqueryimagelist = {$jqueryimagelist}", false);
            if (issetArray($dados) && isset($dados['rt'])) {
                $ordem = $dados['rt'] + 1;
            }
        }

        $imagelist = new objJqueryimagelist($Conexao);
        $imagelist->loadByCod($jqueryimagelist);
        $imagelist->setInfo($imagelist->getInfo() + 1);
        $imagelist->Save();

        return parent::Inserir($Conexao, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die);
    }

    public static function Update($Conexao, $cod, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die = false) {

        return parent::Update($Conexao, $cod, $jqueryimagelist, $jqueryimage, $titulo, $link, $target, $descricao, $ordem, $principal, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $obj = new objJqueryimagelistitem($Conexao);
        $obj->loadByCod($cod);

        //Deleta o registro Jqueryimagelistitem
        $exec = parent::Deletar($Conexao, $cod);

        //Deleta a imagem
        $obj->objJqueryimage()->Delete();

        //Atualiza o info da Imagelist
        $obj->objJqueryimagelist()->setInfo($obj->objJqueryimagelist()->getInfo() - 1);
        $obj->objJqueryimagelist()->Save();

        return $exec;
    }

// </editor-fold>
}
