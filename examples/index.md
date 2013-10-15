---
layout: default
title: "Examples"
---

# Examples

Here are a number of common scenarios where you'll find gearman is an excellent
fit. Each example strives to have code samples in multiple languages. If your
favorite is missing, please help out with a pull request!

## Foreground Jobs

This basic request pattern will send a job to to gearman and block until
a response (or timeout) occurs.

 * [Basic Reverse String]({{ site.baseurl }}/examples/reverse/)
 * [Geocoding]({{ site.baseurl }}/examples/geocoding/)

## Background Jobs

Background Jobs will send a job to be processed to gearman and immediately
return when gearman has the message.

 * [Sending Emails]({{ site.baseurl }}/examples/send-emails/)
 * [Logging]({{ site.baseurl }}/examples/logging/)

## Tasks

Execute multiple jobs in parallel as Tasks. Great for speeding up lots of small
calls which done in serial take a long time.

 * [Feed Fetching & Parsing]({{ site.baseurl }}/examples/feed-fetch-parse/)
 * [Synchronous Image Resize]({{ site.baseurl }}/examples/image-resize/)

# Gearman In The Wild

Links to write ups about how gearman is being used at companies to get real
work done.

 * [Build & Test Framework at Tokutek](http://tokutek.com/2009/10/using_gearman_for_nightly_build_and_test/)
 * [Using Gearman For Distributed Alerts](http://tech.backtype.com/using-gearman-for-distributed-alerts)
 * [Shard-Query](https://github.com/greenlion/swanhart-tools/tree/master/shard-query) -- A PHP project which executes queries on horizontally partitioned databases, aggregates, and returns the results.
 * [Distribute Nagios Checks/Eventhandler with Gearman](http://labs.consol.de/lang/en/nagios/mod-gearman/)
