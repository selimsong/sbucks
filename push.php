<?php

$api_key = '2a75f58df84075f770ea9f767a7018c4';
$api_secret = '3fd39f82b66289228f15eed97e10be36';

if(!empty($_POST['messageId'])){

$m = new mongoClient('mongodb://127.0.0.1', array());
$db = $m->star;
$collection = $db->users;
$doc = array('messageId' => $_POST['messageId'], 'openid' => $_POST['openid'], 'updateData' => date('d'));
$collection->insert($doc);
}

/**
$p = json_encode($_POST);
$f = time().'.txt';
file_put_contents($f, $p);
*/



