<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
if(isset($_GET['file'])){
	$file_name = $_GET['file'];
	$file_url = 'temp/'. $file_name;

	if(file_exists($file_url)){
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$file_name.'"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_url));
		ob_clean();
		flush();
		readfile($file_url);
		exit();
	}else{
		echo json_encode(array('status' => false, 'message' => 'Your file could not be accessed. It may have been moved, edited or deleted.'));
	}
}else{
	echo json_encode(array('status' => false, 'message' => 'You must provide a file name.'));
}