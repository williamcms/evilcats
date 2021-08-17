<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$file = json_decode(file_get_contents('php://input'), true);

	$imageName = $_FILES['sendimage']['name'];
	$imageType = $_FILES['sendimage']['type'];
	$imagePath = $_FILES['sendimage']['tmp_name'];

	$allowed_mime_types = array('image/gif', 'image/jpg', 'image/jpeg', 'image/png', 'image/bmp');
	if(empty($imagePath)){
		$error = json_encode(array('status' => false, 
			'message' => 'Sorry, You need to select a image before submitting.'));
		echo $error;
		exit();
	}
	if(in_array($imageType, $allowed_mime_types)){
		print $imagePath;
	}else{
		$error = json_encode(array('status' => false, 
			'message' => 'Sorry, only GIF, JPG/JPEG, PNG and BITMAP formats are supported.'));
		echo $error;
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>

<form enctype="multipart/form-data" action="upload.php" method="POST">
    Imagem: <input name="sendimage" type="file" />
    <input type="submit" value="Send File" />
</form>

</body>
</html>