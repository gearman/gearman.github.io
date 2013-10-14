---
layout: examples
title: Basic Reverse Example
---

This is just about the most basic gearman example that can be produced and will
server as a teach you the basics of how the server, client, and worker interact
with one another.

## Before Starting

Make sure you have gearman installed and language bindings available.

## The Involved Parties

Server
: The server, gearmand, will coordinate clients and workers ensuring that
  calls from the clients are delivered to workers and that results from workers
  are sent back to the client.

Client
: A process which has a blob of data to process, a known function which can
  process it, and a desire to get the processed results back. In our case, the
  client wants a string reversed.

Worker
: A process which connected to the server and offers to process function calls.
  In this example, the client can reverse strings.


## The Client

<div class="code-tabs">

{% highlight php %}
<?php
// Create our client object
$client = new GearmanClient();

// Add a server
$client->addServer(); // by default host/port will be "localhost" & 4730

echo "Sending job\n";

// Send reverse job
$result = $client->do("reverse", "Hello!");
if ($result) {
  echo "Success: $result\n";
}
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
// Create our worker object
$worker = new GearmanWorker();

// Add a server (again, same defaults apply as a worker)
$worker->addServer();

// Inform the server that this worker can process "reverse" function calls
$worker->addFunction("reverse", "reverse_fn");

while (1) {
  print "Waiting for job...\n";
  $ret = $worker->work(); // work() will block execution until a job is delivered
  if ($worker->returnCode() != GEARMAN_SUCCESS) {
    break;
  }
}

// A much simple reverse function
function reverse_fn($job) {
  $workload = $job->workload();
  echo "Received job: " . $job->handle() . "\n";
  echo "Workload: $workload\n";
  $result = strrev($workload);
  echo "Result: $result\n";
  return $result;
}
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>

## Running The Client And Worker

Now that we have our client and our worker, startup the server, then start the
worker, then the client. You should see something like the following.

<img src="{{ site.baseurl }}/img/php-example.png" />

### Startup Order

// todo: notes about what happens when you start things out of order

## What's Next?

// TODO: Point to next, more complex example