<?php
/**
 * Created by PhpStorm.
 * User: Alexis
 * Date: 2017-08-01
 * Time: 14:05
 */

namespace PHPOnCouch;


class EnvUtil
{
    private static $instance;

    private function __construct()
    {
        $envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
        $vars = $this->_getEnvVarsFromFile($envFile);
        $this->loadVars($vars);

    }

    public function loadVars(array $vars)
    {
        foreach ($vars as $key => $val) {
            $this->setEnv($key, $val);
        }
    }

    private function _getEnvVarsFromFile($path)
    {
        $result = [];
        if (file_exists($path)) {
            $fh = fopen($path, 'r');
            if ($fh) {
                while (($line = fgets($fh)) != false) {
                    if (substr(trim($line), 0, 1) == '#' || strpos('=', $line) < 0)
                        continue; //This is a comment or not = was found
                    $pair = explode('=', $line);
                    $key = trim($pair[0]);
                    $value = $pair[1];

                    //Boolean parsing.
                    if ($value == "true" || $value == "TRUE")
                        $value = true;
                    else if ($value == "false" || $value == "FALSE")
                        $value = false;

                    $result[$key] = $value;
                }
                fclose($fh);
            }
        }
        return $result;
    }

    /**
     * Taken from https://github.com/vlucas/phpdotenv
     * @param $name
     * @return array|false|null|string
     */
    public function getEnv($name)
    {
        if (array_key_exists($name, $_ENV))
            return $_ENV[$name];
        else if (array_key_exists($name, $_SERVER))
            return $_SERVER[$name];
        else {
            $value = getenv($name);
            return $value == false ? null : $value;
        }
    }

    /**
     * Taken from https://github.com/vlucas/phpdotenv
     * @param $name
     * @param $val
     */
    public function setEnv($name, $val)
    {
        // If PHP is running as an Apache module and an existing
        // Apache environment variable exists, overwrite it
        if (function_exists('apache_getenv') && function_exists('apache_setenv') && apache_getenv($name)) {
            apache_setenv($name, $val);
        }
        if (function_exists('putenv')) {
            putenv("$name=$val");
        }
        $_ENV[$name] = $val;
        $_SERVER[$name] = $val;
    }

    /**
     * Get all the environments variables that has the specified prefix
     * @param $prefix string A prefix
     * @param bool $removePrefix If true, it removes the prefix from the key. False by default.
     * @return array The array containing all the matches.
     */
    public function getEnvWithPrefix($prefix, $removePrefix = false)
    {
        $result = [];
        foreach ($_ENV as $key => $val) {
            if (substr($key, 0, strlen($prefix)) == $prefix) {
                if ($removePrefix)
                    $key = substr($key, strlen($prefix));
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
     * @return EnvUtil The instance of the environment util
     */
    public static function getInstance()
    {
        if (SELF::$instance == null)
            SELF::$instance = new EnvUtil();
        return SELF::$instance;
    }
}