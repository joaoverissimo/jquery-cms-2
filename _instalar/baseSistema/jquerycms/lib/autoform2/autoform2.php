<?php

require_once 'autoform2-tabs.php';

class autoform2 {

    private $formOut = null;
    private $javascript = null;
    private $headJquery = false;
    private $headJavaScriptFiles = true;
    private $srcJsFiles = '/jquerycms/js';
    private $CKEditorBasePath = '/jquerycms/lib/autoform2/ckeditor/';
    private $autoformFolderTemplate;
    public $formName;

    function __construct($autoformFolderTemplate = '') {
        if (!$autoformFolderTemplate)
            $this->autoformFolderTemplate = ___AppRoot . 'jquerycms/lib/autoform2/templates/';
    }

    public function Configurar($headJquery, $headJavaScriptFiles = true, $srcJsFiles = '/jquerycms/js', $CKEditorBasePath = '/jquerycms/lib/autoform2/ckeditor/') {
        $this->headJquery = $headJquery;
        $this->headJavaScriptFiles = $headJavaScriptFiles;
        $this->srcJsFiles = $srcJsFiles;
        $this->CKEditorBasePath = $CKEditorBasePath;
    }

    public function getForm() {
        return $this->formOut;
    }

    private function retornarJavaScriptFiles() {
        $scripts = '';
        $parametros = array('$srcJsFiles' => $this->srcJsFiles);

        if ($this->headJavaScriptFiles === true) {

            if ($this->headJquery === true)
                $scripts = stringuize("jquery.html", $parametros, $this->autoformFolderTemplate);

            $scripts .= stringuize("head.html", $parametros, $this->autoformFolderTemplate);
        }

        return $scripts;
    }

    public function getHead($headJquery = false, $headJavaScriptFiles = true, $srcJsFiles = '/jquerycms/js', $CKEditorBasePath = '/lib/autoform2/ckeditor/') {
        $this->Configurar($headJquery, $headJavaScriptFiles, $srcJsFiles, $CKEditorBasePath);
        $s = $this->retornarJavaScriptFiles();
        $s .= stringuize("headScriptBlock.html", array('$valor' => $this->javascript), $this->autoformFolderTemplate);

        return $s;
    }

    public static function FieldIn() {
        return "\n\t<div class='control-group'>";
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

    #Function: <form...>

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
        $this->javascript = "\n\t " . ' $().ready(function() {$("#' . $name . '").validate({' . "
            highlight: function(label) {
                $(label).siblings('.help-inline').removeClass('help-inline').addClass('help-block');
                $(label).closest('.control-group').addClass('error');
              },
              success: function(label) {
                label.closest('.control-group').removeClass('error');
              }
        " . '}); ' . 
            $add['onready'] . '
            $("#' . $name . '").submit(function(){ if ($("#' . $name . '").valid()) {$(".btn").attr("disabled","disabled"); }});
        }); ';
        $this->formName = $name;
    }

    #Function: <fieldset...>

    function fieldset($legend = '') {
        $s = "<fieldset>";

        if ($legend)
            $s .= "<legend>$legend:</legend>";

        $this->formOut .= $s;
    }

    #Function: <fieldset...>

    function fieldsetOut() {
        $s = "</fieldset>";
        $this->formOut .= $s;
    }

    #Function: <input text>

    function text($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='{$add['type']}' id='$name' name='$name' value='$value' class='$validateString {$add['class']}' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    function floatReal($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $form_name = $this->formName;
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='{$add['type']}' id='$name' name='$name' value='$value' class='$validateString {$add['class']}' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();

        $this->javascript .= "\n\n\t $(document).ready(function(){ $('#{$name}').maskMoney({decimal:',',thousands:'.', defaultZero: false, allowZero: true}); $('#{$name}').focus(function(){ $(this).val($(this).val().replace('R$ ', '')); }); $('#{$name}').focusout(function(){ $(this).val('R$ '+ $(this).val()); });$('#{$form_name}').submit(function(){ if ($('#{$name}').val()){ $('#{$name}').maskMoney('destroy'); $('#{$name}').val($('#{$name}').val().replace('R$ ','').replace(/\./g,'').replace(',','.'));} }); })";
        $this->formOut .= $s;
    }

    #Function: <input text>

    function floatInteger($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        if ($validate == "1") {
            $validateString = $this->retornarValidate(1) . " " . $this->retornarValidate(8);
        } else {
            $validateString = $this->retornarValidate(8);
        }
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='{$add['type']}' id='$name' name='$name' value='$value' class='$validateString {$add['class']}' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t " . '$(document).ready(function(){$("#' . $name . '").keyup(function(){var e=$(this).val();var t=e.length;var n=1;var r="";for(var i=t-1;i>=0;i--){if(!isNaN(parseFloat(e[i]))&&isFinite(e[i])){r=e[i]+r;if(n==3&&i>0){n=0;r="."+r}n++}}if(r){r="R$ "+r+",00";$(this).siblings("i").text(r)}else{$(this).siblings("i").text("")}}).change(function(){var e=$(this).val();if(e.indexOf(".")!==-1||e.indexOf(",")!==-1){var t=$(this).closest(".control-group").find(".control-label").text().replace(": ", "");alert("Você digitou virgula ou ponto no campo \'"+t+"\'.")}if(e.indexOf(",00")!==-1){e=e.substr(0,e.length-3)}$(this).val(e.replace(/[^0-9]/g,""));$(this).keyup()}).after("<i></i>"); $("#' . $name . '").change();})';
    }

    function url($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        if ($validate == "1") {
            $validateString = $this->retornarValidate(1) . " urlhttp";
        } else {
            $validateString = "urlhttp";
        }
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='{$add['type']}' id='$name' name='$name' value='$value' class='$validateString {$add['class']}' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    #Function: <input password>

    function textPassword($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='password' id='$name' name='$name' class='$validateString {$add['class']}' value='$value' placeholder='$label' {$add['add']} {$add['maxlength']} />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    #Function: <selects>

    function select($label, $name, $value, $validate, $opt_value, $opt_txt, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "<select id='$name' name='$name' class='$validateString {$add['class']}' {$add['add']} >\n";

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
        $s .= "\n\t\t<script> $().ready(function() { $('#$name').val('$value'); });</script>\t\t";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    public static function retornarSelectDb($name, $value, $validateString, $Conexao, $tabela, $campo1Val, $campo2Txt, $where = '', $orderBy = '', $AdicionarValorEmBranco = '') {
        $s = "<select id='$name' name='$name' class='$validateString' >\n";

        if ($AdicionarValorEmBranco)
            $s .= "\t\t<option value=''>$AdicionarValorEmBranco</option>\n";

        $dados = dataListar($Conexao, $tabela, $where, $orderBy, '', "$campo1Val, $campo2Txt");
        if (!issetArray($dados))
            $s .= "\t\t<option value=''>Não existem registros disponíveis</option>\n";
        else {
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
        $s .= "\t\t</select>";
        return $s;
    }

    #Function: <selects>

    function selectDb($label, $name, $value, $validate, $Conexao, $tabela, $campo1Val, $campo2Txt, $where = '', $orderBy = '', $AdicionarValorEmBranco = '', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";

        $s.= self::retornarSelectDb($name, $value, $validateString, $Conexao, $tabela, $campo1Val, $campo2Txt, $where, $orderBy, $AdicionarValorEmBranco);

        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= "\n\t\t<script> $().ready(function() { $('#$name').val('$value'); });</script>\t\t";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    #Function: <input checkbox...>

    function checkbox($label, $name, $value = '1', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass");

        $checked = "";
        if ($value)
            $checked = "checked";

        if ($validate)
            $validate = "validate='required:true'";

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<label class='checkbox'><input type='checkbox' id='$name' name='$name' $checked {$add['add']} class='{$add['class']}' $validate >$span</label>\n";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    #Function: <textarea...>

    function textarea($label, $name, $value = '', $validate = '0', $span = '', $add = '', $rows = '5') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<textarea name='$name' id='$name' rows='$rows' class='$validateString {$add['class']}'  placeholder='$label' {$add['add']} {$add['maxlength']}>" . $value . "</textarea>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    #Function: <input file>

    function file($label, $name, $value = null, $validate = '0', $span = '', $add = '') {
        if (isset($value))
            throw new jquerycmsException("Arquivos devem possuir valor nulo!");

        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .="\n\t\t\t<input type='file' name='$name' id='$name' class='$validateString {$add['class']}' {$add['add']} {$add['maxlength']} /> ";
        $s .= $this->retornarSpan($span);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
    }

    #Function: <input file>

    function fileImagems($label, $name, $value = '0', $validate = '0', $Conexao = null, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='{$name}-file'>$label: </label>";
        $s .= "\n\t\t<div class='controls FormfileImagems {$add['divcontrolsclass']}'>{$add['precontrol']}";

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

    #Function: <input hidden...>

    function hidden($name, $value = '', $add = '') {
        if (issetArray($add)) {
            throw new jquerycmsException("O controle hidden nao permite add em arrays, use uma string simples");
        }

        $this->formOut .= "\t<input type='hidden' name='$name' id='$name' value='$value' $add />\n";
    }

    #Function: <input text (__) ____-____>

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

        $valueArr = $this->telefoneGet($value);

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='{$name}DDD'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls FormTelefone {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t<input type='text' id='{$name}DDD' class='$validateString span1 {$name}fone {$add['class']}' value='{$valueArr[0]}' placeholder='DDD' {$add['add']} /> ";
        $s .= "\n\t\t<input type='text' id='{$name}NUM' class='$validateString span2 {$name}fone {$add['class']}' value='{$valueArr[1]}' placeholder='DDD' {$add['add']} /> ";
        $s .= "\n\t\t<input type='hidden' id='{$name}' name='{$name}' value='{$value}' />";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t $(document).ready(function() { $('#{$name}DDD').change(function (){ $('#{$name}').val($('#{$name}DDD').val() + '-' + $('#{$name}NUM').val()).trigger('change'); }); $('#{$name}NUM').change(function (){ $('#{$name}').val($('#{$name}DDD').val() + '-' + $('#{$name}NUM').val()).trigger('change'); }); }); \n";
    }

    function data($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$name' name='$name' value='$value' class='$validateString {$add['class']}' placeholder='dd/mm/aaaa' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t " . 'jQuery(function($){  $("#' . $name . '").mask("99/99/9999");   }); ' . "\n\n";
    }

    function datatime($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t <div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$name' name='$name' value='$value' class='$validateString {$add['class']}' placeholder='dd/mm/aaaa hh:mm:ss' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\n\t " . 'jQuery(function($){  $("#' . $name . '").mask("99/99/9999 99:99");   }); ' . "\n\n";
    }

    function calendario($label, $name, $value = '', $validate = '0', $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $validateString = $this->retornarValidate($validate);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$name' name='$name' value='$value'  class='$validateString {$add['class']}' placeholder='$label' {$add['add']} {$add['maxlength']}  /> ";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= $this->FieldOut();
        $this->formOut .= $s;
        $this->javascript .= "\n\t $(document).ready(function(){\$('#$name').focus(function(){\$(this).calendario({ target:'#$name' }); }); });";
    }

    function capchaField($label = "", $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        if (!$label) {
            $label = "Letras de segurança";
        }

        $name = "capchaField";

        $s = $this->FieldIn();
        $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";
        $validateString = $this->retornarValidate(1);
        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "\n\t\t\t<input type='text' id='$name' name='$name' class='$validateString {$add['class']}' placeholder='$label' {$add['add']} {$add['maxlength']}  /> ";
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

    function fckEditor($label, $name, $value = '', $validade = false, $span = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, class, labelclass, precontrol, poscontrol, divcontrolsclass, spanclass, maxlength, type");

        $s = "<div class='control-group div_field_editor'>";
        if ($label)
            $s .= "\n\t\t<label class='control-label {$add['labelclass']}' for='$name'>$label: </label>";

        $s .= "\n\t\t<div class='controls {$add['divcontrolsclass']}'>{$add['precontrol']}";
        $s .= "<textarea name='$name' id='$name'>$value</textarea>";
        $s .= "{$add['poscontrol']}\n\t\t";
        $s .= $this->retornarSpan($span, $add['spanclass']);
        $s .= "</div>";
        $s .= "</div>";
        $this->formOut .= $s;

        $template = "fckeditor_no_validate.html";
        if ($validade)
            $template = "fckeditor_validate.html";

        $this->javascript .= stringuize($template, array('$name' => $name,
            '$label' => $label,
            '$form' => $this->formName), $this->autoformFolderTemplate);
    }

    #Function: Submit/Reset

    function send($submit, $reset = '', $add = '') {
        $add = $this->retornarAddArray($add, "add, btn1class, btn2class, btn1value, btn2add, btn1icon, btn2icon");

        if (!$add['btn1class'])
            $add['btn1class'] = 'btn-primary';

        if ($add['btn1value'])
            $add['btn1value'] = "value='{$add['btn1value']}'";

        if ($add['btn1icon'])
            $add['btn1icon'] = "<i class='{$add['btn1icon']}'></i>";

        if ($add['btn2icon'])
            $add['btn2icon'] = "<i class='{$add['btn2icon']}'></i>";

        $s = "\n\t<div class='form-actions'>";
        $s.="\n\t\t<button type='submit' class='btn {$add['btn1class']} {$add['btn1value']}' {$add['add']} >{$add['btn1icon']}$submit</button>";
        if ($reset)
            $s.="\n\t\t<button type='button' class='btn {$add['btn2class']}' {$add['btn2add']}>{$add['btn2icon']}$reset</button>";
        $s.="\n\t</div>";
        $this->formOut .= $s;
    }

    #Function: Submit/Reset

    function send_cancel($submit, $cancelLink = '', $add = '', $cancelText = 'Cancelar') {
        $add = $this->retornarAddArray($add, "add, btn1class, btn2class, btn1value, btn2add, btn1icon, btn2icon");

        if (!$add['btn1class'])
            $add['btn1class'] = 'btn-primary';

        if ($add['btn1value'])
            $add['btn1value'] = "value='{$add['btn1value']}'";

        if ($add['btn1icon'])
            $add['btn1icon'] = "<i class='{$add['btn1icon']}'></i>";

        if ($add['btn2icon'])
            $add['btn2icon'] = "<i class='{$add['btn2icon']}'></i>";

        $s = "\n\t<div class='form-actions'>";
        $s.="\n\t\t<button type='submit' class='btn {$add['btn1class']} {$add['btn1value']}' {$add['add']} >{$add['btn1icon']}$submit</button>";
        if ($cancelLink)
            $s.="\n\t\t<a href='$cancelLink' class='btn {$add['btn2class']}' {$add['btn2add']}>{$add['btn2icon']}{$cancelText}</a>";
        $s.="\n\t</div>";
        $this->formOut .= $s;
    }

    #Function: Comentário

    function insertHtml($html) {
        $this->formOut .= $html;
    }

    #Closing the form

    function end() {
        $this->formOut .= "<div style='clear: both'></div>";
        $this->formOut .= "\n\t</form>\n";
    }

    public static function LabelControlGroup($label, $htmlInnerCtrl) {
        if ($label) {
            $label = "$label: ";
        }
        return "<div class='control-group'><label class='control-label '>$label</label><div class='controls '>" . $htmlInnerCtrl . "</div></div>";
    }

}

?>