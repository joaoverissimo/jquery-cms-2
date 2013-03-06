<?PHP

require 'jquerycms/config.php';
header('Content-type: image/jpeg');

//OBTEM O REGISTRO
$imagemid = (string) $_GET['img'];

$obj = new objJqueryimage($Conexao);
$obj->loadByCod($imagemid);

$image_file = $obj->getValor();
$image_path = $obj->getFolder() . $obj->getValor();

//OBTEM A IMAGEM E OS PARAMETROS
$image = imagecreatefromjpeg($image_path);

$width = imagesx($image);
$height = imagesy($image);

if (isset($_GET['width']))
    $thumb_width = $_GET['width']; else
    $thumb_width = $width;
if (isset($_GET['height']))
    $thumb_height = $_GET['height']; else
    $thumb_height = $height;

//SE NÃO HOUVER MEDIDAS PARA THUMB DEVOLVE A IMAGEM ORIGINAL
if ($thumb_height == $height && $thumb_width == $width) {
    imagejpeg($image, "", 80);
    exit();
}

//OBTEM DO CACHE
$filemtime = filemtime($image_path);
$filename = ___AppRoot . "jquerycms/upload/images/imagecache/$image_file-$filemtime--$thumb_width-x-$thumb_height.cache";

if (arquivos::existe($filename)) {
    $time = $filemtime - time();
    if ($time < 60 * 60 * 24) {
        arquivos::deletar($filename);
    } else {
        $cache = imagecreatefromjpeg($filename);
        imagejpeg($cache, "", 80);
        exit();
    }
}

//REDIMENSIONA E IMPRIME
$original_aspect = $width / $height;
$thumb_aspect = $thumb_width / $thumb_height;

if ($original_aspect >= $thumb_aspect) {
    $new_height = $thumb_height;
    $new_width = $width / ($height / $thumb_height);
} else {
    $new_width = $thumb_width;
    $new_height = $height / ($width / $thumb_width);
}

$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

imagecopyresampled($thumb, $image, 0 - ($new_width - $thumb_width) / 2, 0 - ($new_height - $thumb_height) / 2, 0, 0, $new_width, $new_height, $width, $height);

//SALVA A IMAGEM E DEPOIS IMPRIME NA TELA
imagejpeg($thumb, $filename, 80);
imagejpeg($thumb, "", 80);