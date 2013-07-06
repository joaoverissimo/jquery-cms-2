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

    private $name = "";
    private $Conexao;
    private $adm_folder;
    private $cidadeDestaque;
    public $estado = "";
    public $cidade = "";
    public $bairro = "";
    public $rua = "";
    public $numero = "";
    public $complemento = "";

    function __construct($Conexao, $CtrlName, $adm_folder = "", $cidadeDestaque = "") {
        $this->name = $CtrlName;
        $this->Conexao = $Conexao;
        if ($adm_folder) {
            $this->adm_folder = $adm_folder;
        } else {
            $this->adm_folder = "/adm/";
        }
        $this->cidadeDestaque = $cidadeDestaque;
    }

    private function buildAutoFormField($label, $name, $htmlField, $span = '', $add = '') {
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
            $s .= $this->buildAutoFormField("Cidade", $name . "_estado", $html);
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
            $html = $this->buildAutoFormField("Endereço", $name . "_rua", $html);
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
            $this->estado = $_POST[$name . "_estado"];
            $this->cidade = $_POST[$name . "_cidade"];
            $this->bairro = $_POST[$name . "_bairro"];
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

}

class CtrlLocalizacaoCep extends CtrlLocalizacao {
    
}