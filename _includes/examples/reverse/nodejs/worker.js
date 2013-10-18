var Gearman, worker;

Gearman = require('gearman').Gearman;

worker = new Gearman('127.0.0.1', 4730);

worker.on('JOB_ASSIGN', function (job) {
    var result;
    console.log('"' + job.func_name + '" job assigned to this worker with payload: "' + job.payload + '"');
    result = job.payload.toString().split('').reverse().join('');
    worker.sendWorkComplete(job.handle, result);
    return worker.preSleep();
});

worker.on('NOOP', function () {
    return worker.grabJob();
});

worker.connect(function () {
    worker.addFunction('reverse');
    return worker.preSleep();
});

