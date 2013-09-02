[[documentation|Back (Gearman WiKi)]]
==== This is a dead Project ====

This guide is left here only as a reference.

==== gearman-jms ====
gearman-jms is a JMS Provider written using Gearman-Java as its back-end implementation. This allows applications written in Java using JMS to seamlessly interface with a Gearman Job Server. The result is JMS clients can use JMS to request remote work on their behalf, or receive results from a remote function call. JMS workers can use JMS to broadcast to the Job Server what functions they implement, and to receive work requests (and return work results).

=== JMS ===
Java Message Service (JMS) is an API that provides a standardized communication framework that many Java applications use to communicate with each other. 

  *[[http://java.sun.com/products/jms/docs.html|JMS Specifications]]
  *JMS Design ({{GearmanJMSDesign2.pdf|PDF}}, {{GearmanJMSDesign2.doc|Doc}})

=== Docs ===
  *Interface documentation ({{GearmanJMSInterface.pdf|PDF}}, {{GearmanJMSInterface.doc|Doc}})
  *[[http://gearman.org/docs/jms/|Java Docs]]

== Design ==
  *High Level Design ({{GearmanJMSDesign1.pdf|PDF}}, {{GearmanJMSDesign1.doc|Doc}})
  *Protocol Design ({{GearmanJMSDesign3.pdf|PDF}}, {{GearmanJMSDesign3.doc|Doc}})

== Tests ==
  *[[Fake_JNDI|Running the fake JNDI Shell]]
  *[[Run_Tests|Running the tests]]
  *[[Reverse_example|Reverse example]] 

=== Code ===
The code is hosted on [[https://launchpad.net/|Launchpad]]. A direct link to the project can be found at [[https://code.launchpad.net/gearman-jms|gearman-jms]]. Using this portal, all of the source code can be accessed, as well as any releases (including the most current release). Releases can also be found on the [[download|Downloads Page]].

=== Capstone ===

{{ GearmanJMSPERTChart.png|PERT Chart}}

This began as a project for the PSU Computer Science Capstone (CS487-488) Spring-Summer 2009 Sequence. Project Blue team members include:
  *John Ogle
  *Rogelio Hernandez
  *Adam Elston-Jones
  *Isaiah van der Elst
  *Nick Harsh
  *Scott Merz

Project documents from this course:

  *{{GearmanJMSProjectPlan.doc|Project Plan}}
  *{{GearmanJMSProjectSchedule.xls|Project Schedule}}