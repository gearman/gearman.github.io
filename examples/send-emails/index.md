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
<?php

// ... handle form validation, etc

$client = new GearmanClient();
$client->addServer();
$result = $client->doBackground("send_email", json_encode(array(
   // whatever details you gathered from the form
  'email' => $email,
  'request' => $request
)));

// continue page request...

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
with an SMTP server.

## The Worker

<div class="code-tabs">

{% highlight php %}
<?php
$worker = new GearmanWorker();
$worker->addServer();
$worker->addFunction("send_email", "send_email");
while ($worker->work()) {
  if ($worker->returnCode() != GEARMAN_SUCCESS) {
    break;
  }
}

// Send a user the request email
function send_email($job) {
  $workload = json_decode($job->workload());
  echo "Received job: " . $job->handle() . "\n";
  // However you used to handle the email send in-line can be called here
  sendEmail($workload->email, $workload->request);
}
{% endhighlight %}

{% highlight java %}
// todo
{% endhighlight %}

{% highlight perl %}
// todo
{% endhighlight %}

</div>
