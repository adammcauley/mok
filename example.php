<?php
/**
 * example of naughty mok usage
 *
 * PHP Version 5.3
 *
 * @package Mok
 * @author  "Adam McAuley" <amcauley@plus.net>
 * @author  "Stephen Lang" <slang@plus.net>
 * @link    https://github.com/skl/mok
 */

require_once('Mok.php');

try {

    $m = Mok::getMok();

    // Create public property 'bar' with value 'baz'
    $m->bar = 'baz';

    // Create method 'foo', expecting one parameter '5', returning 10
    $m::mInst('foo', 5, 10); // last parameter is always return value

    // Create static method 'sound', expecting one parameter 'cow' returning 'moo'
    $m::mStat('sound', 'cow', 'moo');

    // Create another mok with a parameter and a static method
    $mo = Mok::getMok();
    $mo->duck = 'quack';
    $mo::mStat('sound', 'cow', 'quack quack');

    // lets add these two together !
    $m->mo = $mo;

    // I wonder what happens when....

    print $m->foo(5) . PHP_EOL;   // prints 10
    print $m->bar . PHP_EOL;      // prints baz
    print $m->mo->duck . PHP_EOL; // prints quack
    print $m::sound('cow') . PHP_EOL; // prints 'moo'
    $x = $m->mo;
    print $x::sound('cow') . PHP_EOL; // prints 'quack quack'

    // ------------------------------------------

    $o = Mok::getMok();
    $o2 = Mok::getMok();
    $o3 = Mok::getMok();

    $o::mInst('foo', 5, 10);
    $o::mInst('foo', 15, 100);
    $o::mInst('foo', 25, 1000);
    print $o->foo(5) . PHP_EOL;
    print $o->foo(15) . PHP_EOL;
    print $o->foo(25) . PHP_EOL;

    $o2::mInst('foo', 5, 12);
    print $o2->foo(5) . PHP_EOL;

    $o3::mStat('getGame', 'craig', 'cricket');
    $o3::mStat('getGame', 'sally', 'snooker');
    $o3::mStat('getGame', 'martin', 'marbles');

    print $o3::getGame('craig') . PHP_EOL;
    print $o3::getGame('martin') . PHP_EOL;

    // this will throw an exception
    print $o2::getGame('craig') . PHP_EOL;

} catch (MokMethodNotImplementedException $e) {

    print $e->getMessage() . PHP_EOL ;
    print $e->getTraceAsString() . PHP_EOL ;

}