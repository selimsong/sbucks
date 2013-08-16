<?php
$messageId = '5911541085929597309';
$url = "http://test.call.socialjia.com:8893/index.php";
$param = array(
		'a'=>'Media',
		'm'=>'getMediaUrl',
		'messageId' => $messageId

);
$param = array_merge(getAuthParam(),$param);

$result = createCurl($url,$param);
$mediaUrl = $result['data']['media_local_orig_url'];
echo $mediaUrl;
$content = file_get_contents($mediaUrl);
file_put_contents($messageId.'.amr', $content);


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
	return json_decode($response, true);
       // var_dump($response);
}
