<?php

require_once "base/fbaseLoccidade.php";

class objLoccidade extends fbaseLoccidade {
// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
    /**
     * Relacao localizacao.cidade -> loccidade.cod
     * @return objLocalizacao[]
     */
    /* public function obtemLocalizacaoRel ($orderByField = "", $orderByOrientation = "", $limit = "") {
      $orderBy = new dataOrder($orderByField, $orderByOrientation);
      $where = new dataFilter("localizacao.cidade", $this->getCod());
      $dados = dbLocalizacao::ObjsList($this->Conexao, $where, $orderBy, $limit);
      return $dados;
      } */


// </editor-fold>

    /* public function getRewriteUrl($fullUrl = false) {
      $cod = $this->getCod();
      $titulo = toRewriteString($this->getTitulo());

      $link = "loccidade/$titulo/$cod/";

      $lang = internacionalizacao::getCurrentLang();
      if ($lang != "pt-br") {
      $url = $lang . "/" . $url;
      }

      if ($fullUrl)
      return ___siteUrl . $link;
      else
      return "/" . $link;
      } */
}
