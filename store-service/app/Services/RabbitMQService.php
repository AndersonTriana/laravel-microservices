<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    public function publish($message, $queue = 'default_queue', $exchange = 'default_exchange', $routinKey = 'default_key', $type = 'direct')
    {
        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'), env('RABBITMQ_VHOST'));
        $channel = $connection->channel();
        $channel->exchange_declare($exchange, 'direct', false, true, false);
        $channel->queue_declare($queue, false, false, false, false);
        $channel->queue_bind($queue, $exchange, $routinKey);
        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, $exchange, $routinKey);
        echo " [x] Sent a message to exchange: $exchange routinKey: $routinKey / queue: $queue.\n";
        $channel->close();
        $connection->close();
    }
    
    public function consume(callable $callback, string $queue = 'default_queue', string $exchange = 'default_exchange', string $routinKey = 'default_key')
    {
        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'), env('RABBITMQ_VHOST'));
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, false, false, false);
        $channel->queue_bind($queue, $exchange, $routinKey);
        $channel->basic_consume($queue, '', false, true, false, false, $callback);
        echo 'Waiting for new message on test_queue', " \n";
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}