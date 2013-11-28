Clients
=======

Jobs vs. Tasks
==============

A task is any request or communication between the client and the job server.

A task is usually communication //about// a job.

Tasks might be 'please run this job' or 'what is the status of this job'.

A job is something the worker does, continuously waiting on the job server to tell him when to start and with what
arguments.

Clients submit jobs and ask for status about jobs (both of
those things are considered tasks). Workers actually perform the jobs.

(above taken from conversation on mailing list 2009-12-03)

Single Job Interface
====================

Concurrent Task Interface
=========================




