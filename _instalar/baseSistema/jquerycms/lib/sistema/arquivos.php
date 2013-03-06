<?php

require_once 'zip/dUnzip2.inc.php';

class arquivos {

    public static function existe($filename) {
        if (file_exists($filename))
            return true;
        else
            return false;
    }

    public static function criar($dados, $filename, $replaceTab = false) {
        if ($replaceTab)
            $dados = str_replace("\t", "    ", $dados);

        $fh = fopen($filename, 'w') or die("can't open file");
        fwrite($fh, $dados);
        fclose($fh);
        return true;
    }

    public static function ler($filename) {
        if (arquivos::existe($filename)) {
            $file = file_get_contents($filename, true);
            if (isset($file)) {
                return $file;
            }
        }

        return "";
    }

    public static function lerUrl($url) {
        try {
            $s = @file_get_contents($url);
            if ($s) {
                return $s;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            return false;
        }
    }

    public static function lerXml($filename) {
        $xml = simplexml_load_file($filename);
        //usar: echo $xml->route->leg->duration->text;
        return $xml;
    }

    public static function lerDiretorio($dir, $ignore = array()) {
        $dh = opendir($dir);
        $files = array();
        while (($file = readdir($dh)) !== false) {
            $flag = false;
            if ($file !== '.' && $file !== '..' && !in_array($file, $ignore)) {
                $files[] = $file;
            }
        }
        return $files;
    }

    public static function downloadFile($url, $filename) {
        try {
            $down = file_get_contents($url);
            return file_put_contents($filename, $down);
        } catch (Exception $exc) {
            return false;
        }
    }

    public static function UnzipFile($fileIn, $folderOut) {
        try {
            $zip = new dUnzip2($fileIn);

            $zip->unzipAll($folderOut);
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    public static function deletar($filename) {
        if (self::existe($filename))
            return unlink($filename);
        else
            return false;
    }

    public static function copiar($in, $out) {
        return copy($in, $out);
    }

    public static function arquivoExtensao($filename) {
        $fileext = pathinfo($filename, PATHINFO_EXTENSION);
        $fileext = strtolower($fileext);

        return $fileext;
    }

    public static function uploadFile($FormFieldNome, $filename, $filefolder = '') {
        if (!isset($_FILES[$FormFieldNome]))
            throw new jquerycmsException("Não foi enviado o arquivo $FormFieldNome.");

        if ($_FILES[$FormFieldNome]["error"] > 0) {
            throw new jquerycmsException("Problemas ao salvar arquivo: " . $_FILES[$FormFieldNome]["error"]);
        }

        if ($filefolder == '') {
            $filefolder == ___AppRoot . "jquerycms/upload/files/";
        }

        if (!move_uploaded_file($_FILES[$FormFieldNome]["tmp_name"], $filefolder . $filename)) {
            throw new jquerycmsException("Problemas ao mover arquivo de <b>{$_FILES[$FormFieldNome]["tmp_name"]}</b> para: <b>{$filefolder}/{$filename}</b>");
        }

        return true;
    }

    public static function uploadImage($FormFieldNome, $prefix = '', $filefolder = '') {
        if (!isset($_FILES[$FormFieldNome]))
            throw new jquerycmsException("Não foi enviado o arquivo $FormFieldNome.");

        if ((($_FILES[$FormFieldNome]["type"] == "image/gif")
                || ($_FILES[$FormFieldNome]["type"] == "image/jpeg")
                || ($_FILES[$FormFieldNome]["type"] == "image/png")
                || ($_FILES[$FormFieldNome]["type"] == "image/pjpeg"))) {

            if ($_FILES[$FormFieldNome]["error"] > 0) {
                throw new jquerycmsException("Problemas ao salvar arquivo: " . $_FILES[$FormFieldNome]["error"]);
            }

            if ($filefolder == '') {
                $filefolder = ___AppRoot . "jquerycms/upload/images/";
            }

            $fileext = arquivos::arquivoExtensao($_FILES[$FormFieldNome]['name']);

            $filename = preg_replace("/[^A-Za-z0-9]/", '', $_FILES[$FormFieldNome]["name"]);
            $filename = strtolower($filename);
            $filename = str_replace($fileext, '', $filename);

            while (arquivos::existe($filefolder . $prefix . $filename . '.' . $fileext)) {
                $int = str_toInteger($filename);
                $filename = str_replace($int, '', $filename);
                $filename .= $int + 1;
            }

            if (!move_uploaded_file($_FILES[$FormFieldNome]["tmp_name"], $filefolder . $prefix . $filename . '.' . $fileext)) {
                throw new jquerycmsException("Problemas ao mover arquivo de <b>{$_FILES[$FormFieldNome]["tmp_name"]}</b> para: <b>{$filefolder}/{$filename}</b>");
            }

            return $prefix . $filename . '.' . $fileext;
        } else {
            throw new jquerycmsException("O arquivo <b>{$_FILES[$FormFieldNome]["name"]}</b> não é uma imagem.");
        }
    }

    public static function resizeImageMaxSize($fileimage, $width, $height) {
        $image = new SimpleImage();
        $image->load($fileimage);

        if ($image->getWidth() > $image->getHeight()) {
            //Se for retrato
            if ($image->getWidth() < $width) {
                //Se o tamanho da imagem for menor que o width
                return true;
            }

            $image->resizeToWidth($width);
        } else {
            //Se for paisagem
            if ($image->getHeight() < $height) {
                //Se o tamanho da imagem for menor que o height
                return true;
            }

            $image->resizeToHeight($height);
        }

        $image->save($fileimage);
    }

}

?>