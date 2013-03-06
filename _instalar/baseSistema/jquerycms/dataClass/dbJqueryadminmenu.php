<?php

require_once "base/dbaseJqueryadminmenu.php";

class dbJqueryadminmenu extends dbaseJqueryadminmenu {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $codmenu, $titulo, $patch, $icon, $addhtml, $ordem, $die = false) {
        $where = new dataFilter("jqueryadminmenu.patch", $patch);
        $dados = self::Listar($Conexao, $where);

        if ($dados !== false) {
            dbJqueryimage::Deletar($Conexao, $icon);
            throw new jquerycmsException("Este menu patch já existe!");
        }

        return parent::Inserir($Conexao, $codmenu, $titulo, $patch, $icon, $addhtml, $ordem, $die);
    }

    public static function Update($Conexao, $cod, $codmenu, $titulo, $patch, $icon, $addhtml, $ordem, $die = false) {

        return parent::Update($Conexao, $cod, $codmenu, $titulo, $patch, $icon, $addhtml, $ordem, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $where = new dataFilter("jqueryadmingrupo2menu.jqueryadminmenu", $cod);
        dbJqueryadmingrupo2menu::DeletarWhere($Conexao, $where);

        $obj = new objJqueryadminmenu($Conexao);
        $obj->loadByCod($cod);

        $exec = parent::Deletar($Conexao, $cod);

        $obj->objIcon()->Delete();

        return $exec;
    }

// </editor-fold>

    private static function getAutoFormFieldOptions($Conexao, $root = 0, $nivel = 0, $value = "") {
        $where = new dataFilter("jqueryadminmenu.codmenu", $root);
        $where->add("jqueryadminmenu.cod", 0, dataFilter::op_different);
        $orderBy = new dataOrder("jqueryadminmenu.ordem");

        $dados = dbJqueryadminmenu::ObjsList($Conexao, $where, $orderBy);

        if ($dados === false) {
            return "";
        } else {
            $s = "";
            foreach ($dados as $obj) {
                if ($value == $obj->getCod()) {
                    $valuestr = " selected";
                } else {
                    $valuestr = "";
                }

                $nivelstr = "";
                for ($index = 0; $index < $nivel; $index++) {
                    $nivelstr .= "--";
                }
                $s .= "<option value='{$obj->getCod()}' $valuestr>$nivelstr{$obj->getTitulo()}</option>";

                $subdados = $obj->obtemJqueryadminmenuRel("jqueryadminmenu.ordem");
                if ($subdados !== false) {
                    $s .= self::getAutoFormFieldOptions($Conexao, $obj->getCod(), $nivel + 1);
                }
            }
        }

        return $s;
    }

    public static function getAutoFormField($label, $name, $value, $validate, $Conexao, $root = 0, $nivel = 0, $span = '', $add = '') {
        //Obtem os options
        $options = self::getAutoFormFieldOptions($Conexao, $root, $nivel, $value);

        //Cria o select
        $add = autoform2::retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");
        $s = autoform2::FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = autoform2::retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";

        $s .= "<select id='$name' name='$name' class='$validateString {$add['class']}' {$add['add']} >\n";
        if ($root == 0)
            $s .= "<option value='0'>Root</option>";
        $s .= $options;
        $s .= "\t\t</select>";

        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= autoform2::retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= "\n\t\t<script> $().ready(function() { $('#$name').val('$value'); });</script>\t\t";
        $s .= autoform2::FieldOut();
        return $s;
    }

    public static function getMenu($Conexao, $adm_folder, $root = 0, $nivel = 0, $template = null) {
        if (!isset($template)) {
            $template = array(
                "htmlstart" => "<ul class='#ulclass#'>\n",
                "htmlend" => "</ul>",
                "li" => "\n\t<li><a href='getLink'>getTitulo</a></li>",
                "lisub" => "\n\t<li class='dropdown'><a href='getLink' class='dropdown-toggle' data-toggle='dropdown'>getTitulo<b class='caret'></b></a>\n\t\t #getmenu# \n\t</li>",
                "nivelclass" => array(
                    0 => "nav",
                    1 => "dropdown-menu",
                    2 => "dropdown-submenu"
                )
            );
        }

        if ($nivel >= count($template["nivelclass"])) {
            $ulclass = end($template["nivelclass"]);
        } else {
            $ulclass = $template["nivelclass"][$nivel];
        }

        $where = new dataFilter("jqueryadminmenu.codmenu", $root);
        $where->add("jqueryadminmenu.cod", 0, dataFilter::op_different);
        $orderBy = new dataOrder("jqueryadminmenu.ordem");
        $dados = dbJqueryadminmenu::ObjsList($Conexao, $where, $orderBy);

        if ($dados === false) {
            return "";
        } else {
            $s = str_replace("#ulclass#", $ulclass, $template["htmlstart"]);
            foreach ($dados as $obj) {
                $subdados = $obj->obtemJqueryadminmenuRel("jqueryadminmenu.ordem");
                if ($subdados === false) {
                    $s .= $obj->getHtmlTemplateString($template["li"]);
                } else {
                    $sub = $obj->getHtmlTemplateString($template["lisub"]);
                    $s .= str_replace("#getmenu#", self::getMenu($Conexao, $adm_folder, $obj->getCod(), $nivel + 1, $template), $sub);
                }
            }
            $s.= $template["htmlend"];
            return $s;
        }
    }

    public static function getMenuValidate($Conexao, $adm_folder, $root = 0, $nivel = 0, $template = null, $currentUser = null) {
        if (!isset($currentUser)) {
            global $currentUser;
        }
        if (!isset($template)) {
            $template = array(
                "htmlstart" => "<ul class='#ulclass#'>\n",
                "htmlend" => "</ul>",
                "li" => "\n\t<li><a href='getLink'>getTitulo</a></li>",
                "lisub" => "\n\t<li class='dropdown'><a href='getLink' class='dropdown-toggle' data-toggle='dropdown'>getTitulo<b class='caret'></b></a>\n\t\t #getmenu# \n\t</li>",
                "nivelclass" => array(
                    0 => "nav",
                    1 => "dropdown-menu",
                    2 => "dropdown-submenu"
                )
            );
        }

        if ($nivel >= count($template["nivelclass"])) {
            $ulclass = end($template["nivelclass"]);
        } else {
            $ulclass = $template["nivelclass"][$nivel];
        }

        $where = new dataFilter("jqueryadminmenu.codmenu", $root);
        $where->add("jqueryadminmenu.cod", 0, dataFilter::op_different);
        $orderBy = new dataOrder("jqueryadminmenu.ordem");
        $dados = dbJqueryadminmenu::ObjsList($Conexao, $where, $orderBy);

        if ($dados === false) {
            return "";
        } else {
            $s = str_replace("#ulclass#", $ulclass, $template["htmlstart"]);
            foreach ($dados as $obj) {
                if ($currentUser->validatePermissions($obj->getLink($adm_folder))) {
                    $subdados = $obj->obtemJqueryadminmenuRel("jqueryadminmenu.ordem");
                    if ($subdados === false) {
                        $s .= $obj->getHtmlTemplateString($template["li"]);
                    } else {
                        $sub = $obj->getHtmlTemplateString($template["lisub"]);
                        $s .= str_replace("#getmenu#", self::getMenu($Conexao, $adm_folder, $obj->getCod(), $nivel + 1, $template), $sub);
                    }
                }
            }
            $s.= $template["htmlend"];
            return $s;
        }
    }

}