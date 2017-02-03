<?php

require_once "base/fbaseJqueryadminuser.php";

class objJqueryadminuser extends fbaseJqueryadminuser {

// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
// </editor-fold>

    /* public function getRewriteUrl($fullUrl = false) {
      $cod = $this->getCod();
      $titulo = toRewriteString($this->getTitulo());

      $link = "jqueryadminuser/$titulo/$cod/";

      $lang = internacionalizacao::getCurrentLang();
      if ($lang != "pt-br") {
      $url = $lang . "/" . $url;
      }

      if ($fullUrl)
      return ___siteUrl . $link;
      else
      return "/" . $link;
      } */

    public function validatePermissions($url = "") {
        global $adm_folder;

        if (!isset($adm_folder) || !$adm_folder) {
            $adm_folder = "/adm";
        }

        if ($url == "#") {
            return true;
        }

        if ($url == "") {
            $url = Fncs_GetCurrentURL(true);
        }

        $pattern = "/adm\/([a-zA-Z-\.]*)\//";
        if (!preg_match($pattern, $url, $matches)) {
            return false;
        }

        if (!isset($matches[1])) {
            return false;
        }

        $patch = $matches[1];

        if ($patch === "login") {
            return true;
        }

        $registro = dbJqueryadminmenu::Carregar($this->Conexao, $patch, 'patch');
        if ($registro === false) {
            return false;
        }

        $where = new dataFilter("jqueryadmingrupo2menu.jqueryadmingrupo", $this->getGrupo());
        $where->add("jqueryadmingrupo2menu.jqueryadminmenu", $registro["cod"]);
        $dados = dbJqueryadmingrupo2menu::Listar($this->Conexao, $where, "", "0,1");
        if ($dados !== false) {
            return true;
        } else {
            return false;
        }
    }

}
