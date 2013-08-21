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
        amr_mp3($messageId);
		$_userMp3 = $messageId.'.mp3';
        $userMp3 = $messageId.'_up.mp3';
		$lineG = shell_exec("ffmpeg -i ".$_userMp3."  -af  'volume=4' ".$userMp3);
		$mp3_10 = '10.mp3';
		$mp3_5 = '5.mp3';
		split_mp3('00:00:00', '00:00:05', '00:00:10', 'mp3.mp3', $mp3_5, $mp3_10);
        $outputA = '_'.$messageId.'.mp3';
        $lineA = shell_exec('ffmpeg -y -i '.$userMp3.' -i '.$mp3_10.' -filter_complex amerge -c:a libmp3lame -q:a 4 -ar 44100 '.$outputA.'  2>&1 ');
        $lineB = shell_exec('ffmpeg -i '.$outputA.'  2>&1 | grep Duration ');
		   if(preg_match('/Duration: (\d{2}:\d{2}:\d{2}\.\d{2})/', trim($lineB), $matches)){
			   $_duration = trim($matches[1]); 
			   $duration  = explode(':', $_duration);
			   $_count   = $mp3_10time - $duration[2];
			   $_tmpMp3 = '_tmp'.$messageId.'.mp3';
			   if($_count > 0){
				  $lineC = shell_exec('ffmpeg -ss '.$duration[2].'  -y -i '.$mp3_10.' -acodec copy '.$_tmpMp3.' 2>&1 ');
				  $lineD = shell_exec('ffmpeg -y -i "concat:'.$mp3_5.'|'.$outputA.'|'.$_tmpMp3.'" -acodec copy  g.mp3    ');
				  
			   }

			}


	}else{
		$m = new mongoClient('mongodb://127.0.0.1', array());
		$db = $m->star;
		$collection = $db->erlog;
		$doc = array('messageid' => $messageId, 'desc' => 'media url is empty','updatedate' => date('d'), 'updatetime'=> time());
		$collection->insert($doc);
	}
}  


function split_mp3($sTime, $mTime, $eTime, $mp3, $mp3_5, $mp3_10){

$lineA = shell_exec('ffmpeg -ss '.$sTime.' -t '.$mTime.' -y -i '.$mp3.' -acodec copy '.$mp3_5.'  2>&1 ');

$lineB = shell_exec('ffmpeg -ss '.$mTime.'  -y -i '.$mp3.' -acodec copy '.$mp3_10.' 2>&1 ');

var_dump($lineA);
var_dump($lineA);

}



function amr_mp3($messageId){
	$lineA = shell_exec('ffmpeg -y -i '.$messageId.'amr'.' -ar 44100  '.$messageId.'.mp3'.'  2>&1 ');
	var_dump($lineA);
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
