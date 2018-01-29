<?php
// $fp = stream_socket_client("", $errno, $errstr, 30);


require('vendor/autoload.php');
// var_dump(is_file('vendor/autoload.php'));

// use WebSocket\Client;

$client = new WebSocket\Client("https://streamer.cryptocompare.com/");
var_dump($client);die();
$client->send("Hello from PHP");

echo $client->receive() . "\n"; // Should output 'Hello from PHP'