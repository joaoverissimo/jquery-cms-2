<?php

require_once 'autoform2-tabs.php';

class autoform2 {

    private $formOut = null;
    private $javascript = null;
    private $headJquery = false;
    private $headJavaScriptFiles = true;
    private $srcJsFiles = '/jquerycms/js';
    private $autoformFolderTemplate;
    public $formName;

    function __construct($autoformFolderTemplate = '') {
        if (!$autoformFolderTemplate)
            $this->autoformFolderTemplate = ___AppRoot . 'jquerycms/lib/autoform2/templates/';
    }

    public function Configurar($headJquery, $headJavaScriptFiles = true, $srcJsFiles = '/jquerycms/js') {
        $this->headJquery = $headJquery;
        $this->headJavaScriptFiles = $headJavaScriptFiles;
        $this->srcJsFiles = $srcJsFiles;
    }

    public function getForm() {
        return $this->formOut;
    }

    private function retornarJavaScriptFiles() {
        $scripts = '';
        $parametros = array('$srcJsFiles' => $this->srcJsFiles, '$sisVersao' => ___sisVersao);

        if ($this->headJavaScriptFiles === true) {

            if ($this->headJquery === true)
                $scripts = stringuize("jquery.html", $parametros, $this->autoformFolderTemplate);

            $scripts .= stringuize("head.html", $parametros, $this->autoformFolderTemplate);
        }

        return $scripts;
    }

    public function getHead($headJquery = false, $headJavaScriptFiles = true, $srcJsFiles = '/jquerycms/js') {
        $this->Configurar($headJquery, $headJavaScriptFiles, $srcJsFiles);
        $s = $this->retornarJavaScriptFiles();
        $s .= stringuize("headScriptBlock.html", array('$valor' => $this->javascript, '$sisVersao' => ___sisVersao), $this->autoformFolderTemplate);

        return $s;
    }

    public static function FieldIn($nameId = "", $addClass = "") {
        if ($nameId) {
            $nameId = "id='field-$nameId'";
        }

        return "\n\t<div class='form-group $addClass' $nameId>";
    }

    public static function FieldOut() {
        return "\n\t</div>\n";
    }

    public static function retornarSpan($span, $spanclass = '') {
        if ($spanclass == '')
            $spanclass = 'help-inline';

        if ($span)
            return "\n\t\t<span class='$spanclass'>$span</span>";
        else
            return "";
    }

    public static function retornarValidateLabel($label, $validate) {
        if ($validate == 1) {
            //required
            $s = "*";
        } elseif ($validate == 2) {
            //required number
            $s = "*";
        } elseif ($validate == 3) {
            //number
            $s = "";
        } elseif ($validate == 4) {
            //required email
            $s = "*";
        } elseif ($validate == 5) {
            //email
            $s = "";
        } elseif ($validate == 6) {
            //required url
            $s = "*";
        } elseif ($validate == 7) {
            //url
            $s = "";
        } elseif ($validate == 8) {
            //digits
            $s = "";
        } elseif ($validate == 9) {
            //brDate
            $s = "";
        } elseif ($validate == 10) {
            //required brDate
            $s = "*";
        } elseif ($validate == 11) {
            //brTime
            $s = "";
        } elseif ($validate == 12) {
            //required brTime
            $s = "*";
        } elseif ($validate === "validate='required:true'") {
            $s = "*";
        } else {
            $s = "";
        }

        if ($s) {
            return "$label<span class='spn-required'>*</span>";
        }

        return $label;
    }

    public static function retornarValidate($validate) {
        switch ($validate) {
            case 1:
                return "required";
                break;

            case 2:
                return "required number";
                break;

            case 3:
                return "number";
                break;

            case 4:
                return "required email";
                break;

            case 5:
                return "email";
                break;

            case 6:
                return "required url";
                break;

            case 7:
                return "url";
                break;

            case 8:
                return "digits";
                break;

            case 9:
                return "brDate";
                break;

            case 10:
                return "required brDate";
                break;

            case 11:
                return "brTime";
                break;

            case 12:
                return "required brTime";
                break;

            default:
                return "";
                break;
        }
    }

    public static function retornarAddArray($add, $values) {
        $arr = array();
        $values = explode(",", $values);
        $values = array_map('trim', $values);

        if (issetArray($add)) {
            foreach ($values as $value) {
                if (isset($add[$value])) {
                    $arr[$value] = $add[$value];
                } else {
                    $arr[$value] = "";
                }
            }

            if (!isset($arr["add"])) {
                $arr["add"] = "";
            }
        } else {
            foreach ($values as $value) {
                $arr[$value] = "";
            }

            $arr["add"] = $add;
        }

        //maxlength
        if (isset($arr['maxlength']) && $arr['maxlength']) {
            $arr['maxlength'] = "maxlength='{$arr['maxlength']}'";
        }

        //type
        if (isset($arr['type']) && !$arr['type']) {
            $arr['type'] = "text";
        }

        return $arr;
    }

    function start($name, $action, $method, $add = '') {
        $add = $this->retornarAddArray($add, "add, class, onready");
        if (!$add['class']) {
            $add['class'] = 'form-horizontal';
        }

        if (!$action) {
            $action = Fncs_GetCurrentURL();
        }

        $form = "<form name='$name' id='$name' action='$action' method='$method' class='{$add['class']}' enctype='multipart/form-data' {$add['add']} >\n";

        $this->formOut = $form;
        $this->javascript = ' $(document).ready(function() {$("#' . $name . '").validate({' . "
            highlight: function(label) {
                $(label).siblings('.help-inline').removeClass('help-inline').addClass('help-block');
                $(label).closest('.form-group').addClass('has-error');
              },
              success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                if (label.text() == '') {
                    label.remove();
                }
              }
            }); 
        }); ";
        $this->formName = $name;
    }

    function fieldset($legend = '', $id = null) {
        if (!isset($id)) {
            $id = "fieldset-" . toRewriteString($legend);
        }

        $s = "<fieldset id='$id'>";

        if ($legend)
            $s .= "<legend>$legend:</legend>";

        $this->formOut .= $s;
    }

    function fieldsetOut() {
        $s = "</fieldset>";
        $this->formOut .= $s;
    }

    function text($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $nameId = self::returnInputId($name);
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='{$add['type']}' id='$nameId' name='$name' value='$value' class='$validateString {$add['class']} form-control' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    public static function returnInputId($name) {
        $name = str_replace("[", "-", $name);
        $name = str_replace("]", "", $name);
        return $name;
    }

    function floatReal($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $form_name = $this->formName;
        $nameId = self::returnInputId($name);
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$nameId'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='{$add['type']}' id='$nameId' name='$name' value='$value' class='$validateString {$add['class']} inputFloatReal form-control' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();

        $this->javascript .= "\n\n\t $(document).ready(function(){ $('#$nameId').val(parseFloat($('#$nameId').val().replace(',','')).toFixed(2).replace('.',',')).maskMoney({prefix:'R$ ', allowZero: true, thousands:'.', decimal:','}).maskMoney('mask'); $('#{$form_name}').submit(function(){ if ($('#$form_name').valid()) { $('#$nameId').val($('#$nameId').maskMoney('destroy').maskMoney('unmasked')[0]);} }); });";
        $this->formOut .= $s;
    }

    function floatInteger($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<div class='input-group input-group-floatInteger'><span class='input-group-addon'>R$</span>";
        $s .= "\n\t\t\t\t<input type='{$add['type']}' id='$nameId' name='$name' value='$value' class='floatInteger $validateString {$add['class']} form-control' {$add['add']} {$add['maxlength']} />";
        //$s .= "\n\t\t\t<span class='input-group-addon'>.00</span></div>";
        $s .= "\n\t\t\t</div>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        //$this->javascript .= "\n\n\t " . '$(document).ready(function(){$("#' . $name . '").keyup(function(){var e=$(this).val();var t=e.length;var n=1;var r="";for(var i=t-1;i>=0;i--){if(!isNaN(parseFloat(e[i]))&&isFinite(e[i])){r=e[i]+r;if(n==3&&i>0){n=0;r="."+r}n++}}if(r){r="R$ "+r+",00";$(this).siblings("i").text(r)}else{$(this).siblings("i").text("")}}).change(function(){var e=$(this).val();if(e.indexOf(".")!==-1||e.indexOf(",")!==-1){var t=$(this).closest(".form-group").find(".control-label").text().replace(": ", "");alert("Você digitou virgula ou ponto no campo \'"+t+"\'.")}if(e.indexOf(",00")!==-1){e=e.substr(0,e.length-3)}$(this).val(e.replace(/[^0-9]/g,""));$(this).keyup()}).after("<i></i>"); $("#' . $name . '").change();})';
    }

    function numeroInteiro($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<div class='input-group-floatInteger'>";
        $s .= "\n\t\t\t\t<input type='{$add['type']}' id='$nameId' name='$name' value='$value' class='floatInteger $validateString {$add['class']} form-control' {$add['add']} {$add['maxlength']} />";
        //$s .= "\n\t\t\t<span class='input-group-addon'>.00</span></div>";
        $s .= "\n\t\t\t</div>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        //$this->javascript .= "\n\n\t " . '$(document).ready(function(){$("#' . $name . '").keyup(function(){var e=$(this).val();var t=e.length;var n=1;var r="";for(var i=t-1;i>=0;i--){if(!isNaN(parseFloat(e[i]))&&isFinite(e[i])){r=e[i]+r;if(n==3&&i>0){n=0;r="."+r}n++}}if(r){r="R$ "+r+",00";$(this).siblings("i").text(r)}else{$(this).siblings("i").text("")}}).change(function(){var e=$(this).val();if(e.indexOf(".")!==-1||e.indexOf(",")!==-1){var t=$(this).closest(".form-group").find(".control-label").text().replace(": ", "");alert("Você digitou virgula ou ponto no campo \'"+t+"\'.")}if(e.indexOf(",00")!==-1){e=e.substr(0,e.length-3)}$(this).val(e.replace(/[^0-9]/g,""));$(this).keyup()}).after("<i></i>"); $("#' . $name . '").change();})';
    }

    function url($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        if ($validate == "1") {
            $validateString = $this->retornarValidate(1) . " urlhttp";
        } else {
            $validateString = "urlhttp";
        }
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='{$add['type']}' id='$nameId' name='$name' value='$value' class='$validateString {$add['class']} form-control' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function textPassword($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='password' id='$nameId' name='$name' class='$validateString {$add['class']} form-control' value='$value' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function select($label, $name, $value, $validate, $opt_value, $opt_txt, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "<select id='$name' name='$name' class='form-control $validateString {$add['class']}' {$add['add']} >\n";

        $opt_txt = explode(",", $opt_txt);
        $opt_value = explode(",", $opt_value);
        $qts = count($opt_txt);
        for ($i = 0; $i < $qts; $i++) {
            if ($opt_value[$i] == $value) {
                $s .= "\t\t<option selected value='" . $opt_value[$i] . "'>" . $opt_txt[$i] . "</option>\n";
            } else {
                $s .= "\t\t<option value='" . trim($opt_value[$i]) . "'>" . trim($opt_txt[$i]) . "</option>\n";
            }
        }
        $s .= "\t\t</select>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function multiselect($label, $name, $value, $validate, $opt_value, $opt_txt, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 control-bootstrap-multiselect {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "<select id='$name' multiple='multiple' name='{$name}[]' class='form-control $validateString {$add['class']} bootstrap-multiselect not-select2' {$add['add']} >\n";

        $opt_txt = explode(",", $opt_txt);
        $opt_value = explode(",", $opt_value);
        $qts = count($opt_txt);
        for ($i = 0; $i < $qts; $i++) {
            if ($value && in_array($opt_value[$i], $value)) {
                $s .= "\t\t<option selected value='" . $opt_value[$i] . "'>" . $opt_txt[$i] . "</option>\n";
            } else {
                $s .= "\t\t<option value='" . trim($opt_value[$i]) . "'>" . trim($opt_txt[$i]) . "</option>\n";
            }
        }
        $s .= "\t\t</select>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function radioInline($label, $name, $value, $validate, $opt_value, $opt_txt, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        //$s .= "<select id='$name' name='$name' class='$validateString {$add['class']}' {$add['add']} >\n";

        $opt_txt = explode(",", $opt_txt);
        $opt_value = explode(",", $opt_value);
        $qts = count($opt_txt);
        for ($i = 0; $i < $qts; $i++) {
            if ($opt_value[$i] == $value) {
                $s .= "\t\t<label class='radio-inline'><input type='radio' name='$name' id='$nameId' value='{$opt_value[$i]}' checked class='$validateString {$add['class']}' {$add['add']}>$opt_txt[$i]</label>\n";
            } else {
                $s .= "\t\t<label class='radio-inline'><input type='radio' name='$name' id='$nameId' value='{$opt_value[$i]}' class='$validateString {$add['class']}' {$add['add']}>$opt_txt[$i]</label>\n";
            }
        }
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function radioBtn($label, $name, $value, $validate, $opt_value, $opt_txt, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 control-radio-btn {$add['divcontrolsclass']}'>{$add['precontrol']}";
        //$s .= "<select id='$name' name='$name' class='$validateString {$add['class']}' {$add['add']} >\n";

        $opt_txt = explode(",", $opt_txt);
        $opt_value = explode(",", $opt_value);
        $s .= "<input type='text' name='$name' id='$nameId' value='{$value}' class='$validateString {$add['class']}' {$add['add']} style='display: none;' />";

        $qts = count($opt_txt);
        for ($i = 0; $i < $qts; $i++) {
            if ($opt_value[$i] == $value) {
                $s .= "\t\t<a href='javascript:void(0);' class='btn btn-default btn-warning btn-sm' data-value='{$opt_value[$i]}'>{$opt_txt[$i]}</a>\n";
            } else {
                $s .= "\t\t<a href='javascript:void(0);' class='btn btn-default btn-sm' data-value='{$opt_value[$i]}'>{$opt_txt[$i]}</a>\n";
            }
        }
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;

        $this->javascript .= "\n\n\t $(document).ready(function() { 
                                    $('#{$nameId}').closest('.form-group').find('.control-radio-btn .btn').click(function(){
                                       var value = $(this).attr('data-value');
                                       $(this).parent().find('.btn').removeClass('btn-warning');
                                       
                                       if (value !== $(this).parent().find('input').val()) {
                                            $(this).parent().find('input').val(value);

                                            $(this).addClass('btn-warning');
                                       } else {
                                            $(this).parent().find('input').val('');
                                       }
                                       $(this).parent().find('input').show().focus().hide(); 
                                    });                 
                                }); \n";
    }

    public static function retornarSelectDbItens($Conexao, $tabela, $campo1Val, $campo2Txt, $value, $where = '', $orderBy = '', $AdicionarValorEmBranco = '') {
        $s = "";

        if ($AdicionarValorEmBranco) {
            $s .= "\t\t<option value=''>$AdicionarValorEmBranco</option>\n";
        }

        $dados = dataListar($Conexao, $tabela, $where, $orderBy, '', "$campo1Val, $campo2Txt");
        if (!issetArray($dados)) {
            $s .= "\t\t<option value=''>Não existem registros disponíveis</option>\n";
        } else {
            foreach ($dados as $registro) {
                $key = $registro[$campo1Val];
                $valor = $registro[$campo2Txt];

                if ($value == $key) {
                    $s .= "\t\t<option selected value='$key'>$valor</option>\n";
                } else {
                    $s .= "\t\t<option value='$key'>$valor</option>\n";
                }
            }
        }

        return $s;
    }

    public static function retornarSelectDb($name, $value, $validateString, $Conexao, $tabela, $campo1Val, $campo2Txt, $where = '', $orderBy = '', $AdicionarValorEmBranco = '') {
        $nameId = self::returnInputId($name);
        $s = "<select id='$nameId' name='$name' class='$validateString' >\n";

        $s .= self::retornarSelectDbItens($Conexao, $tabela, $campo1Val, $campo2Txt, $value, $where, $orderBy, $AdicionarValorEmBranco);

        $s .= "\t\t</select>";
        return $s;
    }

    public static function selectDbInsercaoDinamica($name, $tabela, $campo1Val, $campo2Txt, $value, $where = '', $orderBy = '', $AdicionarValorEmBranco = '') {
        $nameId = self::returnInputId($name);
        $s = "";

        $insercaoDinamica = ['tabela' => $tabela, 'campo1Val' => $campo1Val, 'campo2Txt' => $campo2Txt, 'value' => $value, 'where' => $where, 'orderBy' => $orderBy, 'AdicionarValorEmBranco' => $AdicionarValorEmBranco];
        $insercaoDinamica = serialize($insercaoDinamica);
        $insercaoDinamica = base64_encode($insercaoDinamica);
        $s.="<input id='$nameId-data-hidden' type='hidden' value='" . $insercaoDinamica . "' />";

        $s .= "<div id='$nameId-btn-group' class='btn-group btn-group-insercao-dinamica'>
                <button class='btn dropdown-toggle btn-xs btn-info' data-toggle='dropdown' type='button'><i class='fa fa-cog'></i> <span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <li><a id='$nameId-btn-adicionar' rel='iFrameNoReload' title='Inserir' href='/adm/$tabela/inserir.php?tema=branco'><i class='fa fa-plus'></i> Inserir</a></li>
                  <li><a id='$nameId-btn-editar' rel='iFrameNoReload' data-url='/adm/$tabela/editar.php' title='Editar' href='#'><i class='fa fa-pencil'></i> Editar</a></li>
                  <li><a id='$nameId-btn-recarregar' href='javascript:void(0);'><i class='fa fa-refresh'></i> Recarregar</a></li>
                </ul>
              </div>";

        $s .= "<script>"
                . "$(document).ready(function(){ "
                . "    $('#$nameId-btn-recarregar').click(function(){"
                . "      closeTheIFrameImDoneReload('$nameId');"
                . "    }); "
                . "    $('#$nameId-btn-group .dropdown-toggle').click(function(){"
                . "      var cod = $('#$nameId').val();"
                . "      if (!cod) { "
                . "         $('#$nameId-btn-editar').closest('li').hide();"
                . "      } else {"
                . "         $('#$nameId-btn-editar').closest('li').show();"
                . "         var url = $('#$nameId-btn-editar').attr('data-url');"
                . "         $('#$nameId-btn-editar').attr('href', url + '?cod='+ cod +'&tema=branco');"
                . "      }"
                . "    }); "
                . "});"
                . ""
                . "function brancoFncSave_$tabela(cod) {"
                . "  closeTheIFrameImDoneReload('$nameId', cod);"
                . "} "
                . "</script>";

        return $s;
    }

    function selectDb($label, $name, $value, $validate, $Conexao, $tabela, $campo1Val, $campo2Txt, $where = '', $orderBy = '', $AdicionarValorEmBranco = '', $span = '', $add = '') {
        $nameId = self::returnInputId($name);
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");

        $s = $this->FieldIn($nameId, "group-insercao-dinamica");
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$nameId'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";

        $s.= self::retornarSelectDb($name, $value, $validateString, $Conexao, $tabela, $campo1Val, $campo2Txt, $where, $orderBy, $AdicionarValorEmBranco);

        $s .= "{$add['poscontrol']}\n\t\t";

        $s .= self::selectDbInsercaoDinamica($name, $tabela, $campo1Val, $campo2Txt, $value, $where, $orderBy, $AdicionarValorEmBranco);

        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function selectDbHelper($name, $showBtn, $urlInserir = false, $urlEditar = false, $urlParametros = "tema=branco") {
        $nameId = self::returnInputId($name);

        if (!$showBtn) {
            $this->javascript .= "\n\n $(document).ready(function(){ $('#$nameId-btn-group').hide(); $('#field-$nameId').removeClass('group-insercao-dinamica'); });";
        }

        if ($urlInserir) {
            $urlInserir = "$urlInserir?$urlParametros";
            $this->javascript .= "\n\n $(document).ready(function(){ $('#$nameId-btn-adicionar').attr('href', '$urlInserir'); });";
        }

        if ($urlEditar) {
            $this->javascript .= "\n\n $(document).ready(function(){ $('#$nameId-btn-editar').attr('data-url', '$urlEditar'); });";
        }
    }

    function checkbox($label, $name, $value = '1', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass");
        $nameId = self::returnInputId($name);

        $checked = "";
        if ($value) {
            $checked = "checked";
        }

        if ($validate) {
            $validate = "validate='required:true'";
        }

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<div class='checkbox'><label><input type='checkbox' id='$name' name='$name' $checked {$add['add']} class='{$add['class']}' $validate >$span</label></div>\n";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function textarea($label, $name, $value = '', $validate = '0', $span = '', $add = '', $rows = '5') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<textarea name='$name' id='$name' rows='$rows' class='form-control $validateString {$add['class']}' placeholder='$label' {$add['add']} {$add['maxlength']}>" . $value . "</textarea>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function file($label, $name, $value = null, $validate = '0', $span = '', $add = '') {
        if (isset($value))
            throw new jquerycmsException("Arquivos devem possuir valor nulo!");

        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .="\n\t\t\t<input type='file' name='$name' id='$nameId' class='$validateString {$add['class']}' {$add['add']} {$add['maxlength']} /> ";
        $s .= $this->retornarSpan($span);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function fileImagems($label, $name, $value = '0', $validate = '0', $Conexao = null, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='{$name}-file'>" . self::retornarValidateLabel($label, $validate) . ": </label>";
        $s .= "\n\t\t<div class='col-md-6 FormfileImagems {$add['divcontrolsclass']}'>{$add['precontrol']}";

        if ($value != '0') {
            if (!isset($Conexao)) {
                $Conexao = CarregarConexao();
            }

            if (dbJqueryimage::Existe($Conexao, $value)) {
                $s .= "<div class='div_field_imgAtual'><img src='/img.php?img=$value&width=70&height=35&crop=1' alt='' /><span>Caso queira escolha um arquivo abaixo.</span></div>";
            }
        }


        $validateString = $this->retornarValidate($validate);
        $s .="<input type='file' name='$name-file' id='$name-file' class='$validateString {$add['class']}' {$add['add']} /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->hidden($name, $value);
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function hidden($name, $value = '', $add = '') {
        if (issetArray($add)) {
            throw new jquerycmsException("O controle hidden nao permite add em arrays, use uma string simples");
        }

        $this->formOut .= "\t<input type='hidden' name='$name' id='$name' value='$value' $add />\n";
    }

    private function telefoneGet($value) {
        $arr = array(0 => "", 1 => "");
        if (strpos($value, "-")) {
            $value = explode("-", $value);
            $value = array_map('trim', $value);
            for ($index = 0; $index < count($value); $index++) {
                if ($index == 0) {
                    $arr[0] = $value[0];
                } else {
                    $arr[1] .= $value[$index];
                }
            }
        } elseif (strlen(preg_replace("/[^0-9]/", "", $value)) > 8) {
            $value = preg_replace("/[^0-9]/", "", $value);
            $arr[1] = substr($value, -8);
            $arr[0] = str_replace($arr[1], "", $value);
        } else {
            $arr[0] = "";
            $arr[1] = $value;
        }
        return $arr;
    }

    function telefone($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $valueArr = $this->telefoneGet($value);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='{$name}DDD'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 FormTelefone {$add['divcontrolsclass']}'><div class='input-group'>{$add['precontrol']}";
        $s .= "\n\t\t<input type='text' id='{$name}DDD' class='$validateString form-field-fone-ddd {$add['class']}  form-control' value='{$valueArr[0]}' placeholder='DDD' {$add['add']} /> ";
        $s .= "\n\t\t<input type='text' id='{$name}NUM' class='$validateString form-field-fone-num {$add['class']}  form-control' value='{$valueArr[1]}' placeholder='Telefone' {$add['add']} /> ";
        $s .= "\n\t\t<input type='hidden' id='{$name}' name='{$name}' value='{$value}' />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div></div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t $(document).ready(function() { $('#{$name}DDD').change(function (){ $('#{$name}').val($('#{$name}DDD').val() + '-' + $('#{$name}NUM').val()).trigger('change'); }); $('#{$name}NUM').change(function (){ $('#{$name}').val($('#{$name}DDD').val() + '-' + $('#{$name}NUM').val()).trigger('change'); }); }); \n";
    }

    function data($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $nameId = self::returnInputId($name);
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$nameId'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$nameId' name='$name' value='$value' class='$validateString {$add['class']} form-control' placeholder='dd/mm/aaaa' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t " . 'jQuery(function($){  $("#' . $nameId . '").mask("99/99/9999");   }); ' . "\n\n";
    }

    function datatime($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t <div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$nameId' name='$name' value='$value' class='$validateString {$add['class']} form-control' placeholder='dd/mm/aaaa hh:mm:ss' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t " . 'jQuery(function($){  $("#' . $nameId . '").mask("99/99/9999 99:99");   }); ' . "\n\n";
    }

    function time($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$nameId'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$nameId' name='$name' value='$value' class='$validateString {$add['class']} form-control' placeholder='hh:mm' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t " . 'jQuery(function($){  $("#' . $nameId . '").mask("99:99");   }); ' . "\n\n";
    }

    function calendario($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>" . self::retornarValidateLabel($label, $validate) . ": </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$name' name='$name' value='$value'  class='$validateString {$add['class']} form-control' placeholder='$label' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\t $(document).ready(function(){
                                $('#$name').mask('99/99/9999');
                                $('#$name').datepicker({language: 'pt-BR', autoclose: true, todayHighlight: true});
                            });";
    }

    function capchaField($label = "", $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        if (!$label) {
            $label = "Letras de segurança";
        }

        $name = "capchaField";

        $s = $this->FieldIn($nameId);
        $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>$label: </label>";
        $validateString = $this->retornarValidate(1);
        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$name' name='$name' class='$validateString {$add['class']} form-control' placeholder='$label' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "\n\t\t\t<br /><img src='/jquerycms/lib/autoform2/capcha/captcha.php?l=150&a=50&tf=20&ql=5' alt='$name'>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    public static function capchaValidate() {
        if (!isset($_SESSION))
            session_start();

        if (isset($_POST["capchaField"]) && isset($_SESSION["captcha_palavra"])) {
            if (strtoupper($_POST["capchaField"]) == strtoupper($_SESSION["captcha_palavra"]))
                return true;
        }

        return false;
    }

    function summernoteEditor($label, $name, $value = '', $validade = false, $span = '', $add = '') {
        if ($validade) {
            $label = "$label<span class='spn-required'>*</span>";
        }
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");
        $nameId = self::returnInputId($name);

        $s = $this->FieldIn($nameId, "div_field_editor");

        if ($label)
            $s .= "\n\t\t<label class='col-md-3 control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $s .= "\n\t\t<div class='col-md-6 {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "<textarea name='$name' id='$name' class='summernoteEditor'>$value</textarea>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= "</div>";
        $this->formOut .= $s;

        $template = "fckeditor_no_validate.html";
        if ($validade) {
            $template = "fckeditor_validate.html";
        }

        $this->javascript .= "
            $(document).ready(function(){
                $('#$nameId').summernote({
                    lang: 'pt-BR',
                    toolbar: [
                      ['style', ['bold', 'italic', 'underline', 'clear']],
                      ['fontsize', ['fontsize']],
                      ['color', ['color'],],
                      ['para', ['ul', 'ol', 'paragraph']]
                    ],
                    callbacks: {
                        onPaste: function (e) {
                            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    }
                });
            });
                ";
    }

    function send($submit, $reset = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, btn1class, btn2class, btn1value, btn2add, btn1icon, btn2icon");

        if (!$add['btn1class'])
            $add['btn1class'] = 'btn-primary';

        if (!$add['btn2class'])
            $add['btn2class'] = 'btn-default';

        if ($add['btn1value'])
            $add['btn1value'] = "value='{$add['btn1value']}'";

        if ($add['btn1icon'])
            $add['btn1icon'] = "<i class='{$add['btn1icon']}'></i>";

        if ($add['btn2icon'])
            $add['btn2icon'] = "<i class='{$add['btn2icon']}'></i>";

        $s = "\n\t<div class='panel-footer'>";
        $s.="\n\t\t<button type='submit' class='btn {$add['btn1class']} {$add['btn1value']}' {$add['add']} >{$add['btn1icon']}$submit</button>";
        if ($reset)
            $s.="\n\t\t<button type='button' class='btn {$add['btn2class']}' {$add['btn2add']}>{$add['btn2icon']}$reset</button>";
        $s.="\n\t</div>";
        $this->formOut .= $s;
    }

    public static function submitId() {
        $s = $_SERVER['PHP_SELF'];
        $s = str_replace("/", "-", $s);
        $s = str_replace(".", "-", $s);
        return "btn" . toRewriteString($s);
    }

    function send_cancel($submit, $cancelLink = '', $add = '', $cancelText = 'Voltar') {
        $submitId = autoform2::submitId();

        $add = $this->retornarAddArray($add, "add, btn1class, btn2class, btn1value, btn2add, btn1icon, btn2icon");

        if (!$add['btn1class'])
            $add['btn1class'] = 'btn-primary';

        if (!$add['btn2class'])
            $add['btn2class'] = 'btn-default';

        if ($add['btn1value'])
            $add['btn1value'] = "value='{$add['btn1value']}'";

        if ($add['btn1icon'])
            $add['btn1icon'] = "<i class='{$add['btn1icon']}'></i>";

        if ($add['btn2icon'])
            $add['btn2icon'] = "<i class='{$add['btn2icon']}'></i>";

        $s = "\n\t<div class='panel-footer'>";
        $s.="\n\t\t<button type='submit' id='$submitId' class='btn {$add['btn1class']} {$add['btn1value']}' {$add['add']} >{$add['btn1icon']}$submit</button>";
        if ($cancelLink)
            $s.="\n\t\t<a href='$cancelLink' class='btn {$add['btn2class']}' {$add['btn2add']}>{$add['btn2icon']}{$cancelText}</a>";
        $s.="\n\t</div>";
        $this->formOut .= $s;
    }

    function insertHtml($html) {
        $this->formOut .= $html;
    }

    function end() {
        $name = $this->formName;
        $this->formOut .= "<div style='clear: both'></div>";
        $this->formOut .= "\n\t</form>\n";
        $this->formOut .= "<script>
                            $(document).ready(function () {
                                $('#cadastro').submit(function () {
                                    if ($('#cadastro').valid()) {
                                        $('.btn').attr('disabled', 'disabled');
                                    }
                                });
                            });
                        </script>  ";
    }

    public static function LabelControlGroup($label, $htmlInnerCtrl) {
        $nameId = toRewriteString($label);
        $nameId = self::returnInputId($nameId);

        if ($label) {
            $label = "$label: ";
        }
        return "<div class='form-group' id='field-{$nameId}'><label class='col-md-3 control-label '>$label</label><div class='col-md-6'>" . $htmlInnerCtrl . "</div></div>";
    }

}

?>