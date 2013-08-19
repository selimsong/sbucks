<?php
$postdata = 'openid='.rand(0, 100).'&messageId='.rand(0, 100);
$fp = fsockopen('112.124.7.130', 80, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
}else{
fputs($fp, "POST /push.php HTTP/1.1\r\n");
fputs($fp, "Host: 112.124.7.130\r\n");

fputs($fp, "User-Agent: PHP Script\r\n");
fputs($fp, "Content-Type:application/x-www-form-urlencoded\r\n");
fputs($fp, "Content-Length:" . strlen($postdata) . "\r\n");
fputs($fp, "Connection: close\r\n\r\n");
fputs($fp, $postdata);
$buf = null;
while (!feof($fp)) {
	$buf .= fgets($fp,128);
}
echo $buf;
fclose($fp);
}








