<?php
$messageId = 5911064593667844337;
$url = "http://test.call.socialjia.com:8893/index.php";
$param = array(
		'type'=>'video',
		'toUsers'=>'oxIuPjngH8XgzmdG5cXXUo3AAiBU',
		 'a'    => 'Send',
		 'm'    => 'Send',
         'mediaUrl' => 'http://112.124.7.130/2.mp4',
		 'thumbUrl' => 'http://112.124.7.130/1.jpg'
);
$param = array_merge(getAuthParam(),$param);

$result = createCurl($url,$param);
var_dump($result);

function getAuthParam (){
	
	$apiKey = "2a75f58df84075f770ea9f767a7018c4";
	$apiSecret = '3fd39f82b66289228f15eed97e10be36';
	$timestamp = time();
	return array(
			'apiKey' => $apiKey,
			'timestamp' => $timestamp,
			'sig' => md5($apiKey.$apiSecret.$timestamp),
	);
}


function  send(){




}


function createCurl ($url,$param)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl, CURLOPT_POST, 1);
	$body = http_build_query($param);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$httpInfo = curl_getinfo($curl);
	curl_close($curl);
	var_dump($response);
	return json_decode($response, true);
}
