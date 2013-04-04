<?php

require_once "base/fbaseJqueryimagelist.php";

class objJqueryimagelist extends fbaseJqueryimagelist {

    private $jqueryimagelistitemRel;
    private $firstordefault;

    /**
     * Relacao jqueryimagelistitem.jqueryimagelist -> jqueryimagelist.cod
     * @return objJqueryimagelistitem[]
     */
    public function obtemJqueryimagelistitemRel($orderByField = "portalimagelistitem.ordem", $orderByOrientation = "ASC", $limit = "") {
        if (!isset($this->jqueryimagelistitemRel)) {
            $orderBy = new dataOrder($orderByField, $orderByOrientation);
            $where = new dataFilter("jqueryimagelistitem.jqueryimagelist", $this->getCod());
            $dados = dbJqueryimagelistitem::ObjsList($this->Conexao, $where, $orderBy, $limit);
            $this->jqueryimagelistitemRel = $dados;
        }

        return $this->jqueryimagelistitemRel;
    }

    public function obtemItens($orderByField = "portalimagelistitem.ordem", $orderByOrientation = "ASC", $limit = "") {
        return $this->obtemJqueryimagelistitemRel($orderByField, $orderByOrientation, $limit);
    }

	/**
     * Obtem o primeiro ou default item
     * @return objJqueryimagelistitem
     */  
    public function objItemFirstOrDefault() {
        if (!isset($this->firstordefault)) {
            $this->firstordefault = dbJqueryimagelist::obtemFirstOrDefault($this->Conexao, $this->getCod());
        }

        return $this->firstordefault;
    }

}