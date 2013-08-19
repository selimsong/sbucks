<?php

$m = new mongoClient('mongodb://192.168.19.129', array());

$db = $m->xin;

$collection = $db->users;
//$doc = array('title'=> 'selim song', 'id' => 1, 'url'=> 'http://u.com');
//$collection->insert($doc);

$doc = array('title'=> 'sayso'.rand(), 'id' => rand(), 'url'=> 'http://'.rand().'u.com');

$collection->insert($doc);


$cursor  =  $collection->find();

foreach($cursor as $document){

    echo $document["title"];

    echo "<br />";


}
