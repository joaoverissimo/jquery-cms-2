<?php

$internacionalizacaoFileContent;
if (___MyDebugger) {
    internacionalizacao::CriarLocaleFile();
}

function __($s) {
    $lang = internacionalizacao::Lang();

    if (isset($lang[$s])) {
        return trim($lang[$s]);
    } else {
        return $s;
    }
}

class internacionalizacao {

    public static function getCurrentLang() {
        if (isset($_GET["lang"]) && $_GET["lang"]) {
            return $_GET["lang"];
        } elseif (isset($_POST["lang"]) && $_POST["lang"]) {
            return $_POST["lang"];
        } elseif (isset($_SESSION["lang"]) && $_SESSION["lang"]) {
            return $_SESSION["lang"];
        } elseif (isset($_REQUEST["lang"]) && $_REQUEST["lang"]) {
            return $_REQUEST["lang"];
        } else {
            return "pt-br";
        }
    }

    public static function getCurrentLangInteger() {
        $lang = self::getCurrentLang();

        if ($lang == "en-us")
            return 3;
        elseif ($lang == "es-ar")
            return 2;
        else
            return 1;
    }

    public static function Lang() {
        global $internacionalizacaoFileContent;
        $internacionalizacaoLang = self::getCurrentLang();

        if (!isset($internacionalizacaoFileContent)) {
            $filename = ___AppRoot . "locale/" . $internacionalizacaoLang . "/locale.csv";
            if (arquivos::existe($filename))
                $internacionalizacaoFileContent = self::abrirArquivoCsv($filename);
        }

        return $internacionalizacaoFileContent;
    }

    public static function abrirArquivoCsv($filename) {
        $content = arquivos::ler($filename);
        //$s = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
        $conteudo = $content;
        $retorno = Array();

        $linhas = explode("\n", $conteudo);
        foreach ($linhas as $linha) {
            $keys = explode(";", $linha);
            if (isset($keys[0]))
                $key0 = $keys[0];
            else
                $key0 = false;

            if (isset($keys[1]))
                $key1 = $keys[1];
            else
                $key1 = false;

            if ($key0 && $key1)
                $retorno[$key0] = $key1;
        }

        return $retorno;
    }

    private static function recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public static function CriarLocaleFile() {
        $dir = ___AppRoot . "locale/" . internacionalizacao::getCurrentLang() . "/";
        if (!file_exists($dir)) {
            $dir_pt = ___AppRoot . "locale/pt-br/";
            self::recurse_copy($dir_pt, $dir);
        }

        $dados = arquivos::lerDiretorio($dir, array("locale.csv"));
        if (issetArray($dados)) {
            $s = "";
            foreach ($dados as $arquivo) {
                $s .= arquivos::ler($dir . $arquivo);
            }

            arquivos::criar($s, $dir . "locale.csv");
        }
    }

    public static function TraduzirString($s) {
        $sRegExp = '/__\(\'(.*?)\'\)/';
        preg_match_all($sRegExp, $s, $arr);
        if (issetArray($arr[0]) && issetArray($arr[1])) {
            $keys = $arr[0];
            $values = $arr[1];
            for ($index = 0; $index < count($keys); $index++) {
                $s = str_replace($keys[$index], __($values[$index]), $s);
            }
        }

        $sRegExp = '/__\((.*?)\)/';
        preg_match_all($sRegExp, $s, $arr);
        if (issetArray($arr[0]) && issetArray($arr[1])) {
            $keys = $arr[0];
            $values = $arr[1];
            for ($index = 0; $index < count($keys); $index++) {
                $s = str_replace($keys[$index], __($values[$index]), $s);
            }
        }

        return $s;
    }

    public static function AdicionarLangQueryString($url, $lang = "") {
        $internacionalizacaoLangDefault = "pt-br";

        if ($lang == "")
            $lang = self::getCurrentLang();

        if ($lang == $internacionalizacaoLangDefault) {
            if (str_endsWith($url, "&"))
                return str_removeLastChar($url);
            else
                return $url;
        } else {
            if ($lang)
                $lang = $lang;

            if (!str_contains($url, ___siteUrl))
                return "/" . $lang . $url;
            else
                return str_replace(".br/", ".br/" . $lang . "/", $url);
        }
    }

}
