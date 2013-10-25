---
layout: examples
title: Geocoding
---

_Author:_ [sku_](http://twitter.com/sku_)

In this example we're going to attempt to geocode (estimate lat/long for an
address) for a very large list of addresses.

The basic, non-gearman script just reads from STDIN and prints out
a JSON-encoded result for every line:

<div class="code-tabs">

{% highlight perl %}
use strict;

use JSON;
use Geo::Coder::US;

Geo::Coder::US->set_db("foo.db");

while(<STDIN>){
    my @matches = Geo::Coder::US->geocode($_);
    print encode_json(\@matches) . "\n";
}
{% endhighlight %}

</div>

This works, but it's very slow. A 2GHz 8-core Intel Xeon E5405 would eek out
1700 addresses/second. The problem is that a single thread simply isn't
processing fast enough.

To speed this up we're going to spin up multiple workers, split the file among
them, and pull the results all back togther. By doing this we can process
multiple chunks at a time and speed up the overall process.

<div class="code-tabs">

{% highlight perl %}
use strict;

use JSON;
use Geo::Coder::US;

use Gearman::XS qw(:constants);
use Gearman::XS::Worker;

Geo::Coder::US->set_db("foo.db");

my $worker = new Gearman::XS::Worker;
my $ret = $worker->add_server('localhost');
if ($ret != GEARMAN_SUCCESS){
    printf(STDERR "%s\n", $worker->error());
    exit(1);
}

my $options = '';
$ret = $worker->add_function("geocode", 0, \&geocode, $options);
if ($ret != GEARMAN_SUCCESS){
    printf(STDERR "%s\n", $worker->error());
}

while (1){
    my $ret = $worker->work();
    if ($ret != GEARMAN_SUCCESS){
        printf(STDERR "%s\n", $worker->error());
    }
}

sub geocode {
    my $job = shift;
    my $return = '';
    my @lines = split(/\n/, $job->workload());
    my $len = @lines;

    printf("Job=%s Function_Name=%s Size=%s\n",
            $job->handle(), $job->function_name(), $len);

    foreach(@lines){
    	my @matches = Geo::Coder::US->geocode($_);
    	$return .= encode_json(\@matches) . "\n";
    }

    return $return;
}
{% endhighlight %}

</div>

Startup a bunch of those with `supervisord`, split your address file into
reasonable chunks, and pipe them into `gearman -f geocode`. With chunks of 1000
lines and 32 workers we can get 18,000 addresses/second; over 10 fold
improvement just from one server. Adding in a slightly older server into the
mix boosts that number to 30,000 addresses/second.

It should be noted that you can get nearly as good performance, 15,500
addresses/sec in our case on a single server, just by wrapping the basic script
function as `gearman -w -f geocode ./scriptname`. For quick-n-dirty scripts
this is much quicker to spin up.
