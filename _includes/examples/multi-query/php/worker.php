<?php

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction('lookup_user', function(GearmanJob $job){
    // normally you'd so some very safe type checking and query binding to a database here.
    // ...and we're gonna fake that.
    sleep(3);
    return 'The user requested ('. $job->workload() .') is 7 feet tall and awesome';
});

$worker->addFunction('baconate', function(GearmanJob $job){
    sleep(3);
    return 'The user ('. $job->workload() .') is 1 degree away from Kevin Bacon';
});

$worker->addFunction('get_latest_posts_by', function(GearmanJob $job){
    sleep(3);
    return 'The user ('. $job->workload() .') has no posts, sorry!';
});

while ($worker->work());
