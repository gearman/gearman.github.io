---
layout: default
title: Download
---

# Overview

There are a few options when it comes to choosing a job server and API. Gearman
has benefited from many active open source contributors who have each introduced
a new project or helped out with an existing one. Some projects are more active
than others, each have varying features and performance, but they are all
compatible with one another (except for some new protocol additions). If you
need help figuring out which option would best suit your needs,
[get in touch!]({{ site.baseurl }}/communication/)

# Job Server (gearmand)

## gearmand (C)

A job server written in C/C++ under the BSD license that contains all of the
latest protocol additions. Development for this is very active, with threading,
persistent queues, and pluggable protocols now supported. Replication and better
statistics reporting are coming soon. This installs an executable called
`gearmand` (usually in /usr/local/sbin). The C library, libgearman, is bundled
in the same package.

 * [1.1.13 and later releases are on GitHub](https://github.com/gearman/gearmand/releases)
 * [pre 1.1.13 releases are on Launchpad](https://launchpad.net/gearmand)

## java-gearman-service (Java)

A java implementation that is both the gearman library and a standalone
job server.

 * Find java-gearman-service at [Google Code](http://code.google.com/p/java-gearman-service/)
 
## abraxas (Node.js)

A compatible Node.js implementation of gearman client library and job server, 
with full end-to-end streaming support.

* [abraxas on NPM](https://www.npmjs.com/package/abraxas)

## Gearman::Server (Perl)

The original Perl job server that can be found on CPAN as Gearman::Server.
This installs an executable called 'gearmand' (usually in /usr/local/bin).

 * [Perl modules on CPAN](http://search.cpan.org/dist/Gearman-Server/)

# Client & Worker APIs

## libgearman (C)

The C client & worker APIs are under the BSD license and can be found in the same
package as the C server. This library is closely tied with the gearmand (C) job
server, sharing low-level connection and protocol code.

 * [Gearman server documentation]({{ site.baseurl }}/gearmand/)
 * [libgearman library documentation]({{ site.baseurl }}/gearmand/libgearman/)
 * [(Source)](https://github.com/gearman/gearmand)
 * [(Download)](https://github.com/gearman/gearmand/releases)

## Shell

This is included as part of the gearmand and libgearman package above. This tool
allows you to run Gearman clients and workers from the command line or in shell
scripts. This is installed as an executable called `gearman` (usually in /usr/local/bin).

## Perl

There are three Perl client implementations, two of which are Pure Perl and one
which is a wrapper around the libgearman C library.

### Gearman::Client & ::Worker

This pure Perl API can be found in CPAN under Gearman::Client
and Gearman::Worker. It supports SSL and is quite robust and
well maintained.

 *  [Perl modules on CPAN](https://metacpan.org/dist/Gearman)

### Gearman::XS

A Perl module that wraps the libgearman C library. On CPAN under
[Gearman::XS](https://metacpan.org/dist/Gearman-XS). Has not been
maintained in recent years.

 * [Gearman::XS (0.12)](http://launchpad.net/gearmanxs/trunk/0.12/+download/Gearman-XS-0.12.tar.gz)

### AnyEvent::Gearman::Client & ::Worker

This pure Perl API can be found in CPAN under [AnyEvent::Gearman::Client](http://search.cpan.org/dist/AnyEvent-Gearman-Client)
and [AnyEvent::Gearman::Worker](http://search.cpan.org/dist/AnyEvent-Gearman-Worker).

A simpler API can be found under [AnyEvent::Gearman](http://search.cpan.org/dist/AnyEvent-Gearman).

This module uses the [AnyEvent](http://search.cpan.org/dist/AnyEvent) framework
and provides a event-driven asynchronous client and worker.

## Nodejs

[Gearman Nodejs Extension (0.0.1)](https://github.com/mreinstein/node-gearman)  
[GearmaNode Node.js library with support for multiple servers](https://github.com/veny/GearmaNode)

## PHP

There are two PHP client/worker libraries, one which is a pure PHP extension and
one which wraps the libgearman C library.

### Gearman Extension

A PHP extension that wraps the libgearman C library.

 * [Gearman PHP Extension (1.0.2)](http://pecl.php.net/get/gearman-1.0.2.tgz) [(Source)](http://pecl.php.net/package/gearman)
 * [PHP Extension Reflection]({{ site.baseurl }}/php-client-libraries/extension/reflection/)
 * [PHP Extension Reference]({{ site.baseurl }}/php-client-libraries/extension/reference/)

### Net_Gearman

A pure PHP API that can be found as Net_Gearman on PEAR.

 * Net_Gearman at [PEAR](http://pear.php.net/package/Net_Gearman/) or [GitHub](https://github.com/brianlmoon/net_gearman)

### GearmanBundle

 * [GearmanBundle](https://github.com/mmoreram/GearmanBundle)
   (for [Symfony2](http://symfony.com))

## Python

There are three different Python APIs, one that is a wrapper of the libgearman C
library and two that are pure Python. There is also a package for Django.

### Pure Python

Two pure-Python API's can be found on PyPI, "gearman" and "gear". They can be installed with "pip install gearman".

 * [gearman](https://pypi.python.org/pypi/gearman) - Basic client library
 * [gear](https://pypi.python.org/pypi/gear) - Async client library and basic server in python

### Python C Library Wrapper

A python library that wraps the C interface library.

 * [python-gearman](http://pypi.python.org/pypi/gearman/) ([Source](http://github.com/Yelp/python-gearman))

### Django

 * [Django Libraries](https://pypi.python.org/pypi?%3Aaction=search&term=django_gearman_commands&submit=search)

### Twisted

 * [Twisted Libraries](https://pypi.python.org/pypi/twisted-gears/0.2)

## Java

### Java Gearman Service

"Java gearman service" is a asynchronous interface written entirely in java.
This includes client, worker, and server implementations. java-gearman-service
can be downloaded at Google Code

 * [Java Gearman Service](https://github.com/gearman/java-service)
   ([Docs]({{ site.baseurl }}/java-gearman-service))

### Gearman Java

A pure Java driver exists on Launchpad.

 * [Gearman Java](http://launchpad.net/gearman-java)

## C# / .NET

### GearmanSharp

GearmanSharp is a C# / .NET API for Gearman, developed for .NET 3.5. Not a
complete implementation of the protocol yet. Contributions are welcome!

[GearmanSharp on GitHub](https://github.com/twingly/GearmanSharp)

### gearman.net

A C# / .NET library developed using Mono 2.x. Compiles and runs using the
Microsoft C# compiler suite as well, but all active development is done
using Mono.

Available on Launchpad as [gearman.net](https://launchpad.net/gearman.net).
Only [source](https://code.launchpad.net/gearman.net) is available at the
moment, no releases just yet.

## Ruby

### Gearman-Ruby

Official ruby library for the Gearman distributed job system.

 * [Gearman-Ruby](https://github.com/gearman-ruby/gearman-ruby)

## Go

### Gearman-Go

Go library for the Gearman distributed job system.

 * [Gearman-Go](https://github.com/mikespook/gearman-go)

### G2

Go gearman server, client, and worker libraries.

 * [G2](https://github.com/appscode/g2)

## Lisp

### Common Lisp

 * [Common Lisp](https://github.com/taksatou/cl-gearman)

## Databases

Gearman calls can be made from within several databases through UDFs providing
the power of job distribution from within SQL.

### MySQL

A set of MySQL UDFs built on the libgearman C library. This exposes the client
API functions to SQL queries, triggers, and stored procedures.

 * [Gearman MySQL UDFs (0.5)](http://launchpad.net/gearman-mysql-udf/trunk/0.5/+download/gearman-mysql-udf-0.5.tar.gz)
   ([Source](https://launchpad.net/gearman-mysql-udf))

### PostgreSQL

A set of PostgreSQL UDFs built on the libgearman C library. This exposes the
client API functions to SQL queries, triggers, and stored procedures.

 * [Gearman PostgreSQL UDFs (0.2)](http://launchpad.net/pggearman/trunk/0.2/+download/pggearman-0.2.tar.gz)
   ([Source](https://launchpad.net/pggearman))

### Drizzle

The Gearman UDFs for Drizzle are included in the main [Drizzle](http://drizzle.org)
tree and tarballs. See ./configure options for enabling them.

# Tools

## Gearman-Monitor (PHP)

Server monitoring tool developed in PHP, to watch server statistics.

 * [Gearman-Monitor on GitHub](https://github.com/yugene/Gearman-Monitor)

## GearmanManager (PHP)

In addition, there is a framework that can use either of the two extensions
above for managing a group of PHP based Gearman workers.

 * [GearmanManager on GitHub](https://github.com/brianlmoon/GearmanManager)

## Multi-process Gearman Task Server Library (Python)

GMTasks contains a simple multiprocessing server for Gearman workers, designed
for ease of configuration and maximum availability.

 * [GMTasks](https://github.com/ex-nerd/gmtasks)

*[UDF]: User Defined Function
*[UDFs]: User Defined Functions
