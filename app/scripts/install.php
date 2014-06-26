<?php
// Require composer
require_once __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/start.php';
$app->boot();

class Install
{
    public function __construct() { }
}


class InstallSteps extends InstallStepper
{
    public function elasticPfeIndex()
    {
        $elastic = new Custom\Elastic\Base;
        $elastic->create_index();
    }
    public function elasticPostType()
    {
    }
}

class InstallStepper
{
}


$stepper = new InstallSteps();
$stepper->elasticPfeIndex();
