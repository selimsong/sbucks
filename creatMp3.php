<?php
$mp3 = '_2.mp3';
$sTime = '00:00:00'; 
$mTime = '00:00:05';
$eTime = '00:00:10';
$s_dir = './source/';
$mp3_5 = $s_dir.'5.mp3'; 
$mp3_10= $s_dir.'10.mp3';

$amr     = '1.amr';
$amr_mp3 = $s_dir.'amr.mp3';
split_mp3($sTime, $mTime, $eTime, $mp3, $mp3_5, $mp3_10);

amr_mp3($amr, $amr_mp3);


function amr_mp3($amr, $amr_mp3){


//$lineA = shell_exec('ffmpeg -y -i '.$amr.' -ar 22050 '.$amr_mp3.'  2>&1 ');
$lineA = shell_exec('ffmpeg -y -i '.$amr.' -ar 44100  '.$amr_mp3.'  2>&1 ');
var_dump($lineA);
}


function split_mp3($sTime, $mTime, $eTime, $mp3, $mp3_5, $mp3_10){

$lineA = shell_exec('ffmpeg -ss '.$sTime.' -t '.$mTime.' -y -i '.$mp3.' -acodec copy '.$mp3_5.'  2>&1 ');
//$lineB = shell_exec('ffmpeg -ss '.$mTime.' -t '.$eTime.' -y -i '.$mp3.' -acodec copy '.$mp3_10.' 2>&1 ');

$lineB = shell_exec('ffmpeg -ss '.$mTime.'  -y -i '.$mp3.' -acodec copy '.$mp3_10.' 2>&1 ');

var_dump($lineA);
var_dump($lineA);

}
