var gearmanode = require('gearmanode');
var worker = gearmanode.worker(); // by default on localhost:4730

worker.addFunction('reverse', function (job) {
    job.sendWorkData(job.payload); // mirror input as partial result
    job.workComplete(job.payload.toString().split("").reverse().join(""));
});
