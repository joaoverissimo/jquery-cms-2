<?php

require_once "base/dbaseLocestado.php";

class dbLocestado extends dbaseLocestado {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $nome, $uf, $ibge, $die = false) {

        return parent::Inserir($Conexao, $nome, $uf, $ibge, $die);
    }

    public static function Update($Conexao, $cod, $nome, $uf, $ibge, $die = false) {

        return parent::Update($Conexao, $cod, $nome, $uf, $ibge, $die);
    }

    public static function Deletar($Conexao, $cod) {
        /* $obj = new objLocestado($Conexao);
          $obj->loadByCod($cod);

         */

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}

class CtrlLocalizacao {

    protected $name = "";
    protected $Conexao;
    protected $adm_folder;
    protected $cidadeDestaque;
    public $estado = "";
    public $cidade = "";
    public $bairro = "";
    public $rua = "";
    public $numero = "";
    public $complemento = "";

    function __construct($Conexao, $CtrlName = "ctrl_loc", $adm_folder = "", $cidadeDestaque = "") {
        $this->name = $CtrlName;
        $this->Conexao = $Conexao;
        if ($adm_folder) {
            $this->adm_folder = $adm_folder;
        } else {
            $this->adm_folder = "/adm/";
        }
        $this->cidadeDestaque = $cidadeDestaque;
    }

    protected function buildAutoFormField($label, $name, $htmlField, $span = '', $add = '') {
        $add = autoform2::retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");
        $s = autoform2::FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";

        $s.= $htmlField;

        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= autoform2::retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= autoform2::FieldOut();
        return $s;
    }

    public function getAutoFormField($exibeEstado = true, $exibeCidade = true, $exibeBairro = true, $exibeRua = true, $exibeNumero = true, $exibeComplemento = true) {
        $Conexao = $this->Conexao;
        $s = "";
        $validateString = autoform2::retornarValidate(1);
        $name = $this->name;
        if ($exibeEstado && $exibeCidade) {
            $html = autoform2::retornarSelectDb($name . "_estado", $this->estado, $validateString . " span1", $Conexao, "locestado", "cod", "uf", "", "nome asc", "Selecione...");
            $html .= " " . autoform2::retornarSelectDb($name . "_cidade", $this->cidade, $validateString . " span2", $Conexao, "loccidade", "cod", "nome", new dataFilter("loccidade.estado", $this->estado), "nome asc", "Selecione...");
            $s .= $this->buildAutoFormField("Estado e Cidade", $name . "_estado", $html);
        } elseif (!$exibeEstado && $exibeCidade) {
            $html = "<input type='hidden' name='{$name}_estado' id='{$name}_estado' value='{$this->estado}'></input>";
            $html .= " " . autoform2::retornarSelectDb($name . "_cidade", $this->cidade, $validateString, $Conexao, "loccidade", "cod", "nome", new dataFilter("loccidade.estado", $this->estado), "nome asc", "Selecione...");
            $s .= $this->buildAutoFormField("Cidade", $name . "_estado", $html);
        } elseif (!$exibeEstado && !$exibeCidade) {
            $html = "<input type='hidden' name='{$name}_estado' id='{$name}_estado' value='{$this->estado}'></input>";
            $html .= "<input type='hidden' name='{$name}_cidade' id='{$name}_cidade' value='{$this->cidade}'></input>";
            $s .= $html;
        }

        if ($exibeBairro) {
            $html = autoform2::retornarSelectDb($name . "_bairro", $this->bairro, $validateString, $Conexao, "locbairro", "cod", "nome", new dataFilter("locbairro.cidade", $this->cidade), "nome asc", "Selecione");
            $s.= $this->buildAutoFormField("Bairro", $name . "_bairro", $html);
        } else {
            $html = "<input type='hidden' name='{$name}_bairro' id='{$name}_bairro' value='{$this->bairro}'></input>";
            $s .= $html;
        }

        if ($exibeRua && $exibeNumero) {
            $html = "<input type='text' name='{$name}_rua' id='{$name}_rua' class='$validateString span2' value='{$this->rua}'></input> ";
            $html .= "<input type='text' name='{$name}_numero' id='{$name}_numero' class='$validateString span1' value='{$this->numero}'></input>";
            $html = $this->buildAutoFormField("Endereço, Número", $name . "_rua", $html);
            $s .= $html;
        } elseif ($exibeRua && !$exibeNumero) {
            $html = "<input type='text' name='{$name}_rua' id='{$name}_rua' class='$validateString' value='{$this->rua}'></input> ";
            $html .= "<input type='hidden' name='{$name}_numero' id='{$name}_numero' value='{$this->numero}'></input>";
            $html = $this->buildAutoFormField("Rua", $name . "_rua", $html);
            $s .= $html;
        } elseif (!$exibeRua && !$exibeNumero) {
            $html = "<input type='hidden' name='{$name}_rua' id='{$name}_rua' value='{$this->rua}'></input>";
            $html .= "<input type='hidden' name='{$name}_numero' id='{$name}_numero' value='{$this->numero}'></input>";
            $s .= $html;
        }

        if ($exibeComplemento) {
            $html = "<input type='text' name='{$name}_complemento' id='{$name}_complemento' class='' value='{$this->complemento}'></input> ";
            $html = $this->buildAutoFormField("Complemento", $name . "_complemento", $html);
            $s .= $html;
        } else {
            $html = "<input type='hidden' name='{$name}_complemento' id='{$name}_complemento' value='{$this->complemento}'></input>";
            $s .= $html;
        }

        return $s;
    }

    public function PostRecebeValores() {
        $name = $this->name;
        if (isset($_POST[$name . "_estado"]) && isset($_POST[$name . "_cidade"]) && isset($_POST[$name . "_bairro"]) && isset($_POST[$name . "_rua"]) && isset($_POST[$name . "_numero"]) && isset($_POST[$name . "_complemento"])) {
            $this->estado = $_POST[$name . "_estado"] ? $_POST[$name . "_estado"] : null;
            $this->cidade = $_POST[$name . "_cidade"] ? $_POST[$name . "_cidade"] : null;
            $this->bairro = $_POST[$name . "_bairro"] ? $_POST[$name . "_bairro"] : null;
            $this->rua = $_POST[$name . "_rua"];
            $this->numero = $_POST[$name . "_numero"];
            $this->complemento = $_POST[$name . "_complemento"];
        }
    }

    public function getHead() {
        $name = $this->name;
        $cidadeDestaque = $this->cidadeDestaque;
        $adm_folder = $this->adm_folder;

        echo "<script>
        
            function {$name}_destaque() {
                var s = '$cidadeDestaque';
				if (s) {
					var start = false;
					var sp = s.split(',');
					for (var i = 0; i < sp.length; i++) {
						var value = $.trim(sp[i]);
						if (start === false) {
							$('#{$name}_cidade option[value='+ value +']').insertBefore($('#{$name}_cidade option:eq(1)'));     
						} else {
							$('#{$name}_cidade option[value='+ value +']').insertAfter($('#{$name}_cidade option[value='+ start +']'));
						}

						start = value;
					}
					
					$('#{$name}_cidade option[value='+ start +']').after('<option disabled>------------------</option>');
				}
            }
            
            $(document).ready(function(){
                $('#{$name}_estado').change(function(){
                    var e = $('#{$name}_estado').val();
                    var c = $('#{$name}_cidade').val();
                    $('#{$name}_cidade').load('$adm_folder/locestado/ctrl/ajax.php?estado='+ e +'&current=' + c, function() {{$name}_destaque();});
                    $('#{$name}_bairro').html('<option>Selecione...</option>');                    
                });

                $('#{$name}_cidade').change(function(){
                    var e = $('#{$name}_cidade').val();
                    var c = $('#{$name}_bairro').val();
                    $('#{$name}_bairro').load('$adm_folder/locestado/ctrl/ajax.php?cidade='+ e +'&current=' + c);
                });
                
                {$name}_destaque();
            });
            </script>
            ";
    }

    public function getCtrl($exibeEstado = true, $exibeCidade = true, $exibeBairro = true, $exibeRua = true, $exibeNumero = true, $exibeComplemento = true) {
        return $this->getAutoFormField($exibeEstado, $exibeCidade, $exibeBairro, $exibeRua, $exibeNumero, $exibeComplemento);
    }

}

class CtrlLocalizacaoV2 extends CtrlLocalizacao {

    public $registro = false;
    public $cep = "";

    public function getCtrl($exibeCep = true, $exibeEstado = true, $exibeCidade = true, $exibeBairro = true, $exibeRua = true, $exibeNumero = true, $exibeComplemento = true, $exibeNaoSeiCep = false) {
        $validateString = autoform2::retornarValidate(1);
        $name = $this->name;

        if ($exibeCep) {
            $html = "<input type='text' name='{$name}_cep' id='{$name}_cep' class='$validateString' value='{$this->cep}' maxlength='8'></input> ";
            if ($exibeNaoSeiCep) {
                $html .= "<a id='{$name}_cepLink' href='#'>Nao sei o cep</a><script>$(document).ready(function(){ $('#{$name}_cepLink').click(function(){ $('#{$name}_cep').closest('.control-group').after(\"<input type='hidden' name='ctrl_loc_cep'>\"); $('#{$name}_cep').closest('.control-group').remove(); return false; }); });</script>";
            }
            $s = $this->buildAutoFormField("Cep", $name . "_cep", $html);
        } else {
            $html = "<input type='hidden' name='{$name}_cep' id='{$name}_cep' value='{$this->cep}'></input>";
            $s = $html;
        }

        $s .= parent::getCtrl($exibeEstado, $exibeCidade, $exibeBairro, $exibeRua, $exibeNumero, $exibeComplemento);
        return $s;
    }

    public function getHead() {
        $name = $this->name;
        $cidadeDestaque = $this->cidadeDestaque;
        $adm_folder = $this->adm_folder;

        $s = parent::getHead();
        $s .= "<script>
            $(document).ready(function(){
                $('#{$name}_cep').bind('paste', function () {
                    var \$this = $(this);
                    \$this.attr('maxlength', 255);
                    
                    setTimeout(function () {
                        var text = \$this.val().replace(/\D/g,'').substr(0,8);
                        \$this.val(text);
                        \$this.attr('maxlength', 8);
                    }, 100);
                });
                
                $('#{$name}_cep').bind('keyup change',function(){
                    var \$this = $(this);
                    \$this.val(\$this.val().replace(/\D/g,''));
                    if (\$this.val().length == 8){
                        $.ajax({
                            url: '/adm/locestado/ctrl/ajax-cep.php', 
                            data: {cep: \$this.val()}
                        }).done(function(data){
                            data = $.parseJSON(data);
                            if (data.status) {
                                $('#{$name}_rua').val(data.rua);
                                $('#{$name}_estado').val(data.estado);
                                $('#{$name}_cidade').load('/adm/locestado/ctrl/ajax.php?estado='+ data.estado +'&current=' + data.cidade, function() {
                                    {$name}_destaque();
                                    $('#{$name}_bairro').load('/adm/locestado/ctrl/ajax.php?cidade='+ data.cidade +'&current=' + data.bairro);
                                });
                                
                                if (!$('#{$name}_numero').is(':focus')) {
                                    $('#{$name}_numero').focus();
                                }
                            }
                        });
                    }
                });
            });
        </script>";
        return $s;
    }

    public function loadByParams($cep, $estado, $cidade, $bairro, $rua, $numero, $complemento) {
        $this->cep = $cep;
        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->rua = $rua;
        $this->numero = $numero;
        $this->complemento = $complemento;
    }

    public function loadByCod($cod, $campo = 'cod', $die = true) {
        $registro = objLocalizacao::init($cod, $campo, $die);
        $this->cep = $registro->getCep();
        $this->estado = $registro->getEstado();
        $this->cidade = $registro->getCidade();
        $this->bairro = $registro->getBairro();
        $this->rua = $registro->getRua();
        $this->numero = $registro->getNumero();
        $this->complemento = $registro->getComplemento();

        $this->registro = $registro;
    }

    public function PostRecebeValores() {
        $name = $this->name;

        if (isset($_POST[$name . "_cep"]) && isset($_POST[$name . "_cidade"]) && isset($_POST[$name . "_bairro"]) && isset($_POST[$name . "_rua"]) && isset($_POST[$name . "_numero"]) && isset($_POST[$name . "_complemento"])) {
            $this->cep = $_POST[$name . "_cep"];
        }

        return parent::PostRecebeValores();
    }

    public function SaveByPost() {
        $registro = $this->registro;
        $this->PostRecebeValores();

        if ($registro === false) {
            //Insere
            $exec = dbLocalizacao::Inserir($this->Conexao, $this->rua, $this->numero, $this->complemento, $this->cep, $this->bairro, $this->cidade, $this->estado);
            $this->registro = objLocalizacao::init($exec);
        } else {
            //Edita
            $registro->setRua($this->rua);
            $registro->setNumero($this->numero);
            $registro->setComplemento($this->complemento);
            $registro->setCep($this->cep);
            $registro->setBairro($this->bairro);
            $registro->setCidade($this->cidade);
            $registro->setEstado($this->estado);
            $registro->Save();
        }

        return $this->registro->getCod();
    }

}
