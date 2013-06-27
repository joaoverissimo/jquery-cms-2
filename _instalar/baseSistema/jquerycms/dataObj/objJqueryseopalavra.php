<?php

require_once "base/fbaseJqueryseopalavra.php";

class objJqueryseopalavra extends fbaseJqueryseopalavra {

// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
    /**
     * Relacao jqueryseorel.palavra -> jqueryseopalavra.cod
     * @return objJqueryseorel[]
     */
    public function obtemJqueryseorelRel($orderByField = "", $orderByOrientation = "", $limit = "") {
        $orderBy = new dataOrder($orderByField, $orderByOrientation);
        $where = new dataFilter("jqueryseorel.palavra", $this->getCod());
        $dados = dbJqueryseorel::ObjsList($this->Conexao, $where, $orderBy, $limit);
        return $dados;
    }

// </editor-fold>

    public function ativarRewriteEngine() {
        //Add RewriteRule in .htaccess:
        //RewriteRule ^busca/([a-z0-9-]+)/?$ /seobusca.php?url=$1 [NC]
        return false;
    }

    public function getRewriteUrl($fullUrl = false) {
        $url = $this->getUrl();

        if ($this->ativarRewriteEngine()) {
            $link = "busca/$url";
        } else {
            $link = "seobusca.php?url=$url";
        }

        if ($fullUrl)
            return ___siteUrl . $link;
        else
            return "/" . $link;
    }

}