var Gearman, client;

Gearman = require('gearman').Gearman;

client = new Gearman("localhost", 4730);

client.on('WORK_COMPLETE', function (job) {
    console.log('job completed, result:', job.payload.toString());
    return client.close();
});

client.connect(function () {
    return client.submitJob('reverse', 'Hello, World!');
});
