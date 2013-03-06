<?php

require_once "base/fbaseJqueryadminmenu.php";

class objJqueryadminmenu extends fbaseJqueryadminmenu {

// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
    /**
     * Relacao jqueryadminmenu.codmenu -> jqueryadminmenu.cod
     * @return objJqueryadminmenu[]
     */
    public function obtemJqueryadminmenuRel($orderByField = "", $orderByOrientation = "", $limit = "") {
        $orderBy = new dataOrder($orderByField, $orderByOrientation);
        $where = new dataFilter("jqueryadminmenu.codmenu", $this->getCod());
        $dados = dbJqueryadminmenu::ObjsList($this->Conexao, $where, $orderBy, $limit);
        return $dados;
    }

// </editor-fold>

    /* public function getRewriteUrl($fullUrl = false) {
      $cod = $this->getCod();
      $titulo = toRewriteString($this->getTitulo());

      $link = "jqueryadminmenu/$titulo/$cod/";

      $lang = internacionalizacao::getCurrentLang();
      if ($lang != "pt-br") {
      $url = $lang . "/" . $url;
      }

      if ($fullUrl)
      return ___siteUrl . $link;
      else
      return "/" . $link;
      } */

    public function getLink($adm_folder) {
        $patch = $this->getPatch();

        if (str_contains($patch, "/"))
            return $patch;

        if (!isset($adm_folder) || !$adm_folder)
            $adm_folder = "/adm";

        return $adm_folder . "/" . $patch . "/index.php";
    }

    public function getTitulo() {
        $s = parent::getTitulo();
        $s = internacionalizacao::TraduzirString($s);
        return $s;
    }

}