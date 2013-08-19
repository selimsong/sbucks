<?php

$client = new GearmanClient();
$client->addServer();
$arg = $argv[1];
$fn  = "call".$argv[2];
$client->doBackground($fn, $arg);
echo $arg;
