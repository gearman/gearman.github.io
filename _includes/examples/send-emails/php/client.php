<?php

// ... handle form validation, etc
$email = 'joe@hamburger.com';
$subject = "Eat @ Joe's!";
$body = 'They have the best ceaser salad!';
// ... now the good stuff

$client = new GearmanClient();
$client->addServer();
$result = $client->doBackground("send_email", json_encode(array(
   // whatever details you gathered from the form
  'email' => $email,
  'subject' => $subject,
  'body' => $body
)));

// continue page request...
