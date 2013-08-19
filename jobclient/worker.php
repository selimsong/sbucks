<?php

$worker = new GearmanWorker();
$worker->addServers();
$worker->addFunction("media", "do_it");

while($worker->work());

function do_it($job)
{
   echo $job->workload();
   exec("touch ".$job->workload().".txt");
}  
