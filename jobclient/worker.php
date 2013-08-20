<?php
$url = "http://test.call.socialjia.com:8893/index.php";

$worker = new GearmanWorker();
$worker->addServers();
$worker->addFunction("media", "do_it");

while($worker->work());

function do_it($job)
{
  global $url;
	echo $job->workload();
	$messageId = $job->workload();
	$messageId = '5911541085929597309';  // for test
	$param = array(
			'a'=>'Media',
			'm'=>'getMediaUrl',
			'messageId' => $messageId
	);
	$param = array_merge(getMediaAuth(),$param);

	$result = createCurl($url,$param);
	if(!empty($result['data']['media_local_orig_url'])){
	    $content = file_get_contents($result['data']['media_local_orig_url']);
	    file_put_contents($messageId.'.amr', $content);
            


	}else{
		$m = new mongoClient('mongodb://127.0.0.1', array());
		$db = $m->star;
		$collection = $db->erlog;
		$doc = array('messageid' => $messageId, 'desc' => 'media url is empty','updatedate' => date('d'), 'updatetime'=> time());
		$collection->insert($doc);
	}

}  





function getMediaAuth(){
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
}
