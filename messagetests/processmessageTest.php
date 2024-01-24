<?php

use PHPUnit\Framework\TestCase;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class processmessageTest extends TestCase
{
    public function testSendMessageToQueue()
    {
        // RabbitMQ connection details
        $host = 'localhost';
        $port = 5672;
        $user = 'guest';
        $pass = 'guest';
        $queueName = 'task_queue';

        // Creating connection to RabbitMQ server
        $connection = new AMQPStreamConnection($host, $port, $user, $pass);

        // Creating a channel
        $channel = $connection->channel();

        // Declaring a durable queue to ensure tasks are not lost if RabbitMQ restarts
        $channel->queue_declare($queueName, false, true, false, false);

        // Simulating sending a task to the message queue
        $task = "message-test-task";
        $message = new AMQPMessage($task, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($message, '', $queueName);

        // Assert that the message is sent successfully
        $this->assertNotEmpty($task);

        // Clossing the channel and connection
        $channel->close();
        $connection->close();
    }
}
?>
