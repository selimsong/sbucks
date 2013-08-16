<?php
include('conf.ini');
$s_dir = './source/';
$mp3_5 = '5.mp3';
$mp3_10= $s_dir.'10.mp3';
$amr_mp3 = $s_dir.'_amr.mp3';
$outputA = 'output.mp3';

shell_exec('cp '.$s_dir.'5.mp3 . ');

$lineA = shell_exec('ffmpeg -y -i '.$amr_mp3.' -i '.$mp3_10.' -filter_complex amerge -c:a libmp3lame -q:a 4 -ar 44100 '.$outputA.'  2>&1 ');


$lineB = shell_exec('ffmpeg -i '.$outputA.'  2>&1 | grep Duration ');
if(preg_match('/Duration: (\d{2}:\d{2}:\d{2}\.\d{2})/', trim($lineB), $matches)){
   $_duration = trim($matches[1]); 
   $duration  = explode(':', $_duration);
   $_count   = $mp3_10time - $duration[2];
   $_tmpMp3 = 'tmp.mp3';
   if($_count > 0){
//      echo $_count;
      $lineC = shell_exec('ffmpeg -ss '.$duration[2].'  -y -i '.$mp3_10.' -acodec copy '.$_tmpMp3.' 2>&1 ');
      
      $lineD = shell_exec('ffmpeg -y -i "concat:'.$mp3_5.'|'.$outputA.'|'.$_tmpMp3.'" -acodec copy  g.mp3    ');
//      var_dump($lineD);
      unlink($outputA); 
      unlink($_tmpMp3);   
   }

}

//var_dump($lineA);
//var_dump($lineB);
