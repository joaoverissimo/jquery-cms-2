<?php

require_once "base/dbaseJqueryimage.php";

class dbJqueryimage extends dbaseJqueryimage {

// <editor-fold defaultstate="collapsed" desc="Inserir, Update, Deletar">       
    public static function Inserir($Conexao, $valor, $die = false) {

        return parent::Inserir($Conexao, $valor, $die);
    }

    public static function Update($Conexao, $cod, $valor, $die = false) {

        return parent::Update($Conexao, $cod, $valor, $die);
    }

    public static function Deletar($Conexao, $cod) {
        $obj = new objJqueryimage($Conexao);
        $obj->loadByCod($cod);

        $exec = parent::Deletar($Conexao, $cod);
        if ($exec)
            arquivos::deletar($obj->getFileName());

        return $exec;
    }

// </editor-fold>

    public static function InserirByFileImagems($Conexao, $FormFieldNome, $die = false) {
        $prefix = self::getMax($Conexao, true) . "_";

        if (isset($_FILES[$FormFieldNome . '-file']) && $_FILES[$FormFieldNome . '-file']["size"] > 0) {
            $valor = arquivos::uploadImage($FormFieldNome . '-file', $prefix);
        } else {
            $valor = $prefix . "imagem.jpg";
        }

        $exec = parent::Inserir($Conexao, $valor, $die);

        if ($exec) {
            //Redimensiona
            $obj = new objJqueryimage($Conexao);
            $obj->loadByCod($exec);

            arquivos::resizeImageMaxSize($obj->getFileName(), 1000, 700);
        }

        return $exec;
    }

    public static function UpdateByFileImagems($Conexao, $FormFieldNome, $die = false) {
        $cod = issetpost($FormFieldNome);

        //remove a imagem caso exista e estaja marcado o remover
        if (issetpostInteger($FormFieldNome . "-remover")) {
            $obj = new objJqueryimage($Conexao);
            $obj->loadByCod($cod);

            arquivos::deletar($obj->getFileName());
            $obj->setValor("");
            $obj->Save();
            return $cod;
        }

        //Se nenhuma imagem foi enviada apenas retorna o codigo
        if (!isset($_FILES[$FormFieldNome . '-file']) || $_FILES[$FormFieldNome . '-file']["size"] == 0)
            return $cod;

        //Tenta salvar a imagem e atualizar o registro jqueryimage
        $prefix = $cod . "_";
        $newvalor = arquivos::uploadImage($FormFieldNome . '-file', $prefix);

        if ($newvalor) {
            $obj = new objJqueryimage($Conexao);
            $obj->loadByCod($cod);
            arquivos::deletar($obj->getFileName());


            //Redimensiona
            $obj->setValor($newvalor);
            arquivos::resizeImageMaxSize($obj->getFileName(), 1000, 700);

            if (!$obj->Save()) {
                throw new jquerycmsException("Nao foi possivel salvar o registro!");
            }

            return $cod;
        }


        return false;
    }

    public static function Existe($Conexao, $cod) {
        $registro = self::Carregar($Conexao, $cod);

        if (isset($registro["valor"]) && $registro["valor"]) {
            $obj = new objJqueryimage($Conexao);
            $obj->loadByArray($registro);

            return arquivos::existe($obj->getFileName());
        }

        return false;
    }

    public static function Clonar($Conexao, $cod) {
        $obj = new objJqueryimage($Conexao);
        $obj->loadByCod($cod);

        $in = $obj->getFileName();

        $outFolder = $obj->getFolder();
        $outExtensao = arquivos::arquivoExtensao($obj->getValor());

        $outFilename = preg_replace("/[^A-Za-z]/", '', $obj->getValor());
        $outFilename = str_replace($outExtensao, "", $outFilename);
        $outFilename = self::getMax($Conexao, true) . "_" . $outFilename . "." . $outExtensao;


        if (arquivos::copiar($in, $outFolder . $outFilename)) {
            $obj = new objJqueryimage($Conexao);
            $obj->setValor($outFilename);
            $obj->Save();
            return $obj->getCod();
        }

        return false;
    }

}