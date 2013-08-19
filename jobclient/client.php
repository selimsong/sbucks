<?php

$client = new GearmanClient();
$client->addServer();
$client->doBackground('media', '1234');

