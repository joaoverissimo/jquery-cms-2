<?php

require_once "base/fbaseLocmapaponto.php";

class objLocmapaponto extends fbaseLocmapaponto {

// <editor-fold defaultstate="collapsed" desc="Relacoes Inversas">    
    /**
     * Relacao imovel.locmapaponto -> locmapaponto.cod
     * @return objImovel[]
     */
    /* public function obtemImovelRel ($orderByField = "", $orderByOrientation = "", $limit = "") {
      $orderBy = new dataOrder($orderByField, $orderByOrientation);
      $where = new dataFilter("imovel.locmapaponto", $this->getCod());
      $dados = dbImovel::ObjsList($this->Conexao, $where, $orderBy, $limit);
      return $dados;
      } */


// </editor-fold>

    /* public function getRewriteUrl($fullUrl = false) {
      $cod = $this->getCod();
      $titulo = toRewriteString($this->getTitulo());

      $link = "locmapaponto/$titulo/$cod/";

      $lang = internacionalizacao::getCurrentLang();
      if ($lang != "pt-br") {
      $url = $lang . "/" . $url;
      }

      if ($fullUrl)
      return ___siteUrl . $link;
      else
      return "/" . $link;
      } */

    public function getGmapsStreetView($height = "200px", $addJsMapsApi = true) {
        if (!$this->getSuportaview()) {
            return false;
        }

        if (strpos($height, "px") === false) {
            $height .= "px";
        }

        $cod = $this->getCod();
        $lat = $this->getLat();
        $lng = $this->getLng();
        $heading = $this->getHeading();
        $pitch = $this->getPitch();
        $zoom = $this->getZoom();

        $s = "";

        if ($addJsMapsApi) {
            $s.= "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?sensor=false'></script>";
        }

        $s .= "
        <script>
            function initializeMapView$cod() {
                var bryantPark = new google.maps.LatLng($lat, $lng);
                var panoramaOptions = {
                  position:bryantPark,
                  pov: {
                    heading: $heading,
                    pitch: $pitch,
                    zoom: $zoom
                  }
                };
                var myPano = new google.maps.StreetViewPanorama(document.getElementById('pano$cod'), panoramaOptions);
                myPano.setVisible(true);
            }
        
            $(document).ready(function(){
                initializeMapView$cod();
            });
        </script>
        
        <div class='pano' id='pano$cod' style='width: 100%; height: $height;'></div>
        ";

        return $s;
    }

    public function getGmapsSiples($title, $zoom = 12, $height = "200px", $scrollwheel = false, $addJsMapsApi = true) {
        $cod = $this->getCod();
        $lat = $this->getLat();
        $lng = $this->getLng();
		
        $title = str_replace("'", "", $title);

        if (strpos($height, "px") === false) {
            $height .= "px";
        }

        if ($scrollwheel)
            $scrollwheel = 'true';
        else
            $scrollwheel = 'false';

        $s = "";

        if ($addJsMapsApi) {
            $s.= "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?sensor=false'></script>";
        }

        $s .= "
        <script>
            function initializeMap$cod() {
                var myLatlng = new google.maps.LatLng($lat,$lng);
                var mapOptions = {
                    zoom: $zoom,
                    center: myLatlng,
                    scrollwheel: $scrollwheel,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                var map = new google.maps.Map(document.getElementById('map_canvas$cod'), mapOptions);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title:'$title'
                });
            }
        
            $(document).ready(function(){
                initializeMap$cod();
            });
        </script>
        
        <div class='map_canvas' id='map_canvas$cod' style='width: 100%; height: $height;'></div>
        ";

        return $s;
    }

	public function getGmapsStaticImage($zoom = 12, $width = "300px", $height = "200px", $cache = false) {
        $cod = $this->getCod();
        $lat = $this->getLat();
        $lng = $this->getLng();

        $width = str_replace("px", "", $width);
        $height = str_replace("px", "", $height);

        $url = "http://maps.googleapis.com/maps/api/staticmap?center={$lat},{$lng}&zoom={$zoom}&size={$width}x{$height}&maptype=roadmap%20&markers=color:red%7C{$lat},{$lng}&sensor=false";
        
        if ($cache) {
            $filename = "map-$cod-$width-$height.jpg";
            $filefolder = $folder = ___AppRoot . "jquerycms/upload/map-cache/";

            if (!arquivos::existe($filefolder)) {
                umask(0);
                mkdir($filefolder, 0777, true);
            }

            if (arquivos::existe($filefolder . $filename) || arquivos::downloadFile($url, $filefolder . $filename)) {
                $url = "/jquerycms/upload/map-cache/" . $filename;
            }
        }
        
        return $url;
    }


    public function getGmapsViewImage($width = "300px", $height = "200px", $cache = false) {
        $cod = $this->getCod();
        $lat = $this->getLat();
        $lng = $this->getLng();

        $heading = $this->getHeading();
        $pitch = $this->getPitch();

        $width = str_replace("px", "", $width);
        $height = str_replace("px", "", $height);

        $url = "http://maps.googleapis.com/maps/api/streetview?size={$width}x{$height}&location={$lat},{$lng}&fov=90&heading={$heading}&pitch={$pitch}&sensor=false";

        if ($cache) {
            $filename = "view-$cod-$width-$height.jpg";
            $filefolder = $folder = ___AppRoot . "jquerycms/upload/map-cache/";

            if (!arquivos::existe($filefolder)) {
                umask(0);
                mkdir($filefolder, 0777, true);
            }

            if (arquivos::existe($filefolder . $filename) || arquivos::downloadFile($url, $filefolder . $filename)) {
                $url = "/jquerycms/upload/map-cache/" . $filename;
            }
        }

        return $url;
    }
	
}