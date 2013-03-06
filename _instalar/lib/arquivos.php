<?php

class arquivos {
	
	public static function existe($filename) {
	
		if (file_exists($filename)) 
			return true;
		else
			return false;		
	}
	
	public static function criar($dados, $filename) {
		$dados = str_replace("\t", "    ", $dados);
                
                $fh = fopen($filename, 'w') or die("can't open file");
		fwrite($fh, $dados);
		fclose($fh);
		return true;
	}

	public static function ler($filename) {
		$file = file_get_contents($filename, true);
		return $file;
	}

        public static function adicionarConteudo($dados, $filename) {
                $current = file_get_contents($filename);
                $current .= $dados;
		return file_put_contents($filename, $current);
	}
        
	public static function lerXml($filename) {
		$xml = simplexml_load_file($filename);
		//usar: echo $xml->route->leg->duration->text;
		return $xml;
	}
	
	public static function deletar($filename) {
            if (file_exists($filename))
                return unlink($filename);
	}

        public static function salvarPostFileImageDefineImgName($Conexao, $name) {
            $name = toRewriteString($name);
            
            $id = dbImagems::getMax($Conexao) + 1;
            
            $name = $id . "_" . $name;
            return $name;
        }
        
        public static function salvarPostFileImage($FormFieldNome, $filefolder, $imagemid = 0) {
            
            if ((($_FILES["$FormFieldNome"]["type"] == "image/gif")
                || ($_FILES["$FormFieldNome"]["type"] == "image/jpeg")
                || ($_FILES["$FormFieldNome"]["type"] == "image/png")
                || ($_FILES["$FormFieldNome"]["type"] == "image/pjpeg"))) {

                if ($_FILES[$FormFieldNome]["error"] > 0) {
                    if(___MyDebugger)
                        die("Problemas ao salvar imagem".': '. $_FILES["$FormFieldNome"]["error"]);
                    else
                        return false;
                }
                else {
                    $sValor = self::DefineImgName($_FILES["$FormFieldNome"]["name"]);
                    $filename = $filefolder . $sValor;

                    if (file_exists($filename)) {
                        self::deletar($filename);
                    }
                    
                    move_uploaded_file($_FILES["$FormFieldNome"]["tmp_name"], $filename);
                    return $sValor;
                }
            }
            else {
                if(___MyDebugger)
                    die("Arquivo inválido");
                else
                    return false;
            }
        }
}

?>