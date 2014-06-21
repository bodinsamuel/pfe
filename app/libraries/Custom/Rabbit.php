<?php namespace Custom;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbit
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function post_elastic_upsert($id_post)
    {
        $this->channel->queue_declare('elastic_upsert', false, true, false, false);

        $properties = ['delivery_mode' => 2];
        $msg = new AMQPMessage($id_post, $properties);

        $this->channel->basic_publish($msg, '', 'elastic_upsert');
    }
}
