---
layout: default
title: 'Java Gearman Service'
---

# Java Gearman Service

 * Project Page: [https://github.com/gearman/java-service](https://github.com/gearman/java-service)

The Java Gearman Service provides a complete gearman implementation in java. This includes the client, worker, and job server.

## Standalone Server

The Java Gearman Service jar file doubles as a fully featured gearman server.

Usage:

`java [jvm options] -jar java-gearman-service-X.Y.Z.jar [server options]`

### Server Options

The server options will specify the server's system variables and/or tell the server what actions to take. The following is a list of the available options.

| Short Name | Long Name     | Description                                                        |
| ---------- | ------------- | -------------------------------------------------------------------|
| `-p`       | `--port=PORT` | Defines what port number the server will listen on (Default: 4730) |
| `-v`       | `--version`   | Display the version of java gearman service and exit               |
| `-?`       | `--help`      | Print the help menu and exit                                       |

### JVM Options

The following is a list of some applicable jvm options.

| Option Name | Description                                                |
| ----------- | -----------------------------------------------------------|
| `-server`   | Use Server HotSpot VM. Must be first option (HotSpot Only) |

## Gearman

`org.gearman.Gearman` objects define gearman systems and creates gearman
services. These services include `GearmanWorker`s, `GearmanClient`s,
and `GearmanServer`s. All services created by the same `Gearman` object
are said to be in the same system, and all services in the same system
share system wide thread resources.

### Example

The following example shows how to create a gearman system using 8 threads
and how to create the three gearman services:

{% highlight java %}
...

// Create a new gearman system using 8 threads
final Gearman gearman = new Gearman(8);

// Create a GearmanWorker object
final GearmanWorker worker = gearman.createGearmanWorker();

// Create a GearmanClient object
final GearmanClient client = gearman.createGearmanClient();

// Create a GearmanServer object
final GearmanServer server = gearman.createGearmanServer();

...
{% endhighlight %}

## GearmanWorker

`org.gearman.GearmanWorker` objects receive `GearmanJob`s and distributes them
to registered `GearmanFunction`s.

### Adding Job Servers

The purpose of a `GearmanWorker` is to grab jobs from a job servers implementing
the [gearman protocol]({{ site.baseurl }}/protocol/). For this to work, the user
will need to specify what job servers the `GearmanWorker` may grab from.

### Adding GearmanServers

JGS provides an API for creating and managing job servers in the local address
space, the `GearmanServer` API. This makes it possible for services to
communicate directly with a local job server.

The following example adds a running `GearmanServer` to the `GearmanWorker`
allowing for direct communication (without a TCP socket):

### Adding Remote Servers

A `GearmanWorker` can connect to any job server implementing the
[gearman protocol]({{ site.baseurl }}/protocol/) (See the
[download]({{ site.baseurl }}/download/) page for more job server
implementations).  This allows the user to build a very scalable
multilingual distributed system.

The following example adds a remote job server that implements the
[gearman protocol]({{ site.baseurl }}/protocol/):

### Register GearmanFunctions

The user will also need provide a mapping from function names to
`GearmanFunction` objects. This will tell the worker what job queues to grab
from and what `GearmanFunction` to invoke when a job is received.

### GearmanWorker Example

The following example sets up a simple `GearmanWorker`. It shows how to create
a `GearmanWorker`, register a `GearmanFunction`, and add a job server.

{% highlight java %}
import org.gearman.Gearman;
import org.gearman.GearmanFunction;
import org.gearman.GearmanWorker;

/**
 * An example showing how to setup a simple gearman worker
 * @author isaiah.v
 */
public class SingleWorker {

    /** The gearman system */
    final Gearman gearman;

    /**
     * Creates a new SingleWorker. A SingleWorker will setup a worker with only one function and one job server
     * @param host
     *     The job server's host address
     * @param port
     *     The port number the server is listening on.
     * @param functionName
     *     The name of the function to register
     * @param function
     *     The GearmanFunction who will execute 'functionName'
     */
    public SingleWorker(String host, int port, String functionName, GearmanFunction function) throws IOException {

        if (host==null || functionName==null || function==null)
            throw new IllegalArgumentException("parameter is null");

        // Create a new gearman system
        this.gearman = new Gearman();

        // Create a GearmanWorker
        final GearmanWorker worker = gearman.createGearmanWorker();

        // Register function
        worker.addFunction(functionName, function);

        try {

            // Adds a job server, may throw IOException
            worker.addServer(host, port);

        } catch (IOException ioe) {

            // Close gearman system if an I/O exception occurs
            gearman.close();

            // Forward exception to caller
            throw ioe;
        }

    }

    /**
     * Closes the SingleWorker
     */
    public void close() {
        this.gearman.shutdown();
    }

    /**
     * Close if no reference to this object exists
     */
    @Override
    protected void finalize() throws Throwable {
        this.close();
    }
}
{% endhighlight %}

## GearmanClient

`org.gearman.GearmanClient` objects dispatch `GearmanJob`s to the job servers.

## GearmanServer

`org.gearman.GearmanServer`

## GearmanJob

## GearmanFunction