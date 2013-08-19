<?php

$api_key = '2a75f58df84075f770ea9f767a7018c4';
$api_secret = '3fd39f82b66289228f15eed97e10be36';
if(!empty($_POST['messageId']) && !empty($_POST['openid'])){
$m = new mongoClient('mongodb://127.0.0.1', array());
$db = $m->star;
$collection = $db->users;
$doc = array('messageid' => $_POST['messageId'], 'openid' => $_POST['openid'], 'updatedate' => date('d'), 'updatestatus' => '1', 'updatetime'=> time());
$collection->insert($doc);
echo 'success';
}


