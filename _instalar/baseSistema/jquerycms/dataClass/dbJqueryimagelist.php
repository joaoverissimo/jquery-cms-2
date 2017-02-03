<?php

require_once "base/dbaseJqueryimagelist.php";

class dbJqueryimagelist extends dbaseJqueryimagelist {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $info, $die = false) {

        return parent::Inserir($Conexao, $info, $die);
    }

    public static function Update($Conexao, $cod, $info, $die = false) {

        return parent::Update($Conexao, $cod, $info, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $where = new dataFilter(dbJqueryimagelistitem::_jqueryimagelist, $cod);
        dbJqueryimagelistitem::DeletarWhere($Conexao, $where);

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>

    public static function encode($s) {
        $d1 = rand(0, 9);
        $d2 = rand(0, 9);
        $d3 = rand(0, 9);
        $d4 = rand(0, 9);
        $d5 = rand(0, 9);

        $s = $d1 . $d2 . $d3 . $s . $d4 . $d5;
        return base64_encode($s);
    }

    public static function decode($s) {
        $p = "/\d\d\d(.*)\d\d/";
        $s = base64_decode($s);

        if (preg_match($p, $s, $matches)) {
            if (isset($matches[1])) {
                return $matches[1];
            }
        }

        return false;
    }

    public static function obtemFirstOrDefault($Conexao, $jqueryimagelist) {
        $where = new dataFilter(dbJqueryimagelistitem::_jqueryimagelist, $jqueryimagelist);
        $where->add(dbJqueryimagelistitem::_principal, 1);

        $order = new dataOrder(dbJqueryimagelistitem::_ordem);

        $dados = dbJqueryimagelistitem::Listar($Conexao, $where, $order);

        if (!isset($dados[0]['cod'])) {
            $where = new dataFilter(dbJqueryimagelistitem::_jqueryimagelist, $jqueryimagelist);
            $dados = dbJqueryimagelistitem::Listar($Conexao, $where, $order);
        }

        if (isset($dados[0]['cod'])) {
            $obj = new objJqueryimagelistitem($Conexao);
            $obj->loadByArray($dados[0]);
            return $obj;
        }

        return false;
    }

    public static function RecalcInfo($Conexao, $cod, $updatedb = true) {
        $sql = "SELECT COUNT(*) AS rt FROM  `jqueryimagelistitem` WHERE  `jqueryimagelist` = '{$cod}'";
        $dados = dataExecSqlDireto($Conexao, $sql, false);

        $info = 0;
        if (isset($dados['rt'])) {
            $info = $dados['rt'];
        }

        if ($updatedb) {
            //Se $updatedb seja falso nao executa alteracao no db - ideal para inserir imoveis
            dbJqueryimagelist::Update($Conexao, $cod, $info);
        }

        return $info;
    }

}

class CtrlJqueryImageList {

    public $ctrlName;
    public $codImgList;
    public $exibelink = 0;
    public $exibedescricao = 0;
    public $exibetarget = 0;
    public $exibedeletar = 1;
    public $exibeeditar = 1;
    public $exibeInserirUpload = 1;
    public $exibeInserirSimple = 0;
    public $exibesetdefault = 0;
    public $lblInserirUpload = "Inserir Imagens";
    public $lblInserirSimple = "Inserir";
    public $lblimage = '';
    public $lbltitulo = '';
    public $lbldescricao = '';
    public $lbllink = '';
    public $lbltarget = '';

    function __construct($ctrlName, $codImgList) {
        $this->ctrlName = $ctrlName;
        $this->codImgList = $codImgList;
    }

    public function configurarCampos($exibedescricao, $exibelink, $exibetarget, $lblimage, $lbltitulo, $lbldescricao, $lbllink) {
        $this->exibedescricao = $exibedescricao;
        $this->exibelink = $exibelink;
        $this->exibetarget = $exibetarget;
        $this->lblimage = $lblimage;
        $this->lbltitulo = $lbltitulo;
        $this->lbldescricao = $lbldescricao;
        $this->lbllink = $lbllink;
    }

    public function configurarAcoes($exibedeletar, $exibeeditar, $exibesetdefault) {
        $this->exibedeletar = $exibedeletar;
        $this->exibeeditar = $exibeeditar;
        $this->exibesetdefault = $exibesetdefault;
    }

    public function configurarToolbar($exibeInserirSimple, $exibeInserirUpload, $lblInserirSimple, $lblInserirUpload = "") {
        $this->exibeInserirUpload = $exibeInserirUpload;
        $this->exibeInserirSimple = $exibeInserirSimple;
        $this->lblInserirUpload = $lblInserirUpload;
        $this->lblInserirSimple = $lblInserirSimple;
    }

    public function getHead($addJqueryUi = true, $addJsFineuploader = true) {
        $ctrlName = $this->ctrlName;
        $codImgList = $this->codImgList;

        $exibelink = $this->exibelink;
        $exibedescricao = $this->exibedescricao;
        $exibetarget = $this->exibetarget;

        $exibedeletar = $this->exibedeletar;
        $exibeeditar = $this->exibeeditar;
        $exibesetdefault = $this->exibesetdefault;

        $exibeInserirUpload = $this->exibeInserirUpload;
        $exibeInserirSimple = $this->exibeInserirSimple;

        $lblInserirUpload = $this->lblInserirUpload;
        $lblInserirSimple = $this->lblInserirSimple;

        $lblimage = $this->lblimage;
        $lbltitulo = $this->lbltitulo;
        $lbldescricao = $this->lbldescricao;
        $lbllink = $this->lbllink;
        $lbltarget = $this->lbltarget;

        $codImgListEncode = dbJqueryimagelist::encode($codImgList);
        $link = CtrlJqueryImageList::getLinkAdmin($exibelink, $exibedescricao, $exibetarget, $exibedeletar, $exibeeditar, $exibesetdefault, $lblimage, $lbltitulo, $lbldescricao);

        $s = "";

        if ($addJqueryUi) {
            $s .= "<script type='text/javascript' src='/jquerycms/js/jquery-ui/js/jquery-ui-1.8.19.custom.min.js'></script><link rel='stylesheet' href='/jquerycms/js/jquery-ui/css/ui-lightness/jquery-ui-1.8.19.custom.css' type='text/css' media='all' />";
        }

        if ($addJsFineuploader) {
            $s .= "<link href='/jquerycms/js/fineuploader/fineuploader.css' rel='stylesheet'><script src='/jquerycms/js/fineuploader/fineuploader-3.2.js'></script>";
        }

        //script
        $s.= "<script>";

        $script = "
                    function {$ctrlName}_iframe(t) {
                        var url = $(t).attr('href');
                        var margemtop = $(window).scrollTop() + 50;
                        var jqwidth = $(t).attr('jqwidth');
                        var jqheight = $(t).attr('jqheight');
                        if (jqwidth === 'undefined') {
                            jqwidth = 550;
                        }
                        if (jqheight === 'undefined') {
                            jqheight = 330;
                        }
                        var html = \"<div class='{$ctrlName}_jquerymodal' style='width: \"+ $(document).width() +\"px; height: \"+ $(document).height() +\"px;top: 0; left: 0; position: absolute; z-index: 1025; background: url(/jquerycms/js/fineuploader/background.png);'>\";
                        html += \"<div style='background: white; width: \"+ jqwidth +\"px; height: \"+ jqheight +\"px; padding: 10px; margin: 0 auto; margin-top: \"+ margemtop +\"px;'>\";
                        html += \"<iframe src='\"+ url +\"' style='width: 99%; height: 99%;border: 0;'>\";
                        html += \"</iframe></div></div>\";

                        $('body').prepend(html);
                        return false;
                    } 

                    function {$ctrlName}_refresh() {
                        $('#{$ctrlName} #imagelist').load(
                        '/jquerycms/js/fineuploader/upload-get.php?ctrlname={$ctrlName}&{$link}', 
                        {'jqueryimagelist' : '{$codImgListEncode}'},
                        function () {
                            $('.{$ctrlName}_deletar').each(function(){
                                $(this).click(function(){
                                    if (confirm('Deletar a imagem?')){
                                        {$ctrlName}_iframe(this);
                                        return false;
                                    } else {
                                        return false;
                                    }
                                });
                            });

                            $('.{$ctrlName}_editar').each(function(){
                                $(this).click(function(){
                                    {$ctrlName}_iframe(this);
                                    return false;                            
                                });
                            });

                            $(\"#{$ctrlName} #imagelist li.noimage\").html('Não existem imagens. Cadastre através do botão <b>$lblInserirUpload</b>.');

                            $(\"a[data-toggle='tooltip']\").tooltip();
                            
                            $('#{$ctrlName} #imagelist').magnificPopup({
                                delegate: 'a.img-slider',
                                type: 'image',
                                tLoading: 'Carregando imagem #%curr%...',
                                mainClass: 'mfp-img-mobile',
                                gallery: {
                                    enabled: true,
                                    navigateByImgClick: true,
                                    preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
                                },
                                image: {
                                    tError: '<a href=\"%url%\">A imagem #%curr%</a> não pode ser carregada.',
                                    titleSrc: function (item) {
                                        return item.el.attr('title');
                                    }
                                }
                            });
                        });
                    }

                    function {$ctrlName}_closeModalJquery() {
                        $('.{$ctrlName}_jquerymodal').remove();
                        {$ctrlName}_refresh();
                    }

                    function {$ctrlName}_resizeModalJquery(w, h) {
                        $('.{$ctrlName}_jquerymodal div').width(w);
                        $('.{$ctrlName}_jquerymodal div').height(h);
                    }

                    $(document).ready(function () {
                        var {$ctrlName}_i = 0;
                        var imagelist = '{$codImgListEncode}';

                        function {$ctrlName}_endLoad(i) {
                            if (i == 0) {                        
                                $('.qq-upload-list').html('');
                                {$ctrlName}_refresh();
                            }
                        }

                        $('#{$ctrlName}_fineuploader').fineUploader({
                            element: document.getElementById('{$ctrlName}_fineuploader'),
                            request: {
                                endpoint: '/jquerycms/js/fineuploader/upload-file.php',
                                inputName: 'valor-file'
                            },
                            text: {
                                uploadButton: \"<i class='fa fa-plus-square-o'></i> {$lblInserirUpload}\"
                            },
                            validation: {
                                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
                            },
                            template: '<div class=\"qq-uploader\">' +
                                '<pre class=\"qq-upload-drop-area\"><span>{dragZoneText}</span></pre>' +
                                '<div class=\"qq-upload-button btn btn-success\" style=\"width: auto;\">{uploadButtonText}</div>' +
                                '<span class=\"qq-drop-processing\"><span>{dropProcessingText}</span><span class=\"qq-drop-processing-spinner\"></span></span>' +
                                '<ul class=\"qq-upload-list\" style=\"margin-top: 10px; text-align: center;\"></ul>' +
                                '</div>',
                            classes: {
                                success: 'alert alert-success',
                                fail: 'alert alert-danger'
                            }
                        }).on('submit', function (event, id, filename) { 
                            $(this).fineUploader('setParams', {'jqueryimagelist': imagelist} );
                        }).on('upload', function (id, fileName, xhr) { 
                            {$ctrlName}_i = {$ctrlName}_i + 1;
                            {$ctrlName}_endLoad({$ctrlName}_i);
                        }).on('complete', function (id, fileName, responseJSON) {
                            {$ctrlName}_i = {$ctrlName}_i - 1;
                            {$ctrlName}_endLoad({$ctrlName}_i);
                        }).on('cancel', function (id, fileName) {
                            {$ctrlName}_i = {$ctrlName}_i - 1;
                            {$ctrlName}_endLoad({$ctrlName}_i);
                        });

                        $('#{$ctrlName} #imagelist').sortable({
                            update : function () {
                                order = [];
                                $('#{$ctrlName} #imagelist').children('li').each(function(idx, elm) {
                                order.push(elm.id.split('_')[1])
                            });

                            $('#{$ctrlName} #status').load('/jquerycms/js/fineuploader/upload-order.php', {'jqueryimagelist' : '{$codImgListEncode}', 'order' : order});
                            $('#{$ctrlName} #status').html(\"<div class='qq-upload-spinner'></div>\");
                            }
                        });

                        $('#{$ctrlName} #imagelist').disableSelection();

                        $('#{$ctrlName} .inserir').each(function(){
                            $(this).click(function(){
                            {$ctrlName}_iframe(this);
                            return false;                            
                        });
                    });

                    {$ctrlName}_refresh();
                });
                ";

        $s .= $script;
        $s .= "</script>";

        //Styles 
        $css = "                    
                    .qq-upload-list {text-align: left;}
                    li.alert-success {background-color: #DFF0D8;}
                    li.alert-danger {background-color: #F2DEDE;}
                    .alert-danger .qq-upload-failed-text {display: inline;}
                    #{$ctrlName} {float: left; background: white;}
                    #{$ctrlName} .inserir {float: left; margin-right: 10px;}
                    #{$ctrlName} #status {float: right; margin-top: -30px;}
                    #{$ctrlName} #imagelist {list-style: none;}
                    #{$ctrlName} #imagelist li {float: left; width: 200px; height: 150px; margin: 4px;padding: 5px;}
                    #{$ctrlName} #imagelist li.noimage {height: 35px !important;width: 440px !important;margin-bottom: 50px;}
                    #{$ctrlName} #imagelist li {border: 1px solid #d4d4d4;  -webkit-border-radius: 4px;  -moz-border-radius: 4px;  border-radius: 4px;  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#fff2f2f2',GradientType=0);  -webkit-box-shadow: 0 1px 4px rgba(0,0,0,0.065);  -moz-box-shadow: 0 1px 4px rgba(0,0,0,0.065);  box-shadow: 0 1px 4px rgba(0,0,0,0.065);background: rgba(241, 241, 241, 0.6);}
                    #{$ctrlName} #imagelist li p {display: block; width: 130px; height: 100px; overflow: hidden; margin: 0 auto;}
                    #{$ctrlName} #imagelist li img {width: 120px;margin: 0 auto;display: block;}
                    #{$ctrlName} #imagelist li span {display: block;height: 26px;width: 195px; overflow: hidden; font-size: 12px;text-align: center;}
                    #{$ctrlName} #imagelist li div {margin-top: -3px;}
                    #{$ctrlName} #imagelist li div .btn {height: 20px;}
                    #{$ctrlName} #imagelist li div .{$ctrlName}_deletar{float: right;}
                    #{$ctrlName} #imagelist li div .{$ctrlName}_editar{float: right;}
                    #{$ctrlName} #imagelist li div i {font-size: 16px;}
                    ";
        $s .= "<style>$css</style>";
        return $s;
    }

    public function getCtrl() {
        $ctrlName = $this->ctrlName;
        $codImgList = $this->codImgList;

        $exibelink = $this->exibelink;
        $exibedescricao = $this->exibedescricao;
        $exibetarget = $this->exibetarget;

        $exibedeletar = $this->exibedeletar;
        $exibeeditar = $this->exibeeditar;
        $exibesetdefault = $this->exibesetdefault;

        $exibeInserirUpload = $this->exibeInserirUpload;
        $exibeInserirSimple = $this->exibeInserirSimple;

        $lblInserirUpload = $this->lblInserirUpload;
        $lblInserirSimple = $this->lblInserirSimple;

        $lblimage = $this->lblimage;
        $lbltitulo = $this->lbltitulo;
        $lbldescricao = $this->lbldescricao;
        $lbllink = $this->lbllink;
        $lbltarget = $this->lbltarget;

        $codImgListEncode = dbJqueryimagelist::encode($codImgList);
        $link = CtrlJqueryImageList::getLinkAdmin($exibelink, $exibedescricao, $exibetarget, $exibedeletar, $exibeeditar, $exibesetdefault, $lblimage, $lbltitulo, $lbldescricao);

        $s = "";
        $s .= "
                    <div id='{$ctrlName}'> ";
        if ($exibeInserirSimple) {
            $s .= "<a href='/jquerycms/js/fineuploader/upload-inserir.php?jqueryimagelist={$codImgListEncode}&ctrlname={$ctrlName}&{$link}' class='btn btn-success inserir'  jqwidth='600' jqheight='450' ><i class='fa fa-plus-square-o icon-white'></i> {$lblInserirSimple}</a>";
        }

        if ($exibeInserirUpload) {
            $s .= "<div id='{$ctrlName}_fineuploader'></div>";
        }

        $s.= "<div id='status'></div>
                        <div class='clearfix'></div>
                        <ul id='imagelist'>
                            <li>aguarde...</li>
                        </ul>
                    </div>
                ";

        $s .= "<div class='clearfix'></div>";

        return $s;
    }

    public static function getLinkAdmin($exibelink = 0, $exibedescricao = 0, $exibetarget = 0, $exibedeletar = 0, $exibeeditar = 0, $exibesetdefault = 0, $lblimage = '', $lbltitulo = '', $lbldescricao = '', $lbllink = '', $lbltarget = '') {
        $s = "";
        if ($exibelink) {
            if ($s != "")
                $s.= "&";

            $s .= "exibelink=1";
        }

        if ($exibedescricao) {
            if ($s != "")
                $s.= "&";

            $s .= "exibedescricao=1";
        }

        if ($exibetarget) {
            if ($s != "")
                $s.= "&";

            $s .= "exibetarget=1";
        }

        if ($exibedeletar) {
            if ($s != "")
                $s.= "&";

            $s .= "exibedeletar=1";
        }

        if ($exibeeditar) {
            if ($s != "")
                $s.= "&";

            $s .= "exibeeditar=1";
        }

        if ($exibesetdefault) {
            if ($s != "")
                $s.= "&";

            $s .= "exibesetdefault=1";
        }

        if ($lblimage) {
            if ($s != "")
                $s.= "&";

            $s .= "lblimage=$lblimage";
        }

        if ($lbltitulo) {
            if ($s != "")
                $s.= "&";

            $s .= "lbltitulo=$lbltitulo";
        }

        if ($lbllink) {
            if ($s != "")
                $s.= "&";

            $s .= "lbllink=$lbllink";
        }

        if ($lbltarget) {
            if ($s != "")
                $s.= "&";

            $s .= "lbltarget=$lbltarget";
        }

        if ($lbldescricao) {
            if ($s != "")
                $s.= "&";

            $s .= "lbldescricao=$lbldescricao";
        }

        return $s;
    }

}
