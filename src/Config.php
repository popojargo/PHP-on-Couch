<?php

namespace PHPOnCouch;


class Config
{

    private static $instance = null;

    private $config;

    private function __construct()
    {
        $env = EnvUtil::getInstance();

        //Adapter validation
        $adapter = $env->getEnv('ADAPTER');
        $allowedAdapters = ['curl', 'socket'];
        if (empty($adapter) || !array_search($adapter, $allowedAdapters))
            $adapter = 'curl';

        $this->config = [
            'ADAPTER' => $adapter,
            'curl' => $env->getEnvWithPrefix('CURL_', true) ?: []
        ];
    }

    public function getAdapter()
    {
        return $this->config['ADAPTER'];
    }


    public function getCurlOpts()
    {
        return $this->config['curl'];
    }


    public static function getInstance()
    {
        if (SELF::$instance == null)
            SELF::$instance = new Config();
        return SELF::$instance;
    }
}

