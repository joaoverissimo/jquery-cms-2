<?php

class objTime {
    /* d/m/Y H:i:s */

    private $time;
    private $timeArr;

    public function getHora() {
        return $this->timeArr[0];
    }

    public function getMiniuto() {
        return $this->timeArr[1];
    }

    public function getSegundo() {
        return $this->timeArr[2];
    }

    public function getStrHM() {
        return "{$this->getHora()}:{$this->getMiniuto()}";
    }

    public function getStrHMS() {
        return "{$this->getHora()}:{$this->getMiniuto()}:{$this->getSegundo()}";
    }

    public function loadByHMS($time) {
        $this->time = $time;
        $this->timeArr = explode(":", $time);
    }

    public function loadByHM($time) {
        $this->time = "$time:00";
        $this->timeArr = explode(":", "$time:00");
    }

    public static function initHMS($time) {
        $obj = new objTime();
        $obj->loadByHMS($time);
        return $obj;
    }

    public static function initHM($time) {
        $obj = new objTime();
        $obj->loadByHM($time);
        return $obj;
    }

}
