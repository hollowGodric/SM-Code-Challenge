<?php

/**
 * Created by PhpStorm.
 * User: Brian
 * Date: 25/08/2015
 * Time: 10:59
 */
class TestCase
{
    protected $expectedCalls;
    public $errors = [];

    public function run()
    {
        echo 'Running ' . static::class . PHP_EOL;

        foreach(get_class_methods(static::class) as $method) {
            if (strpos($method, 'test') !== 0) {
                continue;
            }
            $this->setUp();

            $this->$method();

            foreach ($this->expectedCalls as $className => $calls) {
                $this->assertEquals($calls, $className::$calls);
            }
        }
        echo PHP_EOL;
    }

    public function setUp()
    {
        // nothing to do
    }

    protected function assertEquals($expected, $actual)
    {
        if ($actual == $expected) {
            echo '.';
        } else {
            echo 'E';
            $this->errors[] = 'Failed asserting that ' . var_export($actual, true) . ' matches the expected ' . var_export($expected, true);
        }
    }

    protected function sendJSONRequest($address, $curlOpts)
    {
        $ch = curl_init('127.0.0.1:80' . $address);
        curl_setopt_array($ch, $curlOpts);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);
        if (!$response) {
            return $error;
        }
        return $response;
    }

    protected function sendPUT($address, $jsonName)
    {
        $options = $this->returnTransfer
                 + $this->putRequest
                 + $this->jsonBody
                 + [CURLOPT_POSTFIELDS => json_encode($this->loadJsonFile($jsonName))];
        return $this->sendJSONRequest($address, $options);
    }

    protected function sendGET($address)
    {
        return $this->sendJSONRequest($address, $this->returnTransfer);
    }

    protected function sendDELETE($address)
    {
        return $this->sendJSONRequest($address, $this->returnTransfer + $this->deleteRequest);
    }

    protected function loadJsonFile($name)
    {
        $jsonPath = self::JSON_DIR . DIRECTORY_SEPARATOR . $name . '.json';
        if (!file_exists($jsonPath)) {
            throw new Exception('File ' . $jsonPath . ' not created.');
        }

        return json_decode(file_get_contents($jsonPath));
    }

    protected function resetMock($className)
    {
        $className::$returns = [];
        $className::$calls = [];
        $this->expectedCalls[$className] = [];
    }

    protected function stub($class, $method, $args, $return)
    {
        $this->expectedCalls[$class][] = [$method => $args];
        $class::$returns[$method][] = $return;
    }
}