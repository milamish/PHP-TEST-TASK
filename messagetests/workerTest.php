<?php

use PHPUnit\Framework\TestCase;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class WorkerTest extends TestCase
{
    public function testProcessTaskFromQueue()
    {
        $host = 'localhost';
        $port = 5672;
        $user = 'guest';
        $pass = 'guest';
        $queueName = 'task_queue';
        $connection = new AMQPStreamConnection($host, $port, $user, $pass);

        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, true, false, false);

        $task = "testing this task";
        $message = new AMQPMessage($task, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($message, '', $queueName);

        require_once __DIR__ . '/../worker.php';

        $this->expectOutputString("[x] Received '$task'\n[x] Done\n");

        $channel->close();
        $connection->close();
    }
}
?>
