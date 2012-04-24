<?php
/**
 * Mok
 *
 * @package Mok
 * @author  "Adam McAuley" <amcauley@plus.net>
 * @author  "Stephen Lang" <slang@plus.net>
 * @link    https://github.com/skl/mok
 */

require_once 'MokC.php';

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
     * Magic method used to create mock methods (when unlocked) or execute mock
     * methos (when locked).
     *
     * @param string $methodName The name of the method to create/execute
     * @param array  $arguments  The arguments to expect/pass to the method.
     *                           Final argument when creating a method is always the return value.
     *
     * @return mixed The return value of the method
     */
    public function __call($methodName, $arguments)
    {
        $fp = "$methodName(" . implode(',', $arguments) . ")";
        if (in_array($fp, array_keys(self::$_map))) {
            return self::$_map[$fp] ;
        } else {
            throw new Exception("Method {$methodName}() was not defined!") ;
        }
    }

    public static function __callStatic($methodName, $arguments)
    {
        $returnValue = array_pop($arguments);
        if ($returnValue instanceof MokC) {
            // TODO handle expected access
            $returnValue = $returnValue->getReturnValue();
        }
        self::$_map["$methodName(" . implode(',', $arguments) . ')'] = $returnValue;
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

    // oh dear god what are you thinking?!
    private static $namething = 'Mokki';
    public static function getMok()
    {
        $namething = self::$namething = self::$namething . rand() ;
        $file = file_get_contents('Mok.php', true);
        $file = preg_replace('/class Mok/' , "class ".$namething , $file , 1 );
        $file = preg_replace('/<\?php/' , '' , $file , 1 );
        $str = $file . ' $o = new '.$namething.'(); return $o;' ;
        $obj = eval($str);
        return $obj;
    }

}
