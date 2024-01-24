<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Connection
$host = 'localhost';
$port = 5672;
$user = 'guest';
$pass = 'guest';
$queueName = 'task_queue';

// connection to RabbitMQ server
$connection = new AMQPStreamConnection($host, $port, $user, $pass);

// Creating a channel
$channel = $connection->channel();

// Declaring a durable queue to ensure  no loss of tasks if RabbitMQ restarts
$channel->queue_declare($queueName, false, true, false, false);

echo " [*] Waiting for messages. To exit, press Ctrl+C\n";

// Define the callback function to process messages
$callback = function (AMQPMessage $message) {
    $task = $message->getBody();

    echo " [x] Received '$task'\n";

    // Simulates task processing
    sleep(5);

    echo " [x] Done\n";
    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
};

// the consumer with the callback function
$channel->basic_consume($queueName, '', false, false, false, false, $callback);

// Waiting for incoming messages
while (count($channel->callbacks)) {
    $channel->wait();
}

// Closing the channel and connection
$channel->close();
$connection->close();
?>
