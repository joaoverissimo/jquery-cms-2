<?php

require_once "base/fbaseLocbairro.php";

class objLocbairro extends fbaseLocbairro {

// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
    /**
     * Relacao localizacao.bairro -> locbairro.cod
     * @return objLocalizacao[]
     */
    /* public function obtemLocalizacaoRel ($orderByField = "", $orderByOrientation = "", $limit = "") {
      $orderBy = new dataOrder($orderByField, $orderByOrientation);
      $where = new dataFilter("localizacao.bairro", $this->getCod());
      $dados = dbLocalizacao::ObjsList($this->Conexao, $where, $orderBy, $limit);
      return $dados;
      } */


// </editor-fold>

    public function getRewriteUrlByModalidade($imovelModalidade, $fullUrl = false) {
        if (!$imovelModalidade instanceof objImovelModalidade) {
            $imovelModalidade = objImovelModalidade::init($imovelModalidade);
        }

        $cod = $this->getCod();
        $titulo = toRewriteString($this->getNome());

        $link = $imovelModalidade->getRewriteUrl() . "bairro/$titulo/$cod/";

        $lang = internacionalizacao::getCurrentLang();
        if ($lang != "pt-br") {
            $url = $lang . "/" . $url;
        }

        if ($fullUrl) {
            return str_replace("http:/", "http://", str_replace("//", "/", ___siteUrl . $link));
        }

        return str_replace("//", "/", $link);
    }

}
