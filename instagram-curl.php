<?php

	function generate_sig($endpoint, $params, $secret) {
	  $sig = $endpoint;
	  ksort($params);
	  foreach ($params as $key => $val) {
	    $sig .= "|$key=$val";
	  }
	  return hash_hmac('sha256', $sig, $secret, false);
	}

	$hashtag = $_GET['hashtag'];

	$endpoint = '/tags/'.$hashtag.'/media/recent';

	$params = array(
	  'access_token' => '<your-access-token>',
		'count' => 100,
	);
	$secret = '<your-secret-key>';
	$sig = generate_sig($endpoint, $params, $secret);

	$query = array(
	  	'access_token' => '<your-access-token>',
		'count' => 100,
		'sig' => $sig,
	);

	$urlGet = 'https://api.instagram.com/v1'.$endpoint.'?'.http_build_query($query);

	$curl_connection = curl_init($urlGet);
	curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
	
	//Data are stored in $data
	$data = curl_exec($curl_connection);
	curl_close($curl_connection);
	$show = new stdClass;
	if (count(json_decode($data))) {
		$datas = json_decode($data);
		$show->hashtag = $hashtag;
		$show->data = $datas->data;
		echo json_encode($show); 	
	}

?>
