<?php namespace Custom;

class Singleton
{
    private static $singleton = [];

    public static function getBeanstalkd()
    {
        if (isset(self::$singleton['beanstalkd']))
            return self::$singleton['beanstalkd'];

        $beanstalkd = new Beanstalkd\Client;
        self::$singleton['beanstalkd'] = $beanstalkd;
        return self::$singleton['beanstalkd'];
    }

    public static function getRabbit()
    {
        if (isset(self::$singleton['rabbit']))
            return self::$singleton['rabbit'];

        $rabbit = new Rabbit;
        self::$singleton['rabbit'] = $rabbit;
        return self::$singleton['rabbit'];
    }
}
