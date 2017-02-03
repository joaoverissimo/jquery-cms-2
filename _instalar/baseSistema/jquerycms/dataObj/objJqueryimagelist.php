<?php

require_once "base/fbaseJqueryimagelist.php";

class objJqueryimagelist extends fbaseJqueryimagelist {

    private $jqueryimagelistitemRel;
    private $firstordefault;

    /**
     * Relacao jqueryimagelistitem.jqueryimagelist -> jqueryimagelist.cod
     * @return objJqueryimagelistitem[]
     */
    public function obtemJqueryimagelistitemRel($orderByField = "jqueryimagelistitem.ordem", $orderByOrientation = "ASC", $limit = "") {
        if (!isset($this->jqueryimagelistitemRel)) {
            $orderBy = new dataOrder($orderByField, $orderByOrientation);
            $where = new dataFilter("jqueryimagelistitem.jqueryimagelist", $this->getCod());
            $dados = dbJqueryimagelistitem::ObjsList($this->Conexao, $where, $orderBy, $limit);
            $this->jqueryimagelistitemRel = $dados;
        }

        return $this->jqueryimagelistitemRel;
    }

    private $obtemItensExistentes;

    public function obtemItensExistentes($orderByField = "jqueryimagelistitem.ordem", $orderByOrientation = "ASC", $limit = "") {
        if (!isset($this->obtemItensExistentes)) {
            $dados = $this->obtemJqueryimagelistitemRel($orderByField, $orderByOrientation, $limit);
            $rt = array();

            foreach ($dados as $objJqueryimagelistitem) {
                if ($objJqueryimagelistitem->objJqueryimage()->getExiste()) {
                    $rt[] = $objJqueryimagelistitem;
                }
            }

            $this->obtemItensExistentes = $rt;
        }

        return $this->obtemItensExistentes;
    }

    public function obtemItens($orderByField = "jqueryimagelistitem.ordem", $orderByOrientation = "ASC", $limit = "") {
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

    /**
     * Obtem a primeira ou default imagem
     * @return objJqueryimage
     */
    public function objImageFirstOrDefault() {
        if ($this->objItemFirstOrDefault() && $this->objItemFirstOrDefault()->objJqueryimage()->getExiste()) {
            return $this->objItemFirstOrDefault()->objJqueryimage();
        }

        return false;
    }

    public function RecalcInfo() {
        return dbJqueryimagelist::RecalcInfo($this->Conexao, $this->getCod());
    }

    public function addJqueryImage($jqueryimage) {
        if ($jqueryimage instanceof objJqueryimage) {
            $jqueryimage = $jqueryimage->getCod();
        } else {
            $jqueryimage = $jqueryimage;
        }

        $jqueryimagelist = $this->cod;
        return dbJqueryimagelistitem::Inserir($this->Conexao, $jqueryimagelist, $jqueryimage, "", "", "", "", "", false);
    }

}
