<script type="text/javascript">
	// var socket=  new WebSocket('ws://localhost:8086');
	// var socket=  new WebSocket('wss://streamer.cryptocompare.com/socket.io/?EIO=3&transport=websocket&sid=DCVJH3T20b-RGYksAGP5');
	// console.log(socket);
</script>

<?php
// $fp = stream_socket_client("", $errno, $errstr, 30);
// wss://streamer.cryptocompare.com

require('vendor/autoload.php');
// var_dump(is_file('vendor/autoload.php'));

// use WebSocket\Client;

 ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// namespace support

// use Ytake\WebSocket\Io;
// instance
$client = new Ytake\WebSocket\Io\Client(new Ytake\WebSocket\Io\Payload, new Ytake\WebSocket\Io\Header, new Ytake\WebSocket\Io\Log);

// var_dump($client);die();
// simple use
$client->client("wss://localhost:8086")->connection()->disconnect();

die();
// namespace support
$client->client("http://localhost:8086")->query(['query' => 1])
    // namespace
    ->of('/active')->connection(function() use($client){
            // event receive
            $client->on('connection', function($data) use($client){
                    // value from socket.io server
                    var_dump($data);
                });
            // event emit
            $client->emit('sender', ['hello']);
            // event receive
            $client->on('message', function($data) use($client){
                    // value from socket.io server
                    var_dump($data);
                    $client->disconnect();
                });
        })->keepAlive();




 // \Ratchet\Client\connect('wss://streamer.cryptocompare.com/socket.io/?EIO=3&transport=websocket&sid=DCVJH3T20b-RGYksAGP5')->then(function($conn) {
 //        $conn->on('message', function($msg) use ($conn) {
 //            echo "Received: {$msg}\n";
 //            $conn->close();
 //        });

 //        $conn->send('Hello World!');
 //    }, function ($e) {
 //        echo "Could not connect: {$e->getMessage()}\n";
 //    });