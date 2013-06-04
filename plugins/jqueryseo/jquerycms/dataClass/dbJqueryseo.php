<?php

require_once "base/dbaseJqueryseo.php";

class dbJqueryseo extends dbaseJqueryseo {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $titulo, $descricao, $die = false) {
        $titulo = str_replace("'", "`", $titulo);
        $titulo = str_replace('"', "`", $titulo);

        $descricao = str_replace("'", "`", $descricao);
        $descricao = str_replace('"', "`", $descricao);

        return parent::Inserir($Conexao, $titulo, $descricao, $die);
    }

    public static function Update($Conexao, $cod, $titulo, $descricao, $die = false) {
        $titulo = str_replace("'", "`", $titulo);
        $titulo = str_replace('"', "`", $titulo);

        $descricao = str_replace("'", "`", $descricao);
        $descricao = str_replace('"', "`", $descricao);

        return parent::Update($Conexao, $cod, $titulo, $descricao, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $where = new dataFilter(dbJqueryseorel::_seo, $cod);
        dbJqueryseorel::DeletarWhere($Conexao, $where);

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>

    public static function obtemKeys($Conexao, $seo) {
        $where = new dataFilter(dbJqueryseorel::_seo, $seo);
        $orderBy = new dataOrder(dbJqueryseorel::_cod, "ASC");
        $dados = dbJqueryseorel::ObjsListLeft($Conexao, $where, $orderBy);

        return $dados;
    }

    public static function obtemKeysVirgula($Conexao, $seo) {
        $dados = self::obtemKeys($Conexao, $seo);

        $s = array();
        if ($dados) {
            foreach ($dados as $obj) {
                $s[] = $obj->objPalavra()->getPalavra();
            }
        }

        return join(",", $s);
    }

    public static function DeletarAllKeysOfSeo($Conexao, $seo) {
        $dados = self::obtemKeys($Conexao, $seo);

        if ($dados) {
            foreach ($dados as $obj) {
                $obj->Delete();
            }
        }
    }

}

class CtrlJquerySeo {

    private $seocod;
    private $objseo;
    private $ctrlname;
    private $Conexao;
    public $keyTitle;

    function __construct($Conexao, $seocod = 0, $ctrlname = "jqueryseoctrl") {
        $this->seocod = $seocod;
        $this->Conexao = $Conexao;
        $this->ctrlname = $ctrlname;

        $this->loadSeo();
    }

    private function loadSeo() {
        if ($this->seocod) {
            $objseo = new objJqueryseo($this->Conexao, false);
            $objseo->loadByCod($this->seocod);
            $this->objseo = $objseo;
        }
    }

    public function getCtrl() {
        $ctrlname = $this->ctrlname;

        $palavras = dbJqueryseopalavra::ObjsList($this->Conexao, "", new dataOrder(dbJqueryseopalavra::_count, "desc"), "0,20");

        $html = "<div class='{$ctrlname}'>
                    <div class='control-group'>
                        <label class='control-label' for='titulo'>SEO: </label>
                        <div class='controls'>
                            <div class='resumo'>
                                <a href='javascript:void(0);' onclick=\"$('.{$ctrlname}_ctrl').slideToggle();\" class='btn'>Otimização para sistemas de busca</a>
                            </div>
                            <div class='{$ctrlname}_ctrl'>
                                <label>
                                    <span class='titulo'>Titulo</span>
                                    <input type='text' id='{$ctrlname}_titulo' name='{$ctrlname}_titulo' value='' placeholder='Titulo'>
                                </label>
                                <label>
                                    <span class='titulo'>Descrição</span>
                                    <textarea name='{$ctrlname}_descricao' id='{$ctrlname}_descricao' rows='5' placeholder='Descrição'></textarea>
                                </label>
                                <label>
                                    <span class='titulo'>Palavras-chave</span>
                                    <input type='text' id='{$ctrlname}_palavrainput' name='{$ctrlname}_palavra' value='' placeholder='Digite aqui uma keyword...'>
                                    <button type='button' class='btn btn-primary btn-mini' id='{$ctrlname}_addkey' rel='tooltip' title='Adicionar Palavra-Chave' tabIndex='-1'><i class='icon-plus icon-white'></i></button>
                                    ";
        if ($palavras) {
            $html .= "<button type='button' class='btn btn-primary btn-mini' id='{$ctrlname}_showcomum' rel='tooltip' title='Adicionar Palavra-Chave' tabIndex='-1'><i class='icon-tags icon-white'></i></button>";
        }
        $html .= "
                                </label>
                                <div id='{$ctrlname}_palavra_container'></div>
                                    ";
        if ($palavras) {
            $html .= "<div id='{$ctrlname}_comum'>";
            foreach ($palavras as $palavra) {
                $html .= "<span onclick=\"{$ctrlname}_addKeyword(('{$palavra->getPalavra()}'));\">{$palavra->getPalavra()}</span>";
            }
            $html .= "</div>";
        }

        $html .= "          </div>
                        </div>
                    </div>
                </div>";
        return $html;
    }

    public function getHead() {
        $ctrlname = $this->ctrlname;
        $tags = "";
        $titulo = "";
        $descricao = "";

        if ($this->seocod) {
            $tags = $this->objseo->getKeysVirgula($this->Conexao, $this->seocod);
            $titulo = $this->objseo->getTitulo();
            $descricao = $this->objseo->getDescricao();
        }
        $html = "<script>
                    function {$ctrlname}_addKeyword(key) {
                        key = key.trim();
                        
                        if (key) {
                            var \$container = $('#{$ctrlname}_palavra_container');
                            var \$a = \"<span class='palavra'>\"+ key +\"<input type='hidden' name='{$ctrlname}_key[]' value='\"+ key +\"'></input><a class='close' href='javascript:void(0);' tabIndex='-1'>&times;</a></span>\";

                            \$container.append(\$a);
                            $('#{$ctrlname}_palavra_container .palavra a.close').click(function(){
                                \$(this).closest('.palavra').remove();
                            });
                        }
                    }
                    
                    function {$ctrlname}_addKeywordsGroup (){
                        var palavras = $('#{$ctrlname}_palavrainput').val().split(',');
                        
                        for (var i in palavras){
                            {$ctrlname}_addKeyword(palavras[i]);
                            i ++;
                        }
                        
                        $('#{$ctrlname}_palavrainput').val('');
                        return false;
                    }
                    
                    $(document).ready(function(){
                        $('#{$ctrlname}_palavrainput').keypress(function(e) {
                            if(e.keyCode == 13 || e.keyCode == 9) {
                                {$ctrlname}_addKeywordsGroup();
                                $('#{$ctrlname}_palavrainput').autocomplete('close');
                                return false;
                            }
                        });
                        
                        /*$('#{$ctrlname}_palavrainput').focusout(function(){
                            {$ctrlname}_addKeywordsGroup();
                        });*/
                        
                        $('#{$ctrlname}_palavrainput').autocomplete({
                            source: '/adm/jqueryseo/ctrl/ajax.php',
                            minLength: 3,
                            select: function( event, ui ) {
                                {$ctrlname}_addKeyword(ui.item.value);
                                $('#{$ctrlname}_palavrainput').val('');
                                return false;
                            }
                        });
                        
                        $('#{$ctrlname}_addkey').click(function(){
                            {$ctrlname}_addKeywordsGroup();
                        });
                        
                        $('#{$ctrlname}_palavra_container .palavra a.close').click(function(){
                            $(this).closest('.palavra').remove();
                        });
                        
                        $('#{$ctrlname}_showcomum').click(function(){
                            $('#{$ctrlname}_comum').slideToggle();
                        });
                        
                        $('#{$ctrlname}_palavra_container').sortable({});
                        $('#{$ctrlname}_palavra_container').disableSelection();
                        ";
        if ($this->seocod) {
            $html.= "
                $('#{$ctrlname}_titulo').val('$titulo');
                $('#{$ctrlname}_descricao').val('$descricao');
                $('#{$ctrlname}_palavrainput').val('$tags');
            
                {$ctrlname}_addKeywordsGroup();
                ";
        }
        $html .= "
                    });
                </script>
                <style>
                    .{$ctrlname} {}
                    .{$ctrlname} .controls {}
                    .{$ctrlname} .controls .resumo {}
                    .{$ctrlname} .controls .{$ctrlname}_ctrl {display: none;border: 1px #ECECEC solid; background: #FAFAFA; padding: 10px;position: relative;}
                    .{$ctrlname} .controls .{$ctrlname}_ctrl label {}
                    .{$ctrlname} .controls .{$ctrlname}_ctrl label span.titulo {width: 120px; display: block; float: left; text-align: right; padding-right: 10px;color: #999;}
                    .{$ctrlname} .controls #{$ctrlname}_palavra_container .palavra {display: inline-block; margin: 3px; margin-right: 10px; padding: 2px; height: 25px;}
                    .{$ctrlname} .controls #{$ctrlname}_comum {display: none;width: 420px; height: 155px; position: absolute; top: 7px; right: 7px; overflow: hidden;}
                    .{$ctrlname} .controls #{$ctrlname}_comum span {float: left; display: block; padding: 1px 2px 2px 4px; margin-left: 5px; margin-bottom: 2px; border-right: 1px solid rgb(202, 202, 202); border-bottom: 1px solid rgb(202, 202, 202); background: rgb(238, 238, 238); cursor: pointer; font-size: 12px; font-weight: bold; font-family: Arial;}
                </style>";

        return $html;
    }

    private function keysSave() {
        $ctrlname = $this->ctrlname;

        if (isset($_POST[$ctrlname . "_key"]) && issetArray($_POST[$ctrlname . "_key"])) {
            foreach ($_POST[$ctrlname . "_key"] as $palavrastr) {
                dbJqueryseorel::InserirByKeyString($this->Conexao, $this->seocod, $palavrastr);
            }
        }
    }

    public function inserirByPost() {
        $ctrlname = $this->ctrlname;

        //Insere o novo seo
        $objseo = new objJqueryseo($this->Conexao);
        $objseo->setTitulo(issetpost($ctrlname . "_titulo"));
        $objseo->setDescricao(issetpost($ctrlname . "_descricao"));
        $objseo->Save();

        $this->seocod = $objseo->getCod();
        $this->loadSeo();

        //Insere as keys
        $this->keysSave();

        //Adiciona uma key com o titulo
        dbJqueryseorel::InserirByKeyString($this->Conexao, $objseo->getCod(), $objseo->getTitulo());
        if (isset($this->keyTitle) && $this->keyTitle) {
            dbJqueryseorel::InserirByKeyString($this->Conexao, $objseo->getCod(), $this->keyTitle);
        }

        return $objseo->getCod();
    }

    public function updateByPost() {
        $ctrlname = $this->ctrlname;

        //Edita o seo atual
        $objseo = $this->objseo;
        $objseo->setTitulo(issetpost($ctrlname . "_titulo"));
        $objseo->setDescricao(issetpost($ctrlname . "_descricao"));
        $objseo->Save();

        //Deleta todas as keys do seo atual
        dbJqueryseo::DeletarAllKeysOfSeo($this->Conexao, $this->seocod);

        //Insere as keys
        $this->keysSave();

        return $this->seocod;
    }

}

class CtrlJquerySeoBusca {

    public static function getCtrl($ctrlName, $default_value = "") {
        $html = "
            <script>
                $(document).ready(function(){
                    $('#$ctrlName input').autocomplete({
                        source: '/adm/jqueryseo/ctrl/ajax.php',
                        minLength: 3
                    });
                });
            </script>
            <form class='form-search' method='get' id='$ctrlName' action='/seobusca.php'>
                <label>
                    <input type='text' id='palavra' name='palavra' value='$default_value' class='input-medium search-query' x-webkit-speech></input>
                </label>
                <button type='submit' class='btn'>Buscar</button>
            </form>
            ";

        return $html;
    }

}