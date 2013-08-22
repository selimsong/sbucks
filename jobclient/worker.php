<?php
set_time_limit(0);

$url = "http://test.call.socialjia.com:8893/index.php";

$worker = new GearmanWorker();
$worker->addServers();
$worker->addFunction("media", "do_it");

$m = new mongoClient('mongodb://127.0.0.1', array());
$db = $m->star;
$collection = $db->users;


while($worker->work());

function do_it($job)
{
  global $url, $db;
	echo $job->workload(); 
        $jobMessage = $job->workload();
        $jobMessage = explode('@@', $jobMessage);
	$messageId = $jobMessage[0];
//	$messageId = '5914774994440022748';  // for test
	$param = array(
			'a'=>'Media',
			'm'=>'getMediaUrl',
			'messageId' => $messageId
	);
	$param = array_merge(getMediaAuth(),$param);

	$result = createCurl($url,$param);
   var_dump($result);
        $media_url = !empty($result['data']['media_url'])? $result['data']['media_url'] : $result['data']['media_local_orig_url'];
	if(!empty($media_url)){ 
            echo 111;
	    $content = file_get_contents($media_url);
	    file_put_contents($messageId.'.amr', $content);
        amr_mp3($messageId);
		$_userMp3 = $messageId.'.mp3';
        $userMp3 = $messageId.'_up.mp3';
		$lineG = shell_exec("ffmpeg -y -i ".$_userMp3."  -af  'volume=4' ".$userMp3.' 2>&1 ');
		$mp3_10 = '10.mp3';
		$mp3_5 = '5.mp3';
		split_mp3('00:00:00', '00:00:05', '00:00:10', 'mp3.mp3', $mp3_5, $mp3_10);
        $outputA = '_'.$messageId.'.mp3';
        $lineA = shell_exec('ffmpeg -y -i '.$userMp3.' -i '.$mp3_10.' -filter_complex amerge -c:a libmp3lame -q:a 4 -ar 44100 '.$outputA.'  2>&1 ');
        $lineB = shell_exec('ffmpeg -i '.$outputA.'  2>&1 | grep Duration ');
        if(preg_match('/Duration: (\d{2}:\d{2}:\d{2}\.\d{2})/', trim($lineB), $matches)){
			   $_duration = trim($matches[1]); 
			   $duration  = explode(':', $_duration);
			   $_count   = 10.06  - $duration[2];
			   $_tmpMp3 = 't'.$messageId.'.mp3';
			   if($_count > 0){
				 $lineC = shell_exec('ffmpeg -ss '.$duration[2].'  -y -i '.$mp3_10.' -acodec copy '.$_tmpMp3.' 2>&1 ');
				  
                                 $lineD = shell_exec('ffmpeg -y -i "concat:'.$mp3_5.'|'.$outputA.'|'.$_tmpMp3.'" -acodec copy  f'.$messageId.'.mp3  2>&1  ');
                                 
                                 $lineD = shell_exec('ffmpeg -y -i mp4.mp4  -i  f'.$messageId.'.mp3    -map 0:0 -map 1:0 -c:v copy -c:a libmp3lame -ar 44100 -aq 0 '.$messageId.'.mp4  2>&1  ');
			  
				 $lineE = shell_exec('ffmpeg -y -i '.$messageId.'.mp4  -acodec  libfaac  f'.$messageId.'.mp4   2>&1 ');

                           }

                $param = null;
		$param = array(
				'type'=>'video',
				'toUsers'=> $jobMessage[1],
				 'a'    => 'Send',
				 'm'    => 'massSend',
				 'mediaUrl' => 'http://112.124.7.130/worker/f'.$messageId.'.mp4',
				 'thumbUrl' => 'http://112.124.7.130/1.jpg'
				);
                $param = array_merge(getSendAuth(),$param);
                $result = createCurl($url,$param); 

                $db->users->update(array('messageid'=> $messageId), array('$set' => array("updatestatus" => "2")));


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


}



function amr_mp3($messageId){
	$lineA = shell_exec('ffmpeg -y -i '.$messageId.'.amr'.' -ar 44100  '.$messageId.'.mp3'.'  2>&1 ');
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

function getSendAuth(){
	
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
	curl_setopt($curl, CURLOPT_TIMEOUT, 300);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
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
