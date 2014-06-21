<?php
// Require composer
require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
$app = require_once __DIR__ . '/../../../bootstrap/start.php';
$app->boot();

// Connect to RabbitMQ
$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('elastic_upsert', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

// The processor
$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";

    // *************************** PROCESSING *************
    $elastic = new Custom\Elastic\Post();

    $post = Custom\Post::select([(int)$msg->body], ['galleries' => FALSE, 'markers' => FALSE]);
    $inserting = $elastic->insert($post['posts']);
    // *************************** END ********************
    if (!empty($inserting['error']))
    {
        Log::error('[Elastic] failed upserting => ' . $msg->body);
        Log::error($inserting['error']);
    }
    echo " [x] Done ", "\n";

    // send acknowledge to Rabbit
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

// Tell rabbit to dispatch event on worker charge and not evenly
$channel->basic_qos(null, 1, null);

// Launch consume
$channel->basic_consume('elastic_upsert', '', false, false, false, false, $callback);

// Wait for message
while(count($channel->callbacks))
{
    $channel->wait();
}

// Kill all
$channel->close();
$connection->close();
