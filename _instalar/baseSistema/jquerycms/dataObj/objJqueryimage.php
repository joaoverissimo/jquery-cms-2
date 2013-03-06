<?php

require_once "base/fbaseJqueryimage.php";

class objJqueryimage extends fbaseJqueryimage {

    public function getRewriteUrl($fullUrl = false) {
        $cod = $this->getCod();
        $titulo = toRewriteString($this->getValor());
        $titulo = str_replace("{$cod}_", "", $this->getValor());

        $link = "img.php?img=$cod&valor=$titulo";

        if ($fullUrl)
            return ___siteUrl . $link;
        else
            return "/" . $link;
    }

    public function getSrc($width = 100, $height = 70, $crop = 1, $fullUrl = false) {
        $cod = $this->getCod();
        $titulo = toRewriteString($this->getValor());
        $titulo = str_replace("{$cod}_", "", $this->getValor());

        $link = "img.php?img=$cod&valor=$titulo&width=$width&height=$height&crop=$crop";

        if ($fullUrl)
            return ___siteUrl . $link;
        else
            return "/" . $link;
    }

    public function getFileName() {
        $filefolder = $this->getFolder();
        return $filefolder . $this->getValor();
    }

    public function getExiste() {
        return arquivos::existe($this->getFileName());
    }

    public function getFolder() {
        return ___AppRoot . "jquerycms/upload/images/";
    }

}