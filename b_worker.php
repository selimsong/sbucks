<?php

$worker = new GearmanWorker();
$worker->addServers("192.168.19.128,192.168.19.129:4730");
$worker->addFunction("callb", "create_new");

while($worker->work());

function create_new($job)
{
   echo $job->workload();
   exec("touch /var/www/2/".$job->workload()."_b.txt");

}
  
