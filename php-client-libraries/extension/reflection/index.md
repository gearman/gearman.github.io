---
layout: default
title: PHP Extension Reflection
---

Output generated with:

{% highlight php %}
<?php
$e = new ReflectionExtension('gearman');
print "<?php\n\n// Gearman Version: " . $e->getVersion() . "\n\n";
foreach ($e->getClasses() as $c) {
  print 'class ' . $c->name . " {\n";
  foreach ($c->getMethods() as $m) {
    print '  ';
    if ($m->isPublic()) {
        print 'public';
    } elseif ($m->isProtected()) {
        print 'protected';
    } elseif ($m->isPrivate()) {
        print 'private';
    }
    print ' function ' . $m->name . '(';
    $sep = '';
    foreach ($m->getParameters() as $p) {
      print $sep;
      $sep = ', ';
      if ($p->isOptional())
        print '$' . $p->name . ' = null' ;
      else
        print '$' . $p->name;
    }
    print "){}\n";
  }
  print "}\n\n";
}
{% endhighlight %}

Generated:

{% highlight php %}
<?php

// Gearman Version: 1.1.2

class GearmanClient {
  public function __construct(){}
  public function returnCode(){}
  public function clone(){}
  public function error(){}
  public function getErrno(){}
  public function options(){}
  public function setOptions($option){}
  public function addOptions($option){}
  public function removeOptions($option){}
  public function timeout(){}
  public function setTimeout($timeout){}
  public function context(){}
  public function setContext($context){}
  public function addServer($host, $port){}
  public function addServers($servers){}
  public function wait(){}
  public function doNormal($function_name, $workload, $unique = null){}
  public function doHigh($function_name, $workload, $unique = null){}
  public function doLow($function_name, $workload, $unique = null){}
  public function doJobHandle(){}
  public function doStatus(){}
  public function doBackground($function_name, $workload, $unique = null){}
  public function doHighBackground($function_name, $workload, $unique = null){}
  public function doLowBackground($function_name, $workload, $unique = null){}
  public function jobStatus($job_handle){}
  public function jobStatusByUniqueKey($unique_key){}
  public function echo($workload){}
  public function ping($workload){}
  public function addTask($function_name, $workload, $context = null, $unique = null){}
  public function addTaskHigh($function_name, $workload, $context = null, $unique = null){}
  public function addTaskLow($function_name, $workload, $context = null, $unique = null){}
  public function addTaskBackground($function_name, $workload, $context = null, $unique = null){}
  public function addTaskHighBackground($function_name, $workload, $context = null, $unique = null){}
  public function addTaskLowBackground($function_name, $workload, $context = null, $unique = null){}
  public function addTaskStatus($job_handle, $context = null){}
  public function setWorkloadCallback($callback){}
  public function setCreatedCallback($callback){}
  public function setDataCallback($callback){}
  public function setWarningCallback($client_object, $callback = null){}
  public function setStatusCallback($callback){}
  public function setCompleteCallback($callback){}
  public function setExceptionCallback($callback){}
  public function setFailCallback($callback){}
  public function clearCallbacks(){}
  public function runTasks(){}
}

class GearmanTask {
  public function returnCode(){}
  public function functionName(){}
  public function unique(){}
  public function jobHandle(){}
  public function isKnown(){}
  public function isRunning(){}
  public function taskNumerator(){}
  public function taskDenominator(){}
  public function sendWorkload($data){}
  public function data(){}
  public function dataSize(){}
  public function recvData($data_len){}
}

class GearmanWorker {
  public function __construct(){}
  public function returnCode(){}
  public function clone(){}
  public function error(){}
  public function getErrno(){}
  public function options(){}
  public function setOptions($option){}
  public function addOptions($option){}
  public function removeOptions($option){}
  public function timeout(){}
  public function setTimeout($timeout){}
  public function setId($id){}
  public function addServer($host = null, $port = null){}
  public function addServers($servers){}
  public function wait(){}
  public function register($function_name, $timeout = null){}
  public function unregister($function_name){}
  public function unregisterAll(){}
  public function grabJob(){}
  public function addFunction($function_name, $function, $data = null, $timeout = null){}
  public function work(){}
  public function echo($workload){}
}

class GearmanJob {
  public function returnCode(){}
  public function setReturn($gearman_return_t){}
  public function sendData($data){}
  public function sendWarning($warning){}
  public function sendStatus($numerator, $denominator){}
  public function sendComplete($result){}
  public function sendException($exception){}
  public function sendFail(){}
  public function handle(){}
  public function functionName(){}
  public function unique(){}
  public function workload(){}
  public function workloadSize(){}
}

class GearmanException {
  private function __clone(){}
  public function __construct($message = null, $code = null, $previous = null){}
  public function getMessage(){}
  public function getCode(){}
  public function getFile(){}
  public function getLine(){}
  public function getTrace(){}
  public function getPrevious(){}
  public function getTraceAsString(){}
  public function __toString(){}
}
{% endhighlight %}
