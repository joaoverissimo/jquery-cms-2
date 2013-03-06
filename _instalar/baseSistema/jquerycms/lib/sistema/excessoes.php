<?php

class jquerycmsException extends Exception {

    public function errorMessage() {
        if (___MyDebugger) {
            return '<b>PsgIntegra - Excessão:</b> ' . $this->getMessage() . '. Linha: ' . $this->getLine() . ' | Arquivo: ' . $this->getFile();
        } else {
            return '<!--PsgIntegra - Excessão: ' . $this->getMessage() . '. Linha: ' . $this->getLine() . ' | Arquivo: ' . $this->getFile() . '-->';
        }
    }

}

if (___MyDebugger) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
    