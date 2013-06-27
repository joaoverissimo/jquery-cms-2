<?php

require_once "base/fbaseJqueryseo.php";

class objJqueryseo extends fbaseJqueryseo {

// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
    /**
     * Relacao jqueryseorel.seo -> jqueryseo.cod
     * @return objJqueryseorel[]
     */
    /* public function obtemJqueryseorelRel ($orderByField = "", $orderByOrientation = "", $limit = "") {
      $orderBy = new dataOrder($orderByField, $orderByOrientation);
      $where = new dataFilter("jqueryseorel.seo", $this->getCod());
      $dados = dbJqueryseorel::ObjsList($this->Conexao, $where, $orderBy, $limit);
      return $dados;
      } */


// </editor-fold>

    /* public function getRewriteUrl($fullUrl = false) {
      $cod = $this->getCod();
      $titulo = toRewriteString($this->getTitulo());

      $link = "jqueryseo/$titulo/$cod/";

      $lang = internacionalizacao::getCurrentLang();
      if ($lang != "pt-br") {
      $url = $lang . "/" . $url;
      }

      if ($fullUrl)
      return ___siteUrl . $link;
      else
      return "/" . $link;
      } */

    private $keys;

    public function getKeys() {
        if (!isset($this->keys)) {
            $this->keys = dbJqueryseo::obtemKeys($this->Conexao, $this->getCod());
        }
        return $this->keys;
    }

    public function getKeysCloud($tagcloud = false, $max = 7, $min = 1) {
        //Thanks http://stackoverflow.com/questions/1926403/first-jquery-plugin-tagcloud

        $dados = $this->getKeys();
        if (!$dados) {
            return false;
        }

        $seocod = $this->getCod();
        if ($seocod < 0) {
            $seocod = $seocod * -1;
        }

        $s = "<ul class='seo_cloud seo_cloud{$this->getCod()}'>";
        foreach ($dados as $obj) {
            $palavra = $obj->objPalavra()->getPalavra();
            $count = $obj->objPalavra()->getCount();
            $url = $obj->objPalavra()->getRewriteUrl();
            $s .= "<li><a href='$url' rel='$count'><span>$palavra</span></a></li>";
        }

        if ($s != "<ul>") {
            return $s . '</ul>
                <script>(function(e){e.fn.tagCloud' . $seocod . '=function(t){t=t||{};var n=t["maxPercent"]||' . $max . ';var r=t["minPercent"]||' . $min . ';var i=t["retrieveCount"]||function(t){return e(t).attr("rel")};var s=t["apply"]||function(t,n){e(t).addClass("size"+n)};var o=null;var u=null;var a=this;a.each(function(e){var t=i(this);o=o==null||t>o?t:o;u=u==null||u>t?t:u});var f=(n-r)/(o-u);a.each(function(e){var t=i(this);size=r+(t-u)*f;s(this,size)})}})(jQuery)
                $(document).ready(function(){ $(".seo_cloud' . $seocod . ' li a").tagCloud' . $seocod . '(); });</script>';
        } else {
            return "";
        }
    }

    private $keysVirgula;

    public function getKeysVirgula() {
        if (!isset($this->keysVirgula)) {
            $this->keysVirgula = dbJqueryseo::obtemKeysVirgula($this->Conexao, $this->getCod());
        }
        return $this->keysVirgula;
    }

    public function getHeadTags($titleIfEmpty, $canonical = "", $descriptionIfEmpty = "") {
        $titulo = $this->getTitulo();
        if (!$titulo) {
            $titulo = $titleIfEmpty;
        }

        $descricao = $this->getDescricao();
        if (!$descricao) {
            $descricao = $descriptionIfEmpty;
        }

        $keys = $this->getKeysVirgula();
        $s = "<title>$titulo</title>";
        $s .= "<meta name='keywords' content='$keys'/>";
        $s .= "<meta name='description' content='$descricao'/>";

        if ($canonical) {
            $s.= "<link rel='canonical' href='$canonical' />";
        }

        return $s;
    }
	
	public function AddKeyWord($palavra) {
        $objSeoPalavra = new objJqueryseopalavra($this->Conexao, false);
        if (!$objSeoPalavra->loadByCod($palavra, 'palavra')) {
            $objSeoPalavra->setPalavra($palavra);
            $objSeoPalavra->Save();
        }

        $objSeoRel = new objJqueryseorel($this->Conexao, false);
        $objSeoRel->setSeo($this->getCod());
        $objSeoRel->setPalavra($objSeoPalavra->getCod());
        
        return $objSeoRel->Save();
    }

}