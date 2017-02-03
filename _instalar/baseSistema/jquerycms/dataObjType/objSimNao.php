<?php

class objSimNao extends fbaseConstante {

    const ct_Sim = "1";
    const ct_Nao = "0";

    public function arr() {
        return array(
            1 => array("1", "Sim"),
            0 => array("0", "Não")
        );
    }

    /**
     * @return objSimNao
     */
    public static function init($cod = null) {
        $class = get_called_class();
        return new $class($cod);
    }

}
