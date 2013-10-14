---
layout: default
title: PHP Extension Reference
---

# PECL PHP Extension

The [PECL/Gearman extension](http://pecl.php.net/package/gearman) uses
libgearman library to provide API for communicating with gearmand, and
writing clients and workers.

## FAQ

Is it possible to set the user on who's behalf a worker will do work?
: Yes, simply start workers using the desired user. This can be done either by
  using setuidgid from daemontools (''setuidgid pavel ./worker.php''), using
  [jk_chrootlaunch](http://olivier.sessink.nl/jailkit/jk_chrootlaunch.8.html)
  from jailkit (`jk_chrootlaunch -j /jail -u pavel -x /scripts/worker.php`) or
  by using a wrapper written in php - posix extension, setuid() setgit()
  functions.

How does the job server handle accepted jobs when no function are registered yet?
: The job server queues up the jobs just wait for workers to register (nice for
  race conditions when starting up). If gearman is compiled with a persistent
  queue module (Drizzle, MySQL, PostgreSQL, SQLite, memcached), the queue will
  survive restart of the job server.

Does Gearman provide some authentication mechanisms?
: Not yet, currently you can only limit access by IPtable rules and by telling
  gearman to listen only on a specific IP by using the -L option
  (`gearmand -vv -L 10.0.1.1`). SASL/TLS secure authentication is planned
  (see [Gearman Blueprints](https://blueprints.launchpad.net/gearmand)).

Does Gearman understand prioritization, background/foreground jobs, parallel execution?
: Yes.

What happens if I set a timeout on a worker?
: This allows workers to say "don't let me run a job for function X for more
  than X seconds". If a worker does take longer than the timeout, gearmand will
  restart the job somewhere else.

Can gearman be used as a `cron`/`at` replacement?
: Not yet (see this [thread](http://groups.google.com/group/gearman/browse_thread/thread/b9891649fb08d16b))

Is there a way to gracefully stop a worker instead (such as for update of worker code) of killing it?
: Yes and no. See this [thread](http://groups.google.com/group/gearman/browse_thread/thread/493e88930efffe46).

How do I know that the workers succeed/fail doing their work?
: A plugin will be added ([pluggable result cache](https://blueprints.launchpad.net/gearmand)).

Where can I get more information on planned Gearman development?
: See the [blueprints](https://blueprints.launchpad.net/gearmand) or search
  the [Gearman mailing list](http://groups.google.com/group/gearman).

## Examples

 * [Basic Example]({{ site.baseurl }}/examples/basic)
 * [Synchronous Image Resize]({{ site.baseurl }}/examples/synchronous-resize)
 * [Examples distributed with pecl/gearman](http://svn.php.net/viewvc/pecl/gearman/trunk/examples/)

## Function reference

Please consult the [extension reference at PHP.net](http://docs.php.net/manual/en/book.gearman.php)
for the latest function reference.