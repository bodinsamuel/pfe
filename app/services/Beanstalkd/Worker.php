<?php
// Require composer
require_once __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/start.php';
$app->boot();

$queue =  new Pheanstalk_Pheanstalk("0.0.0.0:11300");

$worker = new Custom\Beanstalkd\Worker();

// Set which queues to bind to
$queue->watch('events');

echo 'Ready to run, waiting for events...';
// pick a job and process it
while($job = $queue->reserve())
{
    $received = json_decode($job->getData(), true);
    $action = $received['action'];

    $data = isset($received['data']) ? $received['data'] : [];

    echo "Received $action (" . current($data) . ") ...";

    if(method_exists($worker, $action))
    {
        // Execute action
        $result = $worker->$action($data);

        // Done or Failed
        if((is_bool($result) && $result === TRUE)
           || (is_int($result) && $result > 0)
           || (is_array($result) && !empty($result['error'])))
        {
            echo " - done \n";
            $queue->delete($job);
        }
        else
        {
            echo " - failed \n";
            print_r($result);
            $queue->bury($job);
        }
    }
    else
    {
        echo " - Action not found\n";
        $queue->bury($job);
    }
}
