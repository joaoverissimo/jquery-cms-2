<?php

function Creator_Fncs_SomarData($data, $dias, $meses, $ano) {
    /* www.brunogross.com */
    //passe a data no formato dd/mm/yyyy 
    $data = explode("/", $data);
    $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano));
    return $newData;
}

function Creator_Fncs_FiltrarArray_by_value($array, $index, $value) {
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

function Creator_Fncs_ValidaQueryString($queryString, $redirecionarPara, $redirecionar = true) {
    if (isset($_REQUEST[$queryString]) && $_REQUEST[$queryString] != "") {
        return true;
    } elseif ($redirecionar) {
        header('Location: ' . $redirecionarPara);
        exit();
    } else {
        return false;
    }
}

function Creator_Fncs_Funcs_LerData($data) {
    try {
        //Testa se a data é nula
        if (!is_null($data)) {
            $dataArray = explode('-', $data);
            ////Testa se a data é um array válido
            if (is_array($dataArray)) {
                //Retorna a data formatada
                return $dataArray[2] . '/' . $dataArray[1] . '/' . $dataArray[0];
            } else {
                //Não é um array válido, retornará a data original
                return $data;
            }
        } else {
            //É nulo e retorna nulo
            return null;
        }
    } catch (Exception $exc) {
        PsgExceptions($exc);
        return $data;
    }
}

function Creator_Fncs_Funcs_LerDataTime($data) {
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
            if (is_array($dataArray)) {
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
        PsgExceptions($exc);
        return $data;
    }
}

function Creator_Fncs_Funcs_LerDataTimeRtData($data) {
    $data = Creator_Fncs_Funcs_LerDataTime($data);
    $data = explode(" ", $data);
    if (isset($data[0]))
        return $data[0];
    else
        return "00/00/0000";
}

function Creator_Fncs_Funcs_LerMoeda($integer) {
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
        return 0;
    else
        return 1;
}

function issetpost($nome_campo) {
    if (isset($_POST["$nome_campo"]))
        return $_POST["$nome_campo"];
    else
        return "";
}

function issetpostInteger($nome_campo) {
    if (isset($_POST["$nome_campo"]))
        return intval($_POST["$nome_campo"]);
    else
        return 0;
}

function str_toInteger($s) {
    if (str_endsWith($s, ",00"))
        $s = str_replace(",00", "", $s);

    if (str_endsWith($s, ".00"))
        $s = str_replace(".00", "", $s);

    $s = ereg_replace("[^0-9]", "", $s);

    return intval($s);
}

function str_dataPtBrToMySql($data) {
    try {
        return implode('-', array_reverse(split('[/]', $data)));
    } catch (Exception $exc) {
        echo "<!--ATENÇÃO: Ocorreu um erro ao converter a data; Função dataPtBrToMySql().-->";
        return $data;
    }
}

function Creator_Fncs_EnviarEmail($para, $from, $mensagem, $assunto, $charset = "utf-8", $Cc = "", $Bcc = "", $reply_to = "") {
    //Charset = utf-8 ou iso-8859-1
    if (PATH_SEPARATOR == ";")
        $quebra_linha = "\r\n";
    else
        $quebra_linha = "\n";

    if ($reply_to = "")
        $reply_to = $from;

    $headers = "MIME-Version: 1.1" . $quebra_linha;
    $headers .= "Content-type: text/html; charset=$charset" . $quebra_linha;
    $headers .= "From: " . $from . $quebra_linha;

    if ($Cc)
        $headers .= "Cc: " . "eduardo@sistemasparaimobiliarias.com.br" . $quebra_linha;

    if ($Bcc)
        $headers .= "Bcc: " . "ezequiel@areadigital.com.br" . $quebra_linha;

    $headers .= "Reply-To: " . $reply_to . $quebra_linha;

    mail($para, $assunto, $mensagem, $headers);
}

function Creator_Fncs_EnviarEmail2($para, $from, $mensagemHTML, $assunto, $charset = "utf-8", $Cc = "", $Bcc = "") {
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

function toRewriteString($s) {
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

    //caractere "spaço"
    $s_contemEspaco = str_contains($s, "  ");
    while ($s_contemEspaco) {
        $s = str_replace("  ", " ", $s);
        $s_contemEspaco = str_contains($s, "  ");
    }

    //ultimos caracteres indesejaveis
    $s = str_replace(" ", "-", $s);
    $s = ereg_replace("[^a-zA-Z0-9_.-]", "", $s);
    $s = str_replace("-.", ".", $s);
    return $s;
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