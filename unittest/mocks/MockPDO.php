<?php

/**
 * Time: 20:33
 */
class MockPDO extends \PDO
{
    use Stub;

    public function __construct($dsn = null, $username = null, $passwd = null, $options = null)
    {

    }

    public function prepare($statement, array $driver_options = array())
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function beginTransaction()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function commit()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function rollBack()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function inTransaction()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function setAttribute($attribute, $value)
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function exec($statement)
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null)
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function lastInsertId($name = null)
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function errorCode()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function errorInfo()
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function getAttribute($attribute)
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        return $this->stub(__FUNCTION__, func_get_args());
    }

    public static function getAvailableDrivers()
    {
        return self::stub(__FUNCTION__, func_get_args());
    }
}