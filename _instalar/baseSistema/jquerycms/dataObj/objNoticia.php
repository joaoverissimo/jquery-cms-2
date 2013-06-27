<?php

require_once "base/fbaseNoticia.php";

class objNoticia extends fbaseNoticia {

// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
// </editor-fold>

    public function getRewriteUrl($fullUrl = false) {
        $cod = $this->getCod();
        $titulo = toRewriteString($this->getTitulo());

        $link = "noticia-detalhe.php?cod=$cod";

        if ($fullUrl)
            return ___siteUrl . $link;
        else
            return "/" . $link;
    }

}