---
layout: examples
title: Logging
---

Centralize your logging. Scale your application to multiple servers, aggregate
logs from different languages, or just collect all your logs in a centralized
format, you can insert gearman to help as a central reporting point and attach
a worker to parse all the logs to where ever they need to go.

## The Client

<div class="code-tabs">

{% highlight php %}
<?php

class GearmanLogger {

  static $instances = array();
  public function getInstance($server = '127.0.0.1', $port = 4730, $queue = 'log') {
    $hash = $queue.$server.$port;
    if (!array_key_exists($hash, $instances)) {
      $instances[$hash] = new self($queue, $server, $port);
    }
    return $instance;
  }

  private $gmc;
  private $queue;
  public function __construct($queue, $server, $port) {
    $this->gmc = new GearmanClient();
    $this->queue = $queue;
    $this->gmc->addServer($server, $port);
  }

  public function log($message, $level = 'DEBUG') {
    $this->gmc->doBackground($queue, json_encode(array(
      'level'   => $level,
      'message' => $message,
      'ts'      => time(),
      'host'    => gethostname(),
    )));
  }

}

GearmanLogger::getInstance()->log('A debug message');
GearmanLogger::getInstance()->log('A warning', 'WARN');
GearmanLogger::getInstance()->log('A serious problem', 'ERROR');
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>

## The Worker

<div class="code-tabs">

{% highlight php %}
<?php
$worker = new GearmanWorker();
$worker->addServer();
$worker->addFunction("log", "log");
while ($worker->work()) {
  if ($worker->returnCode() != GEARMAN_SUCCESS) {
    break;
  }
}

// Send a user the request email
function log($job) {
  $workload = json_decode($job->workload());
  // Save the logs to the database, write them to a single file, index them, ship them to splunk, whatever
  processLog($workload->level, $workload->message, $workload->ts, $workload->host);
  // You can do more interesting things too, like scan for specific errors
  // and send out warnings, or having rolling counts of errors to alert on, etc
}
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>
