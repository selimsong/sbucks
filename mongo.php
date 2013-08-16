<?php
$m = new mongoClient('mongodb://127.0.0.1', array());
$db = $m->star;
$collection = $db->users;
$doc = array('messageId' => rand(0, 100), 'openid' => rand(0, 100), 'updateData' => date('d'));
$collection->insert($doc);


$cursor = $collection->find();

foreach($cursor as $document){

    print_r($document);
	echo "<br />";

}