<?php

function Fncs_SomarData($data, $dias, $meses, $ano) {
    /* www.brunogross.com */
    $data = explode("/", $data);
    $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano));
    return $newData;
}

function Fncs_FiltrarArray_by_value($array, $index, $value) {
    if (is_array($array) && count($array) > 0) {
        foreach (array_keys($array) as $key) {
            $temp[$key] = $array[$key][$index];

            if ($temp[$key] == $value) {
                $newarray[$key] = $array[$key];
            }
        }
    }
    if (isset($newarray))
        return $newarray;
    else
        return null;
}

function Fncs_ValidaQueryString($queryString, $redirecionarPara, $redirecionar = true) {
    if (isset($_REQUEST[$queryString]) && $_REQUEST[$queryString] != "") {
        return true;
    } elseif ($redirecionar) {
        header('Location: ' . $redirecionarPara);
        exit();
    } else {
        return false;
    }
}

function Fncs_LerData($data) {
    try {
        //Testa se a data é nula
        if (isset($data)) {
            $dataArray = explode('-', $data);
            ////Testa se a data é um array válido
            if (is_array($dataArray) && isset($dataArray[0]) && isset($dataArray[1]) && isset($dataArray[2])) {
                //Retorna a data formatada
                return $dataArray[2] . '/' . $dataArray[1] . '/' . $dataArray[0];
            } else {
                //Não é um array válido, retornará a data original
                return $data;
            }
        } else {
            //É nulo e retorna nulo
            return "";
        }
    } catch (Exception $exc) {
        throw new jquerycmsException("Problema para ler data, com o valor $data. ");
    }
}

function Fncs_LerDataTime($data) {
    //2012-01-16 00:00:00
    //   0; 1; 2 ; 3;4;5
    try {
        //Testa se a data é nula
        if (!is_null($data)) {
            $s = str_replace("-", ";", $data);
            $s = str_replace(" ", ";", $s);
            $s = str_replace(":", ";", $s);

            $dataArray = explode(';', $s);
            ////Testa se a data é um array válido
            if (issetArray($dataArray)) {
                //Retorna a data formatada
                return $dataArray[2] . '/' . $dataArray[1] . '/' . $dataArray[0] . ' ' . $dataArray[3] . ':' . $dataArray[4] . ':' . $dataArray[5];
            } else {
                //Não é um array válido, retornará a data original
                return $data;
            }
        } else {
            //É nulo e retorna nulo
            return "00/00/0000 00:00:00";
        }
    } catch (Exception $exc) {
        throw new jquerycmsException("Problema para ler data time, com o valor $data. ");
    }
}

function Fncs_LerDataTimeRtData($data) {
    $data = explode(" ", $data);
    if (isset($data[0]))
        return $data[0];
    else
        return "00/00/0000";
}

function Fncs_LerMoeda($integer) {
    return "R$ " . $integer . ",00";
}

function str_startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function str_endsWith($haystack, $needle) {
    $length = strlen($needle);
    $start = $length * -1; //negative
    return (substr($haystack, $start) === $needle);
}

function str_removeLastChar($haystack) {
    $length = strlen($haystack);
    $start = $length * -1; //negative
    if ($start)
        return substr($haystack, $start);
    else
        return $haystack;
}

function str_contains($haystack, $needle, $ignoreCase = false) {
    if ($ignoreCase) {
        $haystack = strtolower($haystack);
        $needle = strtolower($needle);
    }
    $needlePos = strpos($haystack, $needle);

    if ($needlePos === false)
        return false;
    else
        return true;
}

function str_toInteger($s) {
    if (str_endsWith($s, ",00"))
        $s = str_replace(",00", "", $s);

    if (str_endsWith($s, ".00"))
        $s = str_replace(".00", "", $s);

    $s = preg_replace("/[^0-9]/", "", $s);

    return intval($s);
}

function str_dataPtBrToMySql($data) {
    try {
        return implode('-', array_reverse(explode('/', $data)));
    } catch (Exception $exc) {
        echo "<!--ATENÇÃO: Ocorreu um erro ao converter a data; Função dataPtBrToMySql().-->";
        return $data;
    }
}

function issetpost($nome_campo) {
    if (isset($_POST["$nome_campo"]))
        return $_POST["$nome_campo"];
    else
        return "";
}

function issetpostInteger($nome_campo) {
    if (isset($_POST["$nome_campo"])) {
        if ($_POST["$nome_campo"] == "on")
            return 1;

        return intval($_POST["$nome_campo"]);
    } else
        return 0;
}

function Fncs_EnviarEmail($para, $from, $mensagemHTML, $assunto, $charset = "utf-8", $Cc = "", $Bcc = "") {
    $emaildestinatario = $para;
    $emailsender = $from;

    // Se for servidor win deve ser "\r\n", como é linux será "\n"
    $quebra_linha = "\n";

    /* Montando o cabeçalho da mensagem */
    $headers = "MIME-Version: 1.1" . $quebra_linha;
    $headers .= "Content-type: text/html; charset=$charset" . $quebra_linha;
    $headers .= "From: " . $emailsender . $quebra_linha;
    $headers .= "Reply-To: " . $emailsender . $quebra_linha;

    //É obrigatório o uso do parâmetro -r (concatenação do "From na linha de envio"), na Locaweb - se tivermos problemas podemos investicar no nosso servidor

    if (!mail($emaildestinatario, $assunto, $mensagemHTML, $headers, "-r" . $emailsender)) { // Se for Postfix
        $headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
        if (mail($emaildestinatario, $assunto, $mensagemHTML, $headers)) {
            return true;
            echo '<!-- Contato Sucesso -->';
        } else {
            echo '<!-- Ocorreu um erro ao enviar e-mail -->';
            return false;
        }
    } else {
        echo '<!-- Contato Sucesso - POST FIX-->';
        return true;
    }
}

function toRewriteString($s, $permitePonto = false) {
    $s = trim($s);
    $s = mb_strtolower($s, 'UTF-8');

    //Letra a
    $s = str_replace("á", "a", $s);
    $s = str_replace("à", "a", $s);
    $s = str_replace("ã", "a", $s);
    $s = str_replace("â", "a", $s);
    $s = str_replace("ä", "a", $s);

    //letra e
    $s = str_replace("é", "e", $s);
    $s = str_replace("ê", "e", $s);
    $s = str_replace("è", "e", $s);
    $s = str_replace("ë", "e", $s);

    //letra i
    $s = str_replace("í", "i", $s);
    $s = str_replace("ì", "i", $s);
    $s = str_replace("î", "i", $s);
    $s = str_replace("ï", "i", $s);

    //letra o
    $s = str_replace("ó", "o", $s);
    $s = str_replace("ô", "o", $s);
    $s = str_replace("õ", "o", $s);
    $s = str_replace("ò", "o", $s);
    $s = str_replace("ö", "o", $s);

    //letra u
    $s = str_replace("ú", "u", $s);
    $s = str_replace("ü", "u", $s);
    $s = str_replace("û", "u", $s);
    $s = str_replace("ù", "u", $s);

    //letra c
    $s = str_replace("ç", "c", $s);

    //ultimos caracteres indesejaveis
    $s = str_replace("  ", " ", $s);
    $s = str_replace("  ", " ", $s);
    $s = str_replace(" ", "-", $s);

    $s = str_replace("---", "-", $s);
    $s = str_replace("--", "-", $s);

    if ($permitePonto === false) {
        $s = str_replace(".", "", $s);
    }

    $s = preg_replace("/[^a-zA-Z0-9_.-]/", "", $s);
    $s = str_replace("-.", ".", $s);
    return $s;
}

function sortArrayByLenght($a, $b) {
    if (strlen($a) == strlen($b))
        return 1;
    if (strlen($a) > strlen($b))
        return 0;
    return +1;
}

function stringuizeCmp($a, $b) {
    if (strlen($a) == strlen($b))
        return 0;
    if (strlen($a) > strlen($b))
        return -1;
    return 1;
}

function stringuizeStr($str, $parametros) {
    if (!isset($parametros))
        return $str;

    //ordena o array
    uksort($parametros, "stringuizeCmp");

    //realiza a substituicao
    foreach ($parametros as $key => $value) {
        $str = str_replace($key, $value, $str);
    }

    return $str;
}

function stringuize($filename, $parametros, $filefolder = "") {

    if ($filefolder == "")
        $filefolder = ___AppRoot . "lib/templates/";

    $str = arquivos::ler($filefolder . $filename);
    return stringuizeStr($str, $parametros);
}

function issetArray($arr) {
    return isset($arr) && is_array($arr) && count($arr) > 0;
}

function Fncs_GetCurrentURL($ignoreQuerys = false) {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }

    if ($ignoreQuerys) {
        $arr = explode("?", $pageURL);
        if (issetArray($arr) && isset($arr[0])) {
            return $arr[0];
        }
    }

    return $pageURL;
}

function Fncs_TemplateHtml($html) {
    //_request
    if (preg_match_all("/_request\((.*)\)/", $html, $matches)) {
        for ($index = 0; $index < count($matches[0]); $index++) {
            $parametro = trim($matches[1][$index]);
            if (isset($_REQUEST[$parametro])) {
                $html = str_replace($matches[0][$index], $_REQUEST[$parametro], $html);
            } else {
                $html = str_replace($matches[0][$index], "", $html);
            }
        }
    }
    
    //_session
    if (preg_match_all("/_session\((.*)\)/", $html, $matches)) {
        for ($index = 0; $index < count($matches[0]); $index++) {
            $parametro = trim($matches[1][$index]);
            if (isset($_SESSION[$parametro])) {
                $html = str_replace($matches[0][$index], $_SESSION[$parametro], $html);
            } else {
                $html = str_replace($matches[0][$index], "", $html);
            }
        }
    }
    
    return $html;
}