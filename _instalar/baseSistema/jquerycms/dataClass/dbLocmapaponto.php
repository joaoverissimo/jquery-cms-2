<?php

require_once "base/dbaseLocmapaponto.php";

class dbLocmapaponto extends dbaseLocmapaponto {

    const ct_exibirMapaEVisaoDaRua = "1";
    const ct_exibirSomenteMapa = "2";
    const ct_naoExibir = "3";

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $lat, $lng, $heading, $pitch, $zoom, $comportamento, $suportaview, $die = false) {

        return parent::Inserir($Conexao, $lat, $lng, $heading, $pitch, $zoom, $comportamento, $suportaview, $die);
    }

    public static function Update($Conexao, $cod, $lat, $lng, $heading, $pitch, $zoom, $comportamento, $suportaview, $die = false) {
        return parent::Update($Conexao, $cod, $lat, $lng, $heading, $pitch, $zoom, $comportamento, $suportaview, $die);
    }

    public static function Deletar($Conexao, $cod) {
        if ($cod == 0 || $cod == 1) {
            return false;
        }

        /* $obj = new objLocmapaponto($Conexao);
          $obj->loadByCod($cod);

         */

        return parent::Deletar($Conexao, $cod);
    }

// </editor-fold>
}

class CtrlMapaPontoView {

    private $Conexao;
    private $CtrlName;
    //From params
    public $uf = "SC";
    public $cidade = "Florianópolis";
    public $bairro = "Canasvieiras";
    public $rua = "Rua Mario Lacombe";
    public $numero = "314";
    //From db
    public $codPontoMapa;
    public $comportamento = 1;
    public $suporta = 0;
    public $lat = "";
    public $lng = "";
    public $heading = 0;
    public $pitch = 10;
    public $zoom = 1;
    public $height;

    function __construct($Conexao, $CtrlName = "locmapa", $height = "350px") {
        $this->Conexao = $Conexao;
        $this->CtrlName = $CtrlName;
        $this->height = $height;
    }

    public function loadByParams($uf, $cidade, $bairro, $rua, $numero) {
        $this->uf = $uf;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->rua = $rua;
        $this->numero = $numero;
    }

    public function loadByCod($codPontoMapa) {
        $this->codPontoMapa = $codPontoMapa;

        $obj = new objLocmapaponto($this->Conexao);
        $obj->loadByCod($codPontoMapa);

        $this->lat = $obj->getLat();
        $this->lng = $obj->getLng();
        $this->heading = $obj->getHeading();
        $this->pitch = $obj->getPitch();
        $this->zoom = $obj->getZoom();
        $this->comportamento = $obj->getComportamento();
        $this->suporta = $obj->getSuportaview();
    }

    public function loadByObjLocalizacao(objLocalizacao $objLocalizacao) {
        $this->uf = $objLocalizacao->objEstado()->getUf();
        $this->cidade = $objLocalizacao->objCidade()->getNome();
        $this->bairro = $objLocalizacao->objBairro()->getNome();
        $this->rua = $objLocalizacao->getRua();
        $this->numero = $objLocalizacao->getNumero();
    }

    public function loadByCtrlLocalizacao(CtrlLocalizacao $ctrlLocalizacao) {
        $estado = new objLocestado($this->Conexao, false);
        $estado->loadByCod($ctrlLocalizacao->estado);

        $cidade = new objLoccidade($this->Conexao, false);
        $cidade->loadByCod($ctrlLocalizacao->cidade);

        $bairro = new objLocbairro($this->Conexao, false);
        $bairro->loadByCod($ctrlLocalizacao->bairro);

        $this->uf = $estado->getUf();
        $this->cidade = $cidade->getNome();
        $this->bairro = $bairro->getNome();
        $this->rua = $ctrlLocalizacao->rua;
        $this->numero = $ctrlLocalizacao->numero;
    }

    public function getHead($addJsMapsApi = true) {
        $name = $this->CtrlName;
        $uf = $this->uf;
        $cidade = $this->cidade;
        $bairro = $this->bairro;
        $rua = $this->rua;
        $numero = $this->numero;
        $lat = $this->lat;
        $lng = $this->lng;
        $comportamento = $this->comportamento;
        $this->suporta = $this->suporta;

        if ($this->heading) {
            $heading = $this->heading;
        } else {
            $heading = 0;
        }

        if ($this->pitch) {
            $pitch = $this->pitch;
        } else {
            $pitch = 10;
        }

        if ($this->zoom) {
            $zoom = $this->zoom;
        } else {
            $zoom = 1;
        }

        $s = "";
        if ($addJsMapsApi) {
            $s.= "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=" . ___googleMapsAPI . "'></script>";
        }

        $s .= "
            <script type='text/javascript'>

                var {$name}marker;
                var {$name}map;
                var {$name}panorama;
                var {$name}panoramasv = new google.maps.StreetViewService();
                
                function {$name}initialize() {

                    var {$name}mapOptions = {
                        zoom: 15,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        mapTypeControl: true,
                        scrollwheel: false
                    };

                    {$name}map = new google.maps.Map(document.getElementById('map_canvas'), {$name}mapOptions);
                        
        ";

        if (!$lat || !$lng) {
            $s.= "
                    //inicia pelo endereço
                        var {$name}address = '$rua, $numero - $bairro, $cidade - $uf, Brasil';
                        var {$name}geocoder = new google.maps.Geocoder();
                        {$name}geocoder.geocode( { 'address': {$name}address}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                {$name}map.setCenter(results[0].geometry.location);
                                {$name}marker = new google.maps.Marker({
                                    map: {$name}map,
                                    draggable:true,
                                    animation: google.maps.Animation.DROP,
                                    position: results[0].geometry.location
                                });

                                var {$name}panoramaOptions = {position: results[0].geometry.location, addressControl: false, zoomControl: false, scrollwheel: false, pov: {heading: $heading, pitch: $pitch, zoom: $zoom }};
                                {$name}panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'),{$name}panoramaOptions);
                                
                                {$name}addMarkerListener();
                            } else {
                                //inicia pelo bairro
                                var {$name}address = '$bairro, $cidade - $uf, Brasil';
                                var {$name}geocoder = new google.maps.Geocoder();
                                {$name}geocoder.geocode( { 'address': {$name}address}, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        {$name}map.setCenter(results[0].geometry.location);
                                        {$name}marker = new google.maps.Marker({
                                            map: {$name}map,
                                            draggable:true,
                                            animation: google.maps.Animation.DROP,
                                            position: results[0].geometry.location
                                        });

                                        var {$name}panoramaOptions = {position: results[0].geometry.location, addressControl: false, zoomControl: false, pov: {heading: $heading, pitch: $pitch, zoom: $zoom }};
                                        {$name}panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'),{$name}panoramaOptions);

                                        {$name}addMarkerListener();
                                    } else {
                                        alert('Não foi possível geocodificar o endereço, você deve localizar manualmente: ' + status);
                                    }
                                });
                            }
                        });
                                            
                   ";
        } else {
            $s .= "
                    //inicia por latlng
                        var {$name}latlnginicial =  new google.maps.LatLng($lat, $lng);
                        {$name}map.setCenter({$name}latlnginicial);
                        {$name}marker = new google.maps.Marker({
                            map: {$name}map,
                            draggable:true,
                            animation: google.maps.Animation.DROP,
                            position: {$name}latlnginicial
                        });

                        var {$name}panoramaOptions = {position: {$name}latlnginicial, addressControl: false, zoomControl: false, pov: {heading: $heading, pitch: $pitch, zoom: $zoom }};                        
                        {$name}panorama = new  google.maps.StreetViewPanorama(document.getElementById('pano'),{$name}panoramaOptions);

                        {$name}addMarkerListener();
                    ";
        }

        $s.="
                    }
                    
                    function {$name}addMarkerListener() {
                        google.maps.event.addListener({$name}marker, 'dragend', function() {
                            var latlng = this.getPosition();
                            $('#{$name}lat').val(latlng.lat());
                            $('#{$name}lng').val(latlng.lng());
                            {$name}panoramasv.getPanoramaByLocation(latlng, 50, processSVData);
                        });
                        
                        google.maps.event.addListener({$name}map, 'click', function(event) {
                            {$name}marker.setPosition(event.latLng);
                            $('#{$name}lat').val(event.latLng.lat());
                            $('#{$name}lng').val(event.latLng.lng());
                            {$name}panoramasv.getPanoramaByLocation(event.latLng, 50, processSVData);
                        });
                        
                        google.maps.event.addListener({$name}panorama, 'pov_changed', function() {
                            $('#{$name}heading').val({$name}panorama.getPov().heading);
                            $('#{$name}pitch').val({$name}panorama.getPov().pitch);
                            $('#{$name}zoom').val({$name}panorama.getPov().zoom);
                        });

                        google.maps.event.addListener({$name}panorama, 'position_changed', function() {
                            {$name}marker.setPosition({$name}panorama.getPosition());
                            $('#{$name}lat').val({$name}panorama.getPosition().lat());
                            $('#{$name}lng').val({$name}panorama.getPosition().lng());
                        });

                        
                        
                        $('#{$name}lat').val({$name}marker.getPosition().lat());
                        $('#{$name}lng').val({$name}marker.getPosition().lng());
                        {$name}panoramasv.getPanoramaByLocation({$name}marker.getPosition(), 50, processSVData);
                    }
                    
                    function processSVData(data, status) {
                        if (status == google.maps.StreetViewStatus.OK) {
                            if ($('#{$name}comportamento').val() == 1) {
                                $('#pano').show();
                                $('#map_canvas').css('width', '50%');
                                $('#{$name}suporta').val('1');
                            }
                            
                            {$name}panorama.setPano(data.location.pano);
                            {$name}panorama.setVisible(true);                            
                        } else {
                            $('#pano').hide();
                            $('#map_canvas').css('width', '100%');
                            $('#{$name}suporta').val('0');
                        }
                        
                        google.maps.event.trigger({$name}map, 'resize');
                    }
                    
                    $(document).ready(function(){
                        {$name}initialize();
                        
                        $('#{$name}comportamento').change(function(){
                            if ($(this).val() == '1') {
                                $('#map_canvas').show();
                                $('#pano').show();
                                $('#map_canvas').css('width', '50%');
                            } else if ($(this).val() == '2') {
                                $('#map_canvas').show();
                                $('#pano').hide();
                                $('#map_canvas').css('width', '100%');
                            } else {
                                $('#map_canvas').hide();
                                $('#pano').hide();
                            }
                            
                            google.maps.event.trigger({$name}map, 'resize');
                            if(typeof {$name}marker !== 'undefined') {
                                {$name}map.setCenter({$name}marker.getPosition());
                                {$name}panoramasv.getPanoramaByLocation({$name}marker.getPosition(), 50, processSVData);
                            }
                        }).val($comportamento).change();
                    });
            </script>
        ";
        return $s;
    }

    public function getAutoFormField() {
        $name = $this->CtrlName;
        $uf = $this->uf;
        $cidade = $this->cidade;
        $bairro = $this->bairro;
        $rua = $this->rua;
        $numero = $this->numero;
        $lat = $this->lat;
        $lng = $this->lng;
        $heading = $this->heading;
        $pitch = $this->pitch;
        $zoom = $this->zoom;
        $suporta = $this->suporta;
        $height = $this->height;

        $s = "
        <div class='ctrlmapaponto'>
            <select id='{$name}comportamento' name='{$name}comportamento'>
                <option value='1' selected>Exibir mapa e visão da rua</option>
                <option value='2'>Exibir somente mapa</option>
                <!--option value='3'>Não exibir</option-->
            </select>
            <div class='clearfix'></div>
            <input type='hidden' id='{$name}lat' name='{$name}lat'  value='$lat'  /> 
            <input type='hidden' id='{$name}lng' name='{$name}lng'  value='$lng'  /> 
            <input type='hidden' id='{$name}heading' name='{$name}heading'  value='$heading'  /> 
            <input type='hidden' id='{$name}pitch' name='{$name}pitch'  value='$pitch'  /> 
            <input type='hidden' id='{$name}zoom' name='{$name}zoom'  value='$zoom'  /> 
            <input type='hidden' id='{$name}suporta' name='{$name}suporta'  value='{$suporta}'  /> 

            <div id='map_canvas' style='width: 50%; height: $height;float: left;'></div>
            <div id='pano' style='width: 50%; height: $height;float: left;'></div>
            <div class='clearfix'></div>
        </div>
        ";

        return $s;
    }

    public function getCtrl() {
        return $this->getAutoFormField();
    }

    public function SaveByPost() {
        $name = $this->CtrlName;

        $this->lat = $_POST[$name . "lat"];
        $this->lng = $_POST[$name . "lng"];
        $this->heading = $_POST[$name . "heading"];
        $this->pitch = $_POST[$name . "pitch"];
        $this->zoom = $_POST[$name . "zoom"];
        $this->comportamento = $_POST[$name . "comportamento"];
        $this->suporta = $_POST[$name . "suporta"];

        $obj = new objLocmapaponto($this->Conexao);
        if (isset($this->codPontoMapa)) {
            $obj->setCod($this->codPontoMapa);
        }
        $obj->setLat($this->lat);
        $obj->setLng($this->lng);
        $obj->setHeading($this->heading);
        $obj->setPitch($this->pitch);
        $obj->setZoom($this->zoom);
        $obj->setComportamento($this->comportamento);
        $obj->setSuportaview($this->suporta);

        if (!$obj->Save()) {
            return false;
        }

        $this->codPontoMapa = $obj->getCod();
        return true;
    }

}

class CtrlMapaPontoLatLng {

    private $Conexao;
    private $CtrlName;
    //From params
    public $uf = "SC";
    public $cidade = "Florianópolis";
    public $bairro = "Canasvieiras";
    public $rua = "Rua Mario Lacombe";
    public $numero = "314";
    //From db
    public $codPontoMapa;
    public $comportamento = 1;
    public $suporta = 0;
    public $lat = "";
    public $lng = "";
    public $heading = 0;
    public $pitch = 10;
    public $zoom = 1;
    public $height;

    function __construct($Conexao, $CtrlName = "locmapa", $height = "350px") {
        $this->Conexao = $Conexao;
        $this->CtrlName = $CtrlName;
        $this->height = $height;
    }

    public function loadByParams($uf, $cidade, $bairro, $rua, $numero) {
        $this->uf = $uf;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->rua = $rua;
        $this->numero = $numero;
    }

    public function loadByObjLocalizacao(objLocalizacao $objLocalizacao) {
        $this->uf = $objLocalizacao->objEstado()->getUf();
        $this->cidade = $objLocalizacao->objCidade()->getNome();
        $this->bairro = $objLocalizacao->objBairro()->getNome();
        $this->rua = $objLocalizacao->getRua();
        $this->numero = $objLocalizacao->getNumero();
    }

    public function loadByCod($codPontoMapa) {
        $this->codPontoMapa = $codPontoMapa;

        $obj = new objLocmapaponto($this->Conexao);
        $obj->loadByCod($codPontoMapa);

        $this->lat = $obj->getLat();
        $this->lng = $obj->getLng();
        $this->heading = $obj->getHeading();
        $this->pitch = $obj->getPitch();
        $this->zoom = $obj->getZoom();
        $this->comportamento = $obj->getComportamento();
        $this->suporta = $obj->getSuportaview();
    }

    public function getHead($addJsMapsApi = true) {
        $name = $this->CtrlName;
        $uf = $this->uf;
        $cidade = $this->cidade;
        $bairro = $this->bairro;
        $rua = $this->rua;
        $numero = $this->numero;
        $lat = $this->lat;
        $lng = $this->lng;
        $heading = $this->heading;
        $pitch = $this->pitch;
        $zoom = $this->zoom;
        $comportamento = $this->comportamento;

        $s = "";
        if ($addJsMapsApi) {
            $s.= "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=" . ___googleMapsAPI . "'></script>";
        }

        $s .= "
            <script type='text/javascript'>

                var {$name}marker;
                var {$name}map;
                
                function {$name}initialize() {

                    var {$name}mapOptions = {
                        zoom: 15,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        mapTypeControl: false
                    };

                    {$name}map = new google.maps.Map(document.getElementById('map_canvas'), {$name}mapOptions);
                        
        ";

        if (!$lat || !$lng) {
            $s.= "
                    //inicia pelo endereço
                        var {$name}address = '$rua, $numero - $bairro, $cidade - $uf, Brasil';
                        var {$name}geocoder = new google.maps.Geocoder();
                        {$name}geocoder.geocode( { 'address': {$name}address}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                {$name}map.setCenter(results[0].geometry.location);
                                {$name}marker = new google.maps.Marker({
                                    map: {$name}map,
                                    draggable:true,
                                    animation: google.maps.Animation.DROP,
                                    position: results[0].geometry.location
                                });

                                {$name}addMarkerListener();
                            } else {
                                alert('Não foi possível geocodificar o endereço, você deve localizar manualmente: ' + status);
                            }
                        });
                                            
                   ";
        } else {
            $s .= "
                    //inicia por latlng
                        var {$name}latlnginicial =  new google.maps.LatLng($lat, $lng);
                        {$name}map.setCenter({$name}latlnginicial);
                        {$name}marker = new google.maps.Marker({
                            map: {$name}map,
                            draggable:true,
                            animation: google.maps.Animation.DROP,
                            position: {$name}latlnginicial
                        });
                        
                        {$name}addMarkerListener();
                    ";
        }

        $s.="
                    }
                    
                    function {$name}addMarkerListener() {
                        google.maps.event.addListener({$name}marker, 'dragend', function() {
                            var latlng = this.getPosition();
                            $('#{$name}lat').val(latlng.lat());
                            $('#{$name}lng').val(latlng.lng());
                        });
                        
                        google.maps.event.addListener({$name}map, 'click', function(event) {
                            {$name}marker.setPosition(event.latLng);
                            $('#{$name}lat').val(event.latLng.lat());
                            $('#{$name}lng').val(event.latLng.lng());
                        });
                                                                        
                        
                        $('#{$name}lat').val({$name}marker.getPosition().lat());
                        $('#{$name}lng').val({$name}marker.getPosition().lng());
                    }
                    
                    $(document).ready(function(){
                        {$name}initialize();
                        
                        $('#{$name}comportamento').change(function(){
                            if ($(this).val() == '2') {
                                $('#map_canvas').show();   
                            } else {
                                $('#map_canvas').hide();
                            }
                            
                            google.maps.event.trigger({$name}map, 'resize');
                            if(typeof {$name}marker !== 'undefined') {
                                {$name}map.setCenter({$name}marker.getPosition());                                
                            }
                        }).val($comportamento).change();
                    });
            </script>
        ";
        return $s;
    }

    public function getAutoFormField() {
        $name = $this->CtrlName;
        $uf = $this->uf;
        $cidade = $this->cidade;
        $bairro = $this->bairro;
        $rua = $this->rua;
        $numero = $this->numero;
        $lat = $this->lat;
        $lng = $this->lng;
        $heading = $this->heading;
        $pitch = $this->pitch;
        $zoom = $this->zoom;
        $height = $this->height;

        $s = "
        <div class='ctrlmapaponto'>
            <select id='{$name}comportamento' name='{$name}comportamento'>
                <option value='2'>Exibir somente mapa</option>
                <option value='3'>Não exibir</option>
            </select>
            <div class='clearfix'></div>
            <input type='hidden' id='{$name}lat' name='{$name}lat'  value='$lat'  /> 
            <input type='hidden' id='{$name}lng' name='{$name}lng'  value='$lng'  /> 
            <input type='hidden' id='{$name}heading' name='{$name}heading'  value='$heading'  /> 
            <input type='hidden' id='{$name}pitch' name='{$name}pitch'  value='$pitch'  /> 
            <input type='hidden' id='{$name}zoom' name='{$name}zoom'  value='$zoom'  /> 

            <div id='map_canvas' style='width: 100%; height: $height;float: left;'></div>
            <div class='clearfix'></div>
        </div>
        ";

        return $s;
    }

    public function getCtrl() {
        return $this->getAutoFormField();
    }

    public function SaveByPost() {
        $name = $this->CtrlName;

        $this->lat = $_POST[$name . "lat"];
        $this->lng = $_POST[$name . "lng"];
        $this->heading = $_POST[$name . "heading"];
        $this->pitch = $_POST[$name . "pitch"];
        $this->zoom = $_POST[$name . "zoom"];
        $this->comportamento = $_POST[$name . "comportamento"];

        $obj = new objLocmapaponto($this->Conexao);
        if (isset($this->codPontoMapa)) {
            $obj->setCod($this->codPontoMapa);
        }
        $obj->setLat($this->lat);
        $obj->setLng($this->lng);
        $obj->setHeading($this->heading);
        $obj->setPitch($this->pitch);
        $obj->setZoom($this->zoom);
        $obj->setComportamento($this->comportamento);
        $obj->setSuportaview($this->suporta);

        if (!$obj->Save()) {
            return false;
        }

        $this->codPontoMapa = $obj->getCod();
        return true;
    }

}
