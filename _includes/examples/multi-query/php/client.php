<?php

$client = new GearmanClient();
$client->addServer();

// initialize the results of our 3 "query results" here
$userInfo = $friends = $posts = null;

// This sets up what gearman will callback to as tasks are returned to us.
// The $context helps us know which function is being returned so we can 
// handle it correctly.
$client->setCompleteCallback(function(GearmanTask $task, $context) use (&$userInfo, &$friends, &$posts) {
    switch($context) {
        case 'lookup_user':
            $userInfo = $task->data();
            break;
        case 'baconate':
            $friends = $task->data();
            break;
        case 'get_latest_posts_by':
            $posts = $task->data();
            break;
    }
});

// Here we queue up multiple tasks to be execute in *as much* parallelism as gearmand can give us
$client->addTask('lookup_user', 'joe@joe.com', 'lookup_user');
$client->addTask('baconate', 'joe@joe.com', 'baconate');
$client->addTask('get_latest_posts_by', 'joe@joe.com', 'get_latest_posts_by');

echo "Fetching...\n";
$start = microtime(true);
$client->runTasks();
$totaltime = number_format(microtime(true) - $start, 2);

echo "Got user info in: $totaltime seconds:\n";
var_dump($userInfo, $friends, $posts);