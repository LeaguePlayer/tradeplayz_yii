<?php
namespace MyApp;



// use \Ratchet\MessageComponentInterface;
// use \Ratchet\ConnectionInterface;

class Chat implements \Ratchet\MessageComponentInterface {
    public function onOpen(\Ratchet\ConnectionInterface $conn) {
    }

    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
    }

    public function onClose(\Ratchet\ConnectionInterface $conn) {
    }

    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e) {
    }
}