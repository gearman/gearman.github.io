<?php

// Create our client object
$client = new GearmanClient();

// Add a server
$client->addServer(); // by default host/port will be "localhost" & 4730

echo "Sending job\n";

// Send reverse job
$result = $client->doNormal("reverse", "Hello!");
if ($result) {
  echo "Success: $result\n";
}
