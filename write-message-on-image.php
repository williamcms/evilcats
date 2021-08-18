<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){

$message = $_POST['message'];
$file = $_POST['file'];

//Final da mensagem
$messageEnd = '00101110';

//Codifica a mensagem em binário
$encodedMessage = '';
for($i = 0; $i < mb_strlen($message); $i++){
	//Retorna um valor ASCII representante de cada letra da mensagem para posterior conversão
	$char = ord($message[$i]);
	//Converte o valor ASCII para binário (decbin) e verífica se possui 8 digitos (str_pad), se não, preenche
	$encodedMessage .= str_pad(decbin($char), 8, '0', STR_PAD_LEFT);
}
//Verifica se a mensagem possui um final ($MessageEnd), caso não, insere o 'ponto final'
if(!in_array($messageEnd, str_split($encodedMessage, 8))){
	$encodedMessage .= $messageEnd;
}

//Carrega a imagem na memória
$imageToWrite = imagecreatefrombmp($file);

//Verifica as dimensões da imagem
$width = imagesx($imageToWrite);
$height = imagesy($imageToWrite);

$messagePosition = 0;

for($y = 0; $y < $height; $y++){
	for($x = 0; $x < $width; $x++){

		//Verifica se a mensagem já acabou
		if(!isset($encodedMessage[$messagePosition])){
			break 2;
		}

		//Extrai as cores
		$rgb = imagecolorat($imageToWrite, $x, $y);
		$colors = imagecolorsforindex($imageToWrite, $rgb);

		$red = $colors['red'];     //vermelho
		$green = $colors['green']; //verde
		$blue = $colors['blue'];   //azul
		$alpha = $colors['alpha']; //opacidade

		//Converte o Azul para Binário
		$binColor = str_pad(decbin($blue), 8, '0', STR_PAD_LEFT);

		//Insere a mensagem no bit final (LSB) da cor
		$binColor[strlen($binColor) - 1] = $encodedMessage[$messagePosition];
		//Converte o binário para número (cor)
		$EditColor = bindec($binColor);

		//Aloca as cores para a imagem
		$newColor = imagecolorallocatealpha($imageToWrite, $red, $green, $EditColor, $alpha);
		//Define o pixel editado na imagem
		imagesetpixel($imageToWrite, $x, $y, $newColor);

		$messagePosition++;
	}
}

//Sava uma cópia da imagem com o nome modificado
$newImage = explode('.', $file);
$newImage[0] .= '_edit.';
$newImage = implode('', $newImage);

imagebmp($imageToWrite, $newImage);

//Apaga a imagem que foi previamente carregada na memória
imagedestroy($imageToWrite);

echo json_encode(array('status' => true, 'message' => 'File edited successfully.', 'image' => $newImage));
exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<meta charset="utf-8">
	<title>Write Message on Image</title>
</head>
<body class="m-5">
	<form method="POST">
		<div class="form-group">
			<label>Mensagem</label><input type="text" class="form-control" name="message">
		</div>
		<div class="form-group">
			<label>Imagem</label><input type="text" class="form-control" name="file">
		</div>
		<input type="submit" class="btn btn-primary mt-2" value="Enviar" />
	</form>

</body>
</html>