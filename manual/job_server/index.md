---
layout: default
title: Job Server
---

Job Server
==========

Options
=======

Main Options:

  -b, --backlog=BACKLOG      Number of backlog connections for listen.
  -d, --daemon               Daemon, detach and run in the background.
  -f, --file-descriptors=FDS Number of file descriptors to allow for the process
                            (total connections will be slightly less). Default
                            is max allowed for user.
  -h, --help                 Print this help menu.
  -j, --job-retries=RETRIES  Number of attempts to run the job before the job
                            server removes it. Thisis helpful to ensure a bad
                            job does not crash all available workers. Default is
                            no limit.
  -l, --log-file=FILE        Log file to write errors and information to. Turning
                            this option on also forces the first verbose level
                            to be enabled.
  -L, --listen=ADDRESS       Address the server should listen on. Default is
                            INADDR_ANY.
  -p, --port=PORT            Port the server should listen on.
  -P, --pid-file=FILE        File to write process ID out to.
  -r, --protocol=PROTOCOL    Load protocol module.
  -q, --queue-type=QUEUE     Persistent queue type to use.
  -t, --threads=THREADS      Number of I/O threads to use. Default=0.
  -u, --user=USER            Switch to given user after startup.
  -v, --verbose              Increase verbosity level by one.
  -V, --version              Display the version of gearmand and exit.

  libdrizzle Options:

     --libdrizzle-host=HOST         Host of server.
     --libdrizzle-port=PORT         Port of server.
     --libdrizzle-uds=UDS           Unix domain socket for server.
     --libdrizzle-user=USER         User name for authentication.
     --libdrizzle-password=PASSWORD Password for authentication.
     --libdrizzle-db=DB             Database to use.
     --libdrizzle-table=TABLE       Table to use.
     --libdrizzle-mysql             Use MySQL protocol.

  libmemcached Options:

     --libmemcached-servers=SERVER_LIST List of Memcached servers to use.

  libsqlite3 Options:

     --libsqlite3-db=DB       Database file to use.
     --libsqlite3-table=TABLE Table to use.

  libpq Options:

     --libpq-conninfo=STRING PostgreSQL connection information string.
     --libpq-table=TABLE     Table to use.

  http Options:

     --http-port=PORT Port to listen on.

Logging
=======

Threading Model
===============

By default, only a single thread is used. All socket I/O is non-blocking and everything within the server is stateful, so you can still hit great concurrency rates if using it with a single thread. The **-t** option to gearmand allows you to specify multiple I/O threads. There are currently three types of threads in the job server:

* Listening and management thread - only one
* I/O thread - can have many
* Processing thread - only one

When no **-t** option is given or **-t 0** is given, all of three thread types happen within a single thread. When **-t 1** is given, there is a thread for listening/management and a thread for I/O and processing. When **-t 2** is given, there is a thread for each type of thread above. For all **-t** option values above 2, more I/O threads are created.

The listening and management thread is mainly responsible for accepting new connections and assigning those connections to an I/O thread (if there are many). It also coordinates startup and shutdown within the server. This thread will have an instance of libevent for managing socket events and signals on an internal pipe. This pipe is used to wakeup the thread or to coordinate shutdown.

The I/O thread is responsible for doing the read and write system calls on the sockets and initial packet parsing. Once the packet has been parsed it it put into an asynchronous queue for the processing thread (each thread has it's own queue so there is very little contention). Each I/O thread has it's own instance of libevent for managing socket events and signals on an internal pipe like the listening thread.

The processing thread should have no system calls within it (except for the occasional brk() for more memory), and manages the various lists and hash tables used for tracking unique keys, job handles, functions, and job queues. All packets that need to be sent back to connections are put into an asynchronous queue for the I/O thread. The I/O thread will pick these up and send them back over the connected socket. All packets flow through the processing thread since it contains the information needed to process the packets. This is due to the complex nature of the various lists and hash tables. If multiple threads were modifying them the locking overhead would most likely cause worse performance than having it in a single thread (and would also complicate the code). In the future more work may be pushed to the I/O threads, and the processing thread can retain minimal functionality to manage those tables and lists. So far this has not been a significant bottleneck, a 16 core Intel machine is able to process upwards of 50k jobs per second.

Persistent Queues
=================

Inside the Gearman job server, all job queues are stored in memory. This means if a server restarts or crashes with pending jobs, they will be lost and are never run by a worker. Persistent queues were added to allow background jobs to be stored in an external durable queue so they may live between server restarts and crashes. The persistent queue is only enabled for background jobs because foreground jobs have an attached client. If a job server goes away, the client can detect this and restart the foreground job somewhere else (or report an error back to the original caller). Background jobs on the other hand have no attached client and are simply expected to be run when submitted.

The persistent queue works by calling a module callback function right before putting a new job in the internal queue for pending jobs to be run. This allows the module to store the job about to be run in some persistent way so that it can later be replayed during a restart. Once it is stored through the module, the job is put onto the active runnable queue, waking up available workers if needed. Once the job has been successfully completed by a worker, another module callback function is called to notify the module the job is done and can be removed. If a job server crashes or is restarted between these two calls for a job, the jobs are reloaded during the next job server start. When the job server starts up, it will call a replay callback function in the module to provide a list of all jobs that were not complete. This is used to populate the internal memory queue of jobs to be run. Once this replay is complete, the job server finishes its initialization and the jobs are now runnable once workers connect (the queue should be in the same state as when it crashed). These jobs are removed from the persistent queue when completed as normal.  NOTE: Deleting jobs from the persistent queue storage will not remove them from the in-memory queue while the server is running.

Persistent queues were a new feature added in version 0.6 of the job server. The queues are implemented using a modular interface so it is easy to add new data stores for the persistent queue. The first queue module was for libdrizzle, which allows the queue to be stored inside of Drizzle or MySQL. The 0.7 release included a module that allows the queue to be stored in memcached. Version 0.9 adds SQLite3 and PostgreSQL. Other modules are in the works as well, including native MySQL and flat file.

A persistent queue module is enabled by passing the -q or --queue-type option to gearmand. Run gearmand --help to see which queue modules are supported on your system. If you are missing options for one you would like to use, you will need to install any dependencies and then recompile the gearmand package.

libmysqlclient
==============

The libmysqlclient queue was added 0.38. It uses the MySQL binary protocol so it will only work with 4.1 MySQL or above that have enabled the binary protocol. It can be started with:

  gearmand --queue-type=mysql --mysql-host=hostname

This will enable the mysql queue and have Gearman attach to the mysql server specified.

libdrizzle
==========

This was the first queue module added and allows the queue to be stored inside of a [Drizzle](http://drizzle.org/) or [MySQL](http://www.mysql.com/) table. By default it will connect as root to a database listening on 127.0.0.1 and create a 'test.queue' table. You can override this by providing options on the command line. If you create your own tables, be sure they have the following columns:

* unique_key VARCHAR(64)
* function_name VARCHAR(255)
* priority INT
* data LONGBLOB
* unique key (unique_key, function_name)


Added in 0.20:

* when_to_run INT


The unique_key and function_name do not have to form the only unique key, however that is the key used by gearmand internally. It is a good idea to have some index on them together for fast job deletion. You can have other columns as well, since all SQL statements identify rows by names and do not depend on order or column count. If you have created your own Drizzle table and wish to use it, you can start gearmand as:

  gearmand -q libdrizzle --libdrizzle-db=some_db --libdrizzle-table=gearman_queue

Or perhaps you have the table in a remote MySQL table:

  gearmand -q libdrizzle --libdrizzle-host=10.0.0.1 --libdrizzle-user=gearman \
                         --libdrizzle-password=secret --libdrizzle-db=some_db \
                         --libdrizzle-table=gearman_queue --libdrizzle-mysql

This command assumes you have a MySQL server running on 10.0.0.1, can login as gearman/secret, and you want your queue stored in some_db.gearman_queue. Note this is the 0.7 syntax, 0.6 had different argument parsing and had a serious bug inside of the libdrizzle queue, so it is recommended to update from 0.6 if using this module.

Example
=======

This example assume you have Drizzle (or you can use MySQL and use the --libdrizzle-mysql flag) with the database 'test' created. We first start the server in debug mode:

  > gearmand -q libdrizzle -vvv
  INFO Initializing libdrizzle module
  INFO libdrizzle module using table 'test.queue'
  ...
  INFO Entering main event loop

Next, connect to the database so you can monitor the queue table:

  > drizzle
  Welcome to the Drizzle client..  Commands end with ; or \g.
  Your Drizzle connection id is 6
  Server version: 2009.06.1014-eday-dev Source distribution

  Type 'help;' or '\h' for help. Type '\c' to clear the buffer.

  drizzle> SELECT * FROM test.queue;
  Empty set (0 sec)

Next, add a background job to the job server using the gearman command line client:

  gearman -f testq -b payload

This creates a background job (-b option) for the function testq (-f testq option) with the payload being the string "payload". The job server should have printed a few messages out about the job, most importantly a few about the libdrizzle persistent queue module:

  DEBUG libdrizzle add: e73502bf-c4de-416e-bf59-4d3e07379575
  DEBUG libdrizzle flush

These are the callback functions being called in the libdrizzle module. We can verify the job is now in the Drizzle database by displaying all rows in the table:

  drizzle> SELECT * FROM test.queue;
  +--------------------------------------+---------------+----------+---------+
  | unique_key                           | function_name | priority | data    |
  +--------------------------------------+---------------+----------+---------+
  | e73502bf-c4de-416e-bf59-4d3e07379575 | testq         |        1 | payload | 
  +--------------------------------------+---------------+----------+---------+
  1 row in set (0 sec)

The job is now sitting in the runnable queue inside the job server, as well as in the database. If something should happen to the job server at this point (say a crash or restart), the server will replay all jobs in this database table (in this case just one).

Now use the gearman command line tool to create a worker than can "run" this job.

  gearman -f testq -w

This will connect to the job server and output the payload to stdout (you can stop the worker with CTRL-C). The debug messages in the gearmand output will show the job being removed from the queue:

  DEBUG libdrizzle done: e73502bf-c4de-416e-bf59-4d3e07379575

We can verify it was properly removed from the database be checking the table in Drizzle:

  drizzle> SELECT * FROM test.queue;
  Empty set (0 sec)

libmemcached
============

This is another persistent queue type added in 0.7, and depends on libmemcached 0.30 or later which added a key dumping function. This allows your queue to be stored inside of a memcached cluster. The only option available for this module is the list of servers it should use for the queue. This should always be the same to ensure consistent hashing between restarts. See the [memcached homepage](http://www.danga.com/memcached/) for more details on how to setup memcached.

libsqlite3
==========

This module will store the queue in the named SQLite database file.  The DB file name must be specified.  The default table name is 'gearman_queue', but that can be overridden by command line flag.

The fields in the table are defined as

* unique_key TEXT PRIMARY KEY
* function_name TEXT
* priority INTEGER
* data BLOB
* when_to_run INTEGER

  CREATE TABLE gearman_queue(unique_key TEXT PRIMARY KEY,function_name TEXT,when_to_run INTEGER,priority INTEGER,data BLOB);

To use SQLite3 as the peristent queue, enable it on the command line:

  `gearmand -vvv -q libsqlite3 --libsqlite3-db=/tmp/xx.sqlite`

See above for the libdrizzle notes on how to see it in action.  It is very similar: Instead of selecting from 'test.queue' you select from 'gearman_queue'.

Postgresql
==========
To get Postgresql working you need to use the <code>-q Postgres</code> command line option.

Below is a command line to get persistent queues working with Postgresql. This command line was run on Ubuntu 12.04 server, Postgresql version 9.1, and Gearman v 0.27.

  gearmand -L 127.0.0.1 --libpq-conninfo 'hostaddr=127.0.0.1 port=5432 dbname=gearman user=postgres' --libpq-table=queue123 --verbose DEBUG -q Postgres</code>

Also note: gearmand will create the table if it does not already exist. In the case above, it will crate a table named **queue123**

Extended Protocols
==================

As of version 0.8, the Gearman job server has had pluggable protocol support. The first protocol supported besides the native Gearman protocol was HTTP. The protocol plugin interface allows you to take over the packet send and recieve functions, allowing you to pack the buffers as required by the protocol. The core read and write functions can (and should) be used by the protocol plugin.

HTTP
====

This protocol plugin allows you to map HTTP requests to Gearman jobs. It only provides client job submission currently, but it may be extended to support other request types in the future. The plugin can handle both GET and POST data, the latter being used to send a workload to the job server. The URL being requested is translated into the function being called. For example, the request:

  POST /reverse HTTP/1.1
  Content-Length: 12
   
  Hello world!

Is translated into a job submission request for the function "reverse" and workload "Hello world!". This will respond with:

  HTTP/1.0 200 OK
  X-Gearman-Job-Handle: H:lap:4
  Content-Length: 12
  Server: Gearman/0.8
   
  !dlrow olleH

The following headers can be passed to change the behavior of the job:

* X-Gearman-Unique: `<unique key>`
* X-Gearman-Background: true
* X-Gearman-Priority: `<high|low>`

For example, to run a low priority background job, the following request can be sent:

  POST /reverse HTTP/1.1
  Content-Length: 12
  X-Gearman-Background: true
  X-Gearman-Priority: low
   
  Hello world!

The response for this request will not have any data associated with it since it was a background job:

  HTTP/1.0 200 OK
  X-Gearman-Job-Handle: H:lap:6
  Content-Length: 0
  Server: Gearman/0.8
