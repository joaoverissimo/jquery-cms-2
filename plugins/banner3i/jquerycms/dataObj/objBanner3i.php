<?php

require_once "base/fbaseBanner3i.php";

class objBanner3i extends fbaseBanner3i {

    public function getDescricao() {
        $s = "";
        $lang = internacionalizacao::getCurrentLang();
        if ($lang == "pt-br") {
            $s = $this->getDescricaopt();
        } elseif ($lang == "es") {
            $s = $this->getDescricaoes();
        } elseif ($lang == "en") {
            $s = $this->getDescricaoen();
        }

        if (!$s) {
            return $this->getDescricaopt();
        }

        return $s;
    }

    public function getTitulo() {
        $s = "";
        $lang = internacionalizacao::getCurrentLang();
        if ($lang == "pt-br") {
            $s = $this->getTitulopt();
        } elseif ($lang == "es") {
            $s = $this->getTituloes();
        } elseif ($lang == "en") {
            $s = $this->getTituloen();
        }

        if (!$s) {
            return $this->getDescricaopt();
        }

        return $s;
    }

}