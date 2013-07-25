<?php

require_once "base/fbaseLocalizacao.php";

class objLocalizacao extends fbaseLocalizacao {
// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
    /**
     * Relacao clienteendereco.localizacao -> localizacao.cod
     * @return objClienteendereco[]
     */
    /* public function obtemClienteenderecoRel ($orderByField = "", $orderByOrientation = "", $limit = "") {
      $orderBy = new dataOrder($orderByField, $orderByOrientation);
      $where = new dataFilter("clienteendereco.localizacao", $this->getCod());
      $dados = dbClienteendereco::ObjsList($this->Conexao, $where, $orderBy, $limit);
      return $dados;
      } */

    /**
     * Relacao empresa.localizacao -> localizacao.cod
     * @return objEmpresa[]
     */
    /* public function obtemEmpresaRel ($orderByField = "", $orderByOrientation = "", $limit = "") {
      $orderBy = new dataOrder($orderByField, $orderByOrientation);
      $where = new dataFilter("empresa.localizacao", $this->getCod());
      $dados = dbEmpresa::ObjsList($this->Conexao, $where, $orderBy, $limit);
      return $dados;
      } */


// </editor-fold>

    /* public function getRewriteUrl($fullUrl = false) {
      $cod = $this->getCod();
      $titulo = toRewriteString($this->getTitulo());

      $link = "localizacao/$titulo/$cod/";

      $lang = internacionalizacao::getCurrentLang();
      if ($lang != "pt-br") {
      $url = $lang . "/" . $url;
      }

      if ($fullUrl)
      return ___siteUrl . $link;
      else
      return "/" . $link;
      } */

    public function getEnderecoCompleto($breack = "\n") {
        $s = "";

        if ($this->getRua()) {
            $s.= $this->getRua();
        }

        if ($this->getNumero()) {
            $s.= ", " . $this->getNumero();
        }

        if ($this->getComplemento()) {
            $s.= ", " . $this->getComplemento();
        }

        $s.= $breack;

        if ($this->getCep()) {
            $s.= "cep: " . $this->getCep();
        }

        $s.= $breack;
        $s.= $this->objBairro()->getNome() . ", " . $this->objCidade()->getNome() . " - " . $this->objCidade()->getUf();

        return $s;
    }

}