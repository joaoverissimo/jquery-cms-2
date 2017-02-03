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

    public function getBairroCidadeUf($breack = ",") {
        $arrBloco2 = array();
        if ($this->getBairro() && $this->objBairro()->getNome()) {
            $arrBloco2[] = $this->objBairro()->getNome();
        }
        if ($this->getCidade() && $this->objCidade()->getNome()) {
            $arrBloco2[] = $this->objCidade()->getNome();
        }
        if ($this->getCidade() && $this->objCidade()->getUf()) {
            $arrBloco2[] = $this->objCidade()->getUf();
        }

        if (issetArray($arrBloco2)) {
            return join($breack, $arrBloco2);
        }
    }

    public function getEnderecoCompleto($breack = "\n", $showEndereco = true, $showCEP = true, $showBairroCidadeUf = true) {
        $arrRt = array();

        $arrBloco1 = array();

        if ($this->getRua()) {
            $arrBloco1[] = $this->getRua();
        }

        if ($this->getNumero()) {
            $arrBloco1[] = $this->getNumero();
        }

        if ($this->getComplemento()) {
            $arrBloco1[] = $this->getComplemento();
        }

        if (issetArray($arrBloco1) && $showEndereco) {
            $arrRt[] = join(", ", $arrBloco1);
        }

        if ($this->getCep() && $showCEP) {
            $arrRt[] = "CEP: " . $this->getCep();
        }

        if ($this->getBairroCidadeUf(",") && $showBairroCidadeUf) {
            $arrRt[] = $this->getBairroCidadeUf(",");
        }

        return join($breack, $arrRt);
    }

    /**
     * Obtem a associacao entre localizacao.estado => locestado.cod
     * @return objLocestado
     */
    public function objEstado() {
        global $ConexaoLoc;

        if (!isset($this->_objEstado)) {
            $obj = new objLocestado($ConexaoLoc, false);
            $obj->loadByCod($this->estado);
            $this->_objEstado = $obj;
        }

        return $this->_objEstado;
    }

    /**
     * Obtem a associacao entre localizacao.bairro => locbairro.cod
     * @return objLocbairro
     */
    public function objBairro() {
        global $ConexaoLoc;

        if (!isset($this->_objBairro)) {
            $obj = new objLocbairro($ConexaoLoc, false);
            $obj->loadByCod($this->bairro);
            $this->_objBairro = $obj;
        }

        return $this->_objBairro;
    }

    /**
     * Obtem a associacao entre localizacao.cidade => loccidade.cod
     * @return objLoccidade
     */
    public function objCidade() {
        global $ConexaoLoc;

        if (!isset($this->_objCidade)) {
            $obj = new objLoccidade($ConexaoLoc, false);
            $obj->loadByCod($this->cidade);
            $this->_objCidade = $obj;
        }

        return $this->_objCidade;
    }

}
