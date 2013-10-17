---
layout: examples
title: Sending Emails in the Background
---

Quite often you'll want to email a user something based on an action they've
taken on your site. Signed up for a newletter, requested a download link, and
so on. However, email systems are notorious for being slow and causing page load
headaches. Here we demonstrate how you can use Gearman to "background" the email
send task.

## The Client

<div class="code-tabs">

{% highlight php %}
{% include examples/send-emails/php/client.php %}
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>

What's great about `doBackground()` is that it returns as soon as the gearman
server receives the job -- it does not wait for the job to be processed.
This means that the user's browser isn't spinning while your code does battle
with an SMTP server. In fact, you don't even need to have a worker ready to
process the job when you submit it. Try running the client without a worker
running. When you do finally start the worker, it will pick up any waiting jobs.

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
