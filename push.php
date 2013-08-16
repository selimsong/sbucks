<?php

$api_key = '2a75f58df84075f770ea9f767a7018c4';
$api_secret = '3fd39f82b66289228f15eed97e10be36';

$p = json_encode($_POST);
$f = time().'.txt';
file_put_contents($f, $p);




