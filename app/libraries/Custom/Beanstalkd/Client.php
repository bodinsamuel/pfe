<?php namespace Custom\Beanstalkd;

class Client
{
    protected $client;

    public function __construct()
    {
        $this->client = new \Pheanstalk_Pheanstalk( '0.0.0.0:11300' );
    }

    public function sendEvents($data)
    {
        return $this->client->useTube('events')->put(json_encode($data));
    }
}
