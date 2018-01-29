<?php
// use Ratchet\Server\IoServer;
// use \MyApp\Chat;

    require dirname(__DIR__) . '/vendor/autoload.php';
    require dirname(__DIR__) . '/bin/Chat.php';
// var_dump(is_file(dirname(__DIR__) . '/vendor/autoload.php'));die();
    // var_dump(new \Ratchet);die();

    var_dump(new \MyApp\Chat);
    die();

    //  \Ratchet\Server\IoServer\connect('wss://streamer.cryptocompare.com')->then(function($conn) {
    //     $conn->on('message', function($msg) use ($conn) {
    //         echo "Received: {$msg}\n";
    //         $conn->close();
    //     });

    //     // $conn->send('Hello World!');
    // }, function ($e) {
    //     echo "Could not connect: {$e->getMessage()}\n";
    // });


    // \Ratchet\Server\IoServer::factory(
    //     null,
    //     8080
    // );

    // $server->run();