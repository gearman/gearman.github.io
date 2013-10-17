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
{% include examples/reverse/php/client.php %}
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
{% include examples/send-emails/php/worker.php %}
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>

## Running The Client And Worker

Now that we have our client and our worker, startup the gearmand server, start
the worker, then the client. You should see something like the following.

<img src="{{ site.baseurl }}/img/php-example.png" />

### Startup Order

Be mindful of what happens if you start the client before the worker. You'll
find that the client blocks (pauses execution) at the doNormal() call. This
is because you're performaing a "foreground job" in which you wait for the
job to be processed and the results returned. If there are no workers available
the process will block until one shows up. You can check out this behaviour by
starting the client, waiting a few moments, and then starting the worker.
