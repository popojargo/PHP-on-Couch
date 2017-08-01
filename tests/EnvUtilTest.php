<?php
/**
 * Created by PhpStorm.
 * User: Alexis
 * Date: 2017-08-01
 * Time: 15:00
 */

namespace PHPOnCouch;


use PHPUnit_Framework_TestCase;

class EnvUtilTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EnvUtil
     */
    private $env;

    public function setUp()
    {
        $this->env = EnvUtil::getInstance();
    }


    public function construcTest()
    {

    }


    public function testGetSetEnv()
    {
        $key = uniqid("MY_VAR");
        $val = "really";

        $this->env->setEnv($key, $val);
        $this->assertEquals($val, $this->env->getEnv($key));
    }


    public function testLoadVars()
    {

        $vars = [
            'this_test_is_cool' => true
        ];
        reset($vars);
        $key = key($vars);

        if (array_key_exists($key, $_ENV)) {
            unset($_ENV[$key]);
        }

        $this->env->loadVars($vars);

        $this->assertEquals(true, $this->env->getEnv($key));
        $this->assertEquals(true, $_ENV[$key]);
    }


    /**
     * @depends testLoadVars
     */
    public function testGetEnvWithPrefix()
    {
        $pref = 'myprefix_';
        $unprefixed = 'val1';
        $expectedVal = 'phponcouch';
        $vars = [
            $pref . $unprefixed => $expectedVal,
            'no_prefix_this_time' => false
        ];

        $this->env->loadVars($vars);

        //Valid test
        $result = $this->env->getEnvWithPrefix($pref, true);
        $this->assertCount(1, $result);

        $validKey = key($result);
        $this->assertEquals($unprefixed, $validKey);
        $this->assertEquals($expectedVal, $result[$validKey]);

        //No result
        $noResult = $this->env->getEnvWithPrefix('\uf000');
        $this->assertCount(0, $noResult);

        //Don't remove prefix
        $noPrefixResult = $this->env->getEnvWithPrefix($pref);
        $this->assertCount(1, $noPrefixResult);

        $prefixedKey = key($noPrefixResult);
        $this->assertEquals($pref . $unprefixed, $prefixedKey);
        $this->assertEquals($expectedVal, $noPrefixResult[$prefixedKey]);


    }

    public function testGetVarsFromFile()
    {
        $path = join(DIRECTORY_SEPARATOR, [__DIR__, '_config', 'test.env']);


        $fun = new \ReflectionMethod($this->env, '_getEnvVarsFromFile');
        $fun->setAccessible(true);

        //Valid example
        $result = $fun->invoke($this->env, $path);
        $this->assertEquals(['KEY' => 'value'], $result);

        //Invalid path
        $emptyResult = $fun->invoke($this->env, '\\\¼#\\¼#\¼\¼@±£');
        $this->assertEmpty($emptyResult);

    }

}