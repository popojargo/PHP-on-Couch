<?php

namespace PHPOnCouch;


use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Config
{

    private static $instance = null;
    private static $adapterKey = 'HTTP_ADAPTER';
    private $config;

    private function __construct()
    {
        $env = new Dotenv(__DIR__);
        try {
            $env->load();
        } catch (InvalidPathException $e) {
            //The file is not available :(
        }
        $env->required(SELF::$adapterKey)->allowedValues(['curl', 'socket']);

        //Get curl options
        $curlOpts = [];
        foreach ($_SERVER as $key => $val) {
            if (substr($key, 0, 7) == 'CURLOPT')
                $curlOpts[$key] = $val;
        }

        $this->config = [
            SELF::$adapterKey => getenv(SELF::$adapterKey),
            'curl' => $curlOpts
        ];
    }

    public function getAdapter()
    {
        return $this->config[SELF::$adapterKey];
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

