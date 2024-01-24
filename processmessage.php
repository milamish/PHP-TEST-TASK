<?php

/**
Write a PHP script that performs asynchronous processing using a message queue system
like RabbitMQ or Redis. The script should receive a task (e.g., an email sending request) and
process it in the background without blocking the main application. Demonstrate how you
would set up the message queue and create a worker script to handle the tasks.
*/

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

// Getting tasks from the command line arguments or any other source
$task = implode(' ', array_slice($argv, 1));
if (empty($task)) {
    die("Please provide a task.\n");
}

// Creating a message
$message = new AMQPMessage($task, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

// Publishing the message to the queue
$channel->basic_publish($message, '', $queueName);

echo " [x] Sent '$task'\n";

// Closing the channel and connection
$channel->close();
$connection->close();
?>
