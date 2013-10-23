<?php

$cacheFolder = cacheClear::obterDocumentRoot() . "cache/";
$cacheFiles = cacheClear::lerDiretorio($cacheFolder);
if (cacheClear::issetArray($cacheFiles)) {
    $i = 0;
    foreach ($cacheFiles as $file) {
        cacheClear::deletar($cacheFolder . $file);

        if ($i > 200) {
            die("Aguarde... limpando cache<script>setTimeout(function(){location.reload()}, 20);</script>");
        }

        $i++;
    }
}

echo "Cache foi limpo";

class cacheClear {

    public static function obterDocumentRoot() {
        $AppRoot = $_SERVER['DOCUMENT_ROOT'];
        if ($AppRoot[strlen($AppRoot) - 1] != "/")
            $AppRoot.= "/";

        return $AppRoot;
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

    public static function issetArray($arr) {
        return isset($arr) && is_array($arr) && count($arr) > 0;
    }

    public static function existe($filename) {
        if (file_exists($filename))
            return true;
        else
            return false;
    }

    public static function deletar($filename) {
        if (self::existe($filename) && is_file($filename))
            return unlink($filename);
        else
            return false;
    }

}
