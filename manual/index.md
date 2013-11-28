---
layout: default
title: Gearman Manual
---

This manual covers generic Gearman concepts and interfaces used by many of the APIs. Examples given will use the PHP extension that wraps the C library, although it should be easy to translate the examples to the other language APIs. This manual will also focus on features provided by the job server written in C, for documentation on the Perl version of the job server see the documentation provided in the Perl module. This manual is a live document and will be updated as new functionality is added or as interfaces change. Thanks!

**THE MANUAL IS CURRENTLY IN PROGRESS, SOME SECTIONS ARE INCOMPLETE**

1. [Introduction]({{ site.baseurl }}/manual/introduction)
  * [History]({{ site.baseurl }}/manual/introduction/#history)
  * [Implementations]({{ site.baseurl }}/manual/introduction/#implementations)
2. [Clients]({{ site.baseurl }}/manual/clients)
  * [Jobs vs Tasks]({{ site.baseurl }}/manual/clients/#jobs_vs_tasks)
  * [Single Job Interface]({{ site.baseurl }}/manual/clients/#single_job_interface)
  * [Concurrent Task Interface]({{ site.baseurl }}/manual/clients/#concurrent_task_interface)
3. [Workers]({{ site.baseurl }}/manual/workers)
  * [Basic Worker]({{ site.baseurl }}/manual/workers/#basic_worker)
  * [Advanced Worker]({{ site.baseurl }}/manual/workers/#advanced_worker)
4. [Job Server]({{ site.baseurl }}/manual/job_server)
  * [Options]({{ site.baseurl }}/manual/job_server/#options)
  * [Logging]({{ site.baseurl }}/manual/job_server/#logging)
  * [Persistent Queues]({{ site.baseurl }}/manual/job_server/#persistent_queues)
  * [Extended Protocols]({{ site.baseurl }}/manual/job_server/#extended_protocols)
5. [Command Line Tool]({{ site.baseurl }}/manual/command_line)
  * [Client Model]({{ site.baseurl }}/manual/command_line/#client_mode)
  * [Worker Model]({{ site.baseurl }}/manual/command_line/#worker_mode)
6. [Troubleshooting Gearman]({{ site.baseurl }}/manual/troubleshooting)
  * [Using gearadmin]({{ site.baseurl }}/manual/troubleshooting/#using_gearadmin)
  * [cPanel Install]({{ site.baseurl }}/manual/troubleshooting/#cpanel)

