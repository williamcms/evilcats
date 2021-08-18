<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$file = $_FILES['sendimage'];

	$imageName = $file['name'];
	$imageType = $file['type'];
	$imagePath = $file['tmp_name'];

	$target_file = 'temp\\'. $imageName;

	//Tipo de aqruivos permitidos
	$allowed_mime_types = array('image/bmp');
	//Verifica se o arquivo foi selecionado
	if(empty($imagePath)){
		echo $error = json_encode(array('status' => false, 'message' => 'Sorry, You need to select a image before submitting.'));
		exit();
	}
	//Verifica o tipo do arquivo
	if(in_array($imageType, $allowed_mime_types)){
		if(move_uploaded_file($_FILES["sendimage"]["tmp_name"], $target_file)){
			//Exibe o local do arquivo
			echo  json_encode(array('status' => true, 'image' => $target_file));
			exit();
		}
	}else{
		echo json_encode(array('status' => false, 'message' => 'Sorry, only BITMAP format are supported.'));
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<meta charset="utf-8">
	<title>Upload</title>
</head>
<body class="m-5">

<form enctype="multipart/form-data" method="POST">
	<div class="form-group">
		<input name="sendimage" class="form-control" type="file" />
	</div>
    <input type="submit" class="btn btn-primary mt-2" value="Enviar" />
</form>

</body>
</html>