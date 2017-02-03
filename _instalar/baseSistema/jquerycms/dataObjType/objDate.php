<?php

class objDate {
    /* d/m/Y H:i:s */

    private $date;
    private $time;
    protected $datePtBr;
    protected $dateMySql;

    public function getDia() {
        return utf8_encode(strftime('%d', strtotime($this->date)));
    }

    public function getMes() {
        return utf8_encode(strftime('%m', strtotime($this->date)));
    }

    public function getAno() {
        return utf8_encode(strftime('%Y', strtotime($this->date)));
    }

    public function getDataMySql() {
        return utf8_encode(strftime('%Y-%m-%d', strtotime($this->date)));
    }

    public function getDataPtBr() {
        return utf8_encode(strftime('%d/%m/%Y', strtotime($this->date)));
    }

    public function getDiaSemanaCurto() {
        $diasemana = array('domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sabado');
        $diasemanaNumero = utf8_encode(strftime('%w', strtotime($this->date)));
        return $diasemana[$diasemanaNumero];
    }

    public function getDiaSemanaExtenso() {
        $diasemana = array('domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sabado');
        $diasemanaNumero = utf8_encode(strftime('%w', strtotime($this->date)));
        return $diasemana[$diasemanaNumero];
    }

    public function getMesExtenso() {
        $mes = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
        $mesNumero = intval($this->getMes());
        return $mes[$mesNumero];
    }

    public function getTimeHM() {
        $arr = explode(":", $this->time);
        return "{$arr[0]}:{$arr[1]}";
    }

    public function getTimeHMS() {
        return $this->time;
    }

    public function loadByPtBr($datePtBr) {
        if (strpos($datePtBr, ":") !== false) {
            $this->time = explode(" ", $datePtBr)[1];
            $datePtBr = Fncs_LerDataTimeRtData($datePtBr);
        }

        $this->datePtBr = $datePtBr;
        $this->dateMySql = str_dataPtBrToMySql($datePtBr);
        $this->date = date($this->dateMySql);
    }

    public function loadByMySql($dateMySql) {
        $this->dateMySql = $dateMySql;
        $this->datePtBr = Fncs_LerData($dateMySql);
        $this->date = date($this->dateMySql);
    }

    public static function initPtBr($datePtBr) {
        $obj = new objDate();
        $obj->loadByPtBr($datePtBr);
        return $obj;
    }

    public static function initMySql($dateMySql) {
        $obj = new objDate();
        $obj->loadByMySql($dateMySql);
        return $obj;
    }

}
