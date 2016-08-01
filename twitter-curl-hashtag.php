<?php

/*
* using curl
*/
$hashtag ="#great";
$key = 'your-key';
$secret = 'your-secret-key';
$api_endpoint = 'https://api.twitter.com/1.1/search/tweets.json?result_type=mixed&exclude_replies=true&count=100&q='. urlencode($hashtag); //endpoint

// request token
$basic_credentials = base64_encode($key.':'.$secret);
$tk = curl_init('https://api.twitter.com/oauth2/token');
curl_setopt($tk, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$basic_credentials, 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'));
curl_setopt($tk, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($tk, CURLOPT_RETURNTRANSFER, true);
$token = json_decode(curl_exec($tk));
curl_close($tk);

// use token

$header = array(
		''
	);
if (isset($token->token_type) && $token->token_type == 'bearer') {
    $br = curl_init($api_endpoint);
    curl_setopt($br, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token->access_token));
    curl_setopt($br, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($br);
    curl_close($br);
  	$resultData = json_decode($data);

  	
  	foreach ($resultData->statuses as $key => $value) {
  		echo "<pre>";
  		print_r($value);
  		echo "</pre>";
  		echo "<br>";
  	}
}
