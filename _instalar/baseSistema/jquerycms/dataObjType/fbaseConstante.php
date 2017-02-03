<?php

abstract class fbaseConstante {

    protected $cod;

    abstract public function arr();

    public function getCod() {
        return $this->cod;
    }

    public function getDescricao() {
        $cod = $this->cod;
        $arr = $this->arr();

        if (!isset($arr[$cod][1])) {
            throw new jquerycmsException(__CLASS__ . ": não foi possível identificar o código '{$cod}'.");
        }

        return $arr[$cod][1];
    }

    public function obtemByDescricao($descricao) {
        $arr = $this->arr();

        foreach ($arr as $key => $value) {
            if ($value[1] == $descricao) {
                return "$key";
            }
        }

        return false;
    }

    public function optValue($AdicionarValorEmBranco = false) {
        $arr = $this->arr();

        $rt = array();
        if ($AdicionarValorEmBranco) {
            $rt = array(0 => "");
        }

        foreach ($arr as $key => $value) {
            $rt [] = $key;
        }

        return join(",", $rt);
    }

    public function optTxt($AdicionarValorEmBranco = false) {
        $arr = $this->arr();

        $rt = array();
        if ($AdicionarValorEmBranco) {
            $rt = array(0 => "Selecione...");
        }
        foreach ($arr as $key => $value) {
            $rt [] = $value[1];
        }

        return join(",", $rt);
    }

    function __construct($cod = null) {
        $this->cod = $cod;
    }

}
