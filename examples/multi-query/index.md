---
layout: examples
title: Multi-Query
---

In this example we know that we need to fetch several result sets from a
database. Traditionally you would make the requests one after the other,
gathering results, and finally outputting a page. We're going to use gearman
to execute these queries in parallel to speed up the entire operation.

## The Client

The client here could be your webapp, but for the purpose of being quick
to demonstrate and try, we'll stick a command line script.

<div class="code-tabs">

{% highlight php %}
{% include examples/multi-query/php/client.php %}
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>

## The Worker

The worker, on the other hand, is still pretty simple: emulate the delay you
might see when doing some real work and return a dummy result.

<div class="code-tabs">

{% highlight php %}
{% include examples/multi-query/php/worker.php %}
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>

# The Payoff

_"Hey, I ran your stupid code and it took 9 seconds! It's not at all faster!"_

{% highlight bash %}
# ./run/client/here
Fetching...
Got user info in: 9.00 seconds:
string(59) "The user requested (joe@joe.com) is 7 feet tall and awesome"
string(56) "The user (joe@joe.com) is 1 degree away from Kevin Bacon"
string(43) "The user (joe@joe.com) has no posts, sorry!"
{% endhighlight %}

Ouch, yeah, There's a comment in the code snippet that states:

> Here we queue up multiple tasks to be execute in *as much*
> parallelism as gearmand can give us

What this means is that gearman will only run tasks in parallel if there are
enough workers to accomplish that. Failing that, it will run them with as much
concurrency as it can muster up from available workers. So, if you spin up
three (or more) workers you'll see this:

{% highlight bash %}
# ./run/client/here
Fetching...
Got user info in: 3.00 seconds:
string(59) "The user requested (joe@joe.com) is 7 feet tall and awesome"
string(56) "The user (joe@joe.com) is 1 degree away from Kevin Bacon"
string(43) "The user (joe@joe.com) has no posts, sorry!"
{% endhighlight %}

And now our code is significantly faster -- in fact, it's only as slow as the
slowest query. Parallelism can dramatically speed up pages where tasks _must_
be done while the user waits for a response but that, done in serial, can take
a long time.
