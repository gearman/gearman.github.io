---
layout: default
title: 'Getting Started'
---

# Getting Started with Gearman

This document is meant to provide enough information to get a basic Gearman
installation up and running with a simple application. This document assumes a
high-level understanding of the role of the job server, clients, and workers.
If not, please refer to the [home page]({{ site.baseurl }}/) overview. For
more detailed documentation on Gearman components and features, please refer to
the [manual]({{ site.baseurl }}/manual/).

## Job Server

Gearman currently has three job server implementations, but this document will
focus on the Gearman C Job Server since this is where the most active
development is currently. See the [download]({{ site.baseurl }}/download/) page
for other options. There are currently four ways to installed the Gearman C
Job Server.

### Installing

#### RHEL/Fedora

Run `yum install gearmand` (older packages might be called `gearmand-server`)

#### Debain/Ubuntu Package

If you are running Debian/sid, run `apt-get install gearman-job-server`. This
package may be slightly out of date due to the time it takes to propagate to
the repositories.

#### Windows

[Install Gearman on Windows](http://www.phpvs.net/2010/11/30/installing-gearman-and-gearmand-on-windows-with-cygwin/)

#### Compile and install from tarball

This is the best way to get the latest stable features. First, download the
latest tarball from the [download]({{ site.baseurl }}/download/) page or
[Launchpad](https://launchpad.net/gearmand/+download). Once downloaded, run:

{% highlight bash %}
tar xzf gearmand-X.Y.tar.gz
cd gearmand-X.Y
./configure
make
make install
{% endhighlight %}

#### Compile and install from source repository

The [Bazaar](http://bazaar-vcs.org/) version control system is required to
check out the latest stable development source. To download and install, run:

{% highlight bash %}
bzr branch lp:gearmand
cd gearmand
./bootstrap.sh
make install
{% endhighlight %}

### Starting

Once the job server has been installed, it can be started by running:

{% highlight bash %}
$ gearmand -d
{% endhighlight %}

The `-d` option causes the server to detach from the shell and run in the
background. If you would like to run the server in a debugging mode, you can
use one or more `-v` flags:

{% highlight bash %}
$ gearmand -vvv
 INFO Starting up
 INFO Listening on :::4730 (6)
 INFO Creating wakeup pipe
 INFO Creating IO thread wakeup pipe
 INFO Adding event for listening socket (6)
 INFO Adding event for wakeup pipe
 INFO Entering main event loop
{% endhighlight %}

## Client and Worker API

With the job server installed and running, the next step is to choose a client
and worker API. There are a number of options listed on the [download]({{ site.baseurl }}/download/)
page, but this document will focus on the command line utility and the
PHP extension.

### Gearman Command Line Tool

This tool is installed as part of the Gearman C Server and Library package. It
provides both a client and worker interface, with multiple options for each.
Run `gearman -H` for all available options.

#### Worker

The `-W` flag tells the gearman tool to run in worker mode. After all options,
a command can be specified to run for each job. For example:

{% highlight bash %}
$ gearman -w -f wc -- wc -l
{% endhighlight %}

This will start a worker, connect to the job server on `localhost` (the
default), and register the function `wc` (the argument to the `-f option`).
For each job that comes in, the gearman tool will fork a process to run the
`wc -l` command. The gearman tool will write the workload of the job to the
processes standard input, and then read the response from standard output.
The gearman tool will wait and accept jobs indefinitely, so press CTRL-C or
kill the process to stop.

#### Client

Without the `-w` worker flag, the gearman tool will run as a client. By default
it submits a foreground job and waits for the response. You can also start
background jobs by specifying the `-b` flag. The workload for the job can be
given after all options, or piped into the process like other shell utilities.
For example:

{% highlight bash %}
$ gearman -f wc < /etc/passwd
26
{% endhighlight %}

This will submit a foreground job to the Gearman job server running on
`localhost` for the function `wc`. The workload for this job will be the entire
contents of the file `/etc/passwd`. The worker started above will process this
request and send the result `26` back. This is equivalent to running:

{% highlight bash %}
$ wc -l < /etc/passwd
{% endhighlight %}

Through Gearman, the job server, client, and worker can all be running on
separate machines. This command line tool can be very useful for quick
prototyping, writing distributed machine management tools, and pushing
expensive shell script processing out to other machines (like log analysis).

### Gearman PHP Extension

The Gearman PHP extension wraps the C library installed with the Gearman C Job
Server package. This provides a client and worker interface in PHP that looks
much like the C interface. The PHP extension also extends the procedural
interface to provide a native object oriented interface as well. This allows
you to use either programming paradigm with the extension. This document will
use the object oriented interface.

#### Installing

The Gearman PHP extension is hosted on [PECL](http://pecl.php.net/package/gearman)
like most other extensions. Building PHP extensions assumes the PHP development
package is installed (php5-dev on Ubuntu and Debian). The PHP command line
interface package should also be installed for development and workers (php5-cli
on Ubuntu and Debian). The tarball should be downloaded from PECL, and then to
build and install run:

{% highlight bash %}
tar xzf gearman-X.Y.tgz
cd gearman-X.Y
phpize
./configure
make
make install
{% endhighlight %}

The following line will need to be added to all php.ini files, usually located
in /etc/php.

{% highlight bash %}
extension="gearman.so"
{% endhighlight %}

The module should now be usable by all PHP interfaces. To test using the PHP
command line interface, create `gearman_version.php` with the following
contents:

{% highlight php %}
<?php
print gearman_version() . "\n";
?>
{% endhighlight %}

This can then be run on the command line:

{% highlight bash %}
$ php gearman_version.php
0.8
{% endhighlight %}

If any errors are reported, the extension installation was not successful.

#### Worker

The following worker code will take a string as its input and return the reverse
of that string as its output.

{% highlight php %}
<?php
$worker= new GearmanWorker();
$worker->addServer();
$worker->addFunction("reverse", "my_reverse_function");
while ($worker->work());

function my_reverse_function($job)
{
  return strrev($job->workload());
}
?>
{% endhighlight %}

This code creates a worker object, adds the default server (`localhost`),
registers the function `reverse` with the callback function `my_reverse_function`,
and then enters a loop waiting for jobs. Each time a job is received, the
callback function is run, which then simply reverses and returns the string
passed in as the workload. If this has been added to the file `worker.php`,
it can be started with:

{% highlight bash %}
$ php worker.php
{% endhighlight %}

This will wait for jobs until CTRL-C is pressed or it is killed.

#### Client

The PHP client interface is similar to the worker. The following code will send
a string to the job server and print the return value.

{% highlight php %}
<?php
$client= new GearmanClient();
$client->addServer();
print $client->do("reverse", "Hello World!");
?>
{% endhighlight %}

This code creates a client object, adds the default server (`localhost`), and
sends a foreground job to the job server for the function `reverse` with
workload `Hello World!`. The result of that job is printed. If this has been
added to the file `client.php`, it can be run with:

{% highlight bash %}
$ php client.php
!dlroW olleH
{% endhighlight %}

## Image Resize Application

This section will combine the components explained above to demonstrate a simple
Gearman application. One of the original purposes of Gearman was to push
expensive image processing off to another set of machines, this example will do
the same. This example uses the ImageMagick module for PHP (php5-imagick package
on Ubuntu or Debian).

### Resize Worker

The following code implements a worker that takes the entire image blob as the
workload, resizes the image, and returns the resized image blob. This may not be
the most efficient implementation for a resize application since the original
image is most likely being stored in a shared file system. It may make more
sense to pass a pathname or URL to the image to be resized and write the resized
image back to that filesystem rather than pushing the large objects through
Gearman. In order to keep this demonstration simple, the image blobs are being
exchanged as the workload and result.

{% highlight php %}
<?php
$worker= new GearmanWorker();
$worker->addServer();
$worker->addFunction("resize", "my_resize_function");
while ($worker->work());

function my_resize_function($job)
{
  $thumb = new Imagick();
  $thumb->readImageBlob($job->workload());

  if ($thumb->getImageHeight() > 600)
    $thumb->scaleImage(0, 600);
  else if ($thumb->getImageWidth() > 800)
    $thumb->scaleImage(800, 0);

  return $thumb->getImageBlob();
}
?>
{% endhighlight %}

This worker application code looks much like the `reverse` code above, except
the `resize` callback function performs the image resize. Please refer to the
PHP ImageMagick documentation for details on the PHP interface. The key thing
to take away from this example is the importing of the workload into an image
(`readImageBlob`), and exporting of the converted image (`getImageBlob`) and
returning that in the function. As mentioned above, it may be more efficient
to import and export images files and pass around pathnames or URLs in
environments with shared or global filesystems.

### Resize Client

The simplest way to test this worker is by using the command line utility.
Assuming the resize worker code above is running, the following command sends
a full size image as the workload and writes the output of that job to the
thumbnail image. File I/O redirection in the shell for both input and output.

{% highlight bash %}
$ gearman -f resize < full.jpg > thumb.jpg
$ ls -l full.jpg thumb.jpg
-rw-r--r-- 1 eday eday 3220493 2009-06-24 12:14 full.jpg
-rw-r--r-- 1 eday eday  328421 2009-06-24 12:21 thumb.jpg
{% endhighlight %}

## Summary

For further applications and examples, please see the [examples]({{ site.baseurl }}/examples/).
The [mailing list and IRC channel]({{ site.baseurl }}/communication/) can act as
a sounding board for what applications may be suitable for Gearman. New ideas,
use cases, and examples are always welcome!
