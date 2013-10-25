import gearman

gm_worker = gearman.GearmanWorker(['localhost:4730'])

def task_listener_reverse(gearman_worker, gearman_job):
    print 'Reversing string: ' + gearman_job.data
    return gearman_job.data[::-1]

# gm_worker.set_client_id is optional
gm_worker.set_client_id('python-worker')
gm_worker.register_task('reverse', task_listener_reverse)

# Enter our work loop and call gm_worker.after_poll() after each time we timeout/see socket activity
gm_worker.work()
