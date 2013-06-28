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

        $params = array();
        $params[] = "img=$cod";
        $params[] = "valor=$titulo";
        if ($width) {
            $params[] = "width=$width";
        }

        if ($height) {
            $params[] = "height=$height";
        }

        if ($crop) {
            $params[] = "crop=1";
        } else {
            $params[] = "crop=0";
        }

        $link = "img.php?" . join("&", $params);

        if ($fullUrl)
            return ___siteUrl . $link;
        else
            return "/" . $link;
    }

    /**
     * Retorna a imagem exatamente do tamanho solicitado. Ideal para cituacoes 
     * que a imagem necessite ser extremamente fina ou extremamente alta
     * @param int $width
     * @param int $height
     * @param bool $crop
     * @param bool $fullUrl
     * @return string
     */
    public function getSrc_PropFit($width = 100, $height = 70, $crop = 1, $fullUrl = false) {
        $cod = $this->getCod();
        $titulo = toRewriteString($this->getValor());
        $titulo = str_replace("{$cod}_", "", $this->getValor());

        $params = array();
        $params[] = "img=$cod";
        $params[] = "valor=$titulo";
        if ($width) {
            $params[] = "width=$width";
        }

        if ($height) {
            $params[] = "height=$height";
        }

        if ($crop) {
            $params[] = "crop=1";
        } else {
            $params[] = "crop=0";
        }

        $link = "img_.php?" . join("&", $params);

        if ($fullUrl)
            return ___siteUrl . $link;
        else
            return "/" . $link;
    }

    /**
     * Retorna a imagem proporcional. 
     * @param int $width
     * @param int $height
     * @param bool $crop
     * @param bool $fullUrl
     * @return string
     */
    public function getSrc_PropMax($width = 100, $height = 70, $fullUrl = false) {
        $cod = $this->getCod();
        $titulo = toRewriteString($this->getValor());
        $titulo = str_replace("{$cod}_", "", $this->getValor());

        $params = array();
        $params[] = "img=$cod";
        $params[] = "valor=$titulo";
        $params[] = "width=$width";
        $params[] = "height=$height";

        $link = "img_propmax.php?" . join("&", $params);

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