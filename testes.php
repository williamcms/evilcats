<?php

	function api_test($api_url, $method, $data, $upload){
		$connection_c = curl_init();
		if($method == 'POST'){
			curl_setopt($connection_c, CURLOPT_URL, $api_url);                    // URL da API
			if($upload){
				$cf = new CURLFile($data, 'image/bmp');
				curl_setopt($connection_c, CURLOPT_POSTFIELDS, ["sendimage" => $cf]);
			}else{
				$data = json_decode($data, true);
				curl_setopt($connection_c, CURLOPT_POSTFIELDS, ['message' => $data['message'], 'file' => $data['image']]);	
			}
		}else{
			curl_setopt($connection_c, CURLOPT_URL, $api_url . '?file=' . $data);
		}
		curl_setopt($connection_c, CURLOPT_HTTPHEADER, array('Content-Type:multipart/form-data'));
		//curl_setopt($connection_c, CURLOPT_RETURNTRANSFER, 1); 	              // retornar o resultado, não printar
		curl_setopt($connection_c, CURLOPT_TIMEOUT, 20);
		$json_return = curl_exec($connection_c); 				                  // Conecta e obtem os dados
		curl_close($connection_c); 								                  // Encerra a conexão
		return json_decode($json_return); 						                  // decode and return
	}

	function get_image($api_url){
		set_time_limit(0);
		// Local para salvar o arquivo
		$fp = fopen (dirname(__FILE__) . '/download.bmp', 'w+');
		$connection_c = curl_init($api_url);
		curl_setopt($connection_c, CURLOPT_TIMEOUT, 50);
		// Escreve a resposta no arquivo
		curl_setopt($connection_c, CURLOPT_FILE, $fp);
		curl_setopt($connection_c, CURLOPT_FOLLOWLOCATION, true);
		// Obtem a resposta
		curl_exec($connection_c);
		// Encerra a conexão
		curl_close($connection_c);
		fclose($fp);
	}
	
	$paramsPostUpload = 'big_brother_is_watching_u_original.bmp';
	var_dump(api_test("http://127.0.0.1/evilcats/upload.php", "POST", $paramsPostUpload, true));
	
	$paramsWrite = json_encode(array('image' => 'temp\big_brother_is_watching_u_original.bmp', 'message' => 'Simple Text'));
	var_dump(api_test("http://127.0.0.1/evilcats/write-message-on-image.php", "POST", $paramsWrite, false));
	
	$paramsGetImage = 'big_brother_is_watching_u_original_edit.bmp';
	var_dump(get_image("http://127.0.0.1/evilcats/get-image.php?file=". $paramsGetImage));

	$paramsGetMessage = 'temp\big_brother_is_watching_u_original_edit.bmp';
	var_dump(api_test("http://127.0.0.1/evilcats/decode-message-from-image.php", 'GET', $paramsGetMessage, false));
	