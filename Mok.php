<?php
/**
 * Mok
 *
 * PHP Version 5.3
 *
 * @package Mok
 * @author  "Adam McAuley" <amcauley@plus.net>
 * @author  "Stephen Lang" <slang@plus.net>
 * @link    https://github.com/skl/mok
 */

require_once 'MokC.php';
require_once 'MokExceptions.php';

/**
 * Mok
 *
 * @package Mok
 * @author  "Adam McAuley" <amcauley@plus.net>
 * @author  "Stephen Lang" <slang@plus.net>
 * @link    https://github.com/skl/mok
 */
class Mok
{
    /**
     * @var array $map Hashmap containing function signatures and return values
     */
    private static $_map = array();

    /**
     * @var array $_staticMap Hashmap containing static function signatures and return values
     */
    private static $_staticMap = array();

    /**
     * Magic setter used for creating mock properties
     *
     * @param string $property    The name of the mock property to create
     * @param mixed  $returnValue The value to return upon property access
     *
     * @return Mok
     */
    public function __set($property, $returnValue)
    {
        self::$_map[$property] = $returnValue;
    }

    /**
     * Magic getter used to access mock properties
     *
     * @param string $property The name of the property to access
     *
     * @return mixed The return value of the property
     */
    public function __get($property)
    {
        return self::$_map[$property];
    }

    /**
     * Magic method used to call mock methods.
     *
     * @param string $methodName The name of the method to create/execute
     * @param array  $arguments  The arguments to expect/pass to the method.
     *
     * @return mixed The return value of the method
     */
    public function __call($methodName, $arguments)
    {
        $fp = "$methodName(" . implode(',', $arguments) . ")";
        if (in_array($fp, array_keys(self::$_map))) {
            return self::$_map[$fp] ;
        } else {
            throw new MokMethodNotImplementedException("Method {$methodName}() was not defined!");
        }
    }

    /**
     * Magic method used to call mock methods.
     *
     * @param string $methodName The name of the method to execute
     * @param array  $arguments  The arguments to pass to the method.
     *
     * @return mixed The return value of the method
     * @throws MokMethodNotImplementedException
     */
    public static function __callStatic($methodName, $arguments)
    {
        $sfp = "$methodName(" . implode(',', $arguments) . ')' ;
        if (array_key_exists($sfp, self::$_staticMap)) {
            return self::$_staticMap[$sfp] ;
        } else {
            throw new MokMethodNotImplementedException("Static Method {$methodName}() was not defined!");
        }
    }

    /**
     * create a mock static method
     *
     * @return void
     */
    public static function mStat()
    {
        $arguments = func_get_args();
        // TODO : make this robust
        $staticMethodName = array_shift($arguments);
        $returnValue = array_pop($arguments);
        self::$_staticMap["$staticMethodName(" . implode(',', $arguments) . ')'] = $returnValue;
    }

    /**
     * create a mock instance method
     *
     * @return void
     */
    public static function mInst()
    {
        $arguments = func_get_args();
        $newMethodName = array_shift($arguments);
        $returnValue = array_pop($arguments);
        $fp = "$newMethodName(" . implode(',', $arguments) . ')' ;
        if ($returnValue instanceof MokC) {
            // TODO handle expected access
            $returnValue = $returnValue->getReturnValue();
        }
        self::$_map[$fp] = $returnValue;
    }

    /**
     * Magic toString method provides string representation of the hashmap for
     * debugging purposes
     *
     * @return string
     */
    public function __toString()
    {
        return print_r(self::$_map, true);
    }

    /**
     * String to hold the base name of generated classes
     *
     * @var string
     */
    private static $_mokClassBaseName = 'Mokki';

    /**
     * create a Mok object with it's own unique memory space
     * oh dear god what are you thinking?!
     *
     * @return Mok
     */
    public static function getMok()
    {
        $namething = self::$_mokClassBaseName . rand();
        $file = file_get_contents('Mok.php', true);
        $file = preg_replace('/class Mok/', "class ".$namething, $file, 1);
        $file = preg_replace('/<\?php/', '', $file, 1);
        $str = $file . ' $o = new '.$namething.'(); return $o;' ;
        $obj = eval($str);
        return $obj;
    }

}