<?php

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction("send_email", function(GearmanJob $job) {
    $workload = json_decode($job->workload());
    echo "Sending email: " . print_r($workload,1);
    // You would then, of course, actually call this:
    //mail($workload->email, $workload->subject, $workload->body);
});

while ($worker->work());
