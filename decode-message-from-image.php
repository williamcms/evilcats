<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['file'])){

$file = $_GET['file'];
//Carrega a imagem na memória
$imageToRead = imagecreatefrombmp($file);
//Verifica as dimensões da imagem
$width = imagesx($imageToRead);
$height = imagesy($imageToRead);

//Mensagem
$encodedMessage =  '';
$encodedMessageParts = [];

//Final da mensagem
$messageEnd = '00101110';

for($y = 0; $y < $height; $y++){
	for($x = 0; $x < $width; $x++){
		//Extrai a cor
		$rgb = imagecolorat($imageToRead, $x, $y);
		$colors = imagecolorsforindex($imageToRead, $rgb);
		//Seleciona a cor azul, a qual está a mensagem
		$blue = $colors['blue'];

		//Converte o azul para binário
		$binColor = decbin($blue);

		//Extrai o último bit (LSB) das cores
		$encodedMessageParts[] = $binColor[strlen($binColor) - 1];

		//Caso haja 8 números/partes da mensagem binária, atualiza a mensagem codificada
		if(count($encodedMessageParts) == 8){
			$char = implode('', $encodedMessageParts);
			$encodedMessageParts = [];
			//Verifica se a mensagem já acabou
			if($char == $messageEnd){
				break 2;
			}else{
				//Atribui a última letra encontrada à mensagem codificada
				$encodedMessage .= $char;
			}
		}
	}
}
//Descodifica a mensagem binária
$decodedMessage = '';
for($i = 0; $i < strlen($encodedMessage); $i+=8){ 
	$char = mb_substr($encodedMessage, $i, 8);
	$decodedMessage .= chr(bindec($char));
}
echo json_encode(array('status' => true, 'message' => $decodedMessage));
exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<meta charset="utf-8">
	<title>Decode Message from Image</title>
</head>
<body class="m-5">
	<form method="GET">
		<div class="form-group">
			<label>Imagem</label><input type="text" class="form-control" name="file">
		</div>
		<input type="submit" class="btn btn-primary mt-2" value="Enviar" />
	</form>

</body>
</html>