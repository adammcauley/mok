<?php
require_once('Mok.php');

$o = Mok::getMok();
$o2 = Mok::getMok();

$o::foo(5,10);
$o::foo(15,100);
$o::foo(25,1000);
print $o->foo(5) . PHP_EOL;
print $o->foo(15) . PHP_EOL;
print $o->foo(25) . PHP_EOL;

$o2::foo(5,12);
print $o2->foo(5) . PHP_EOL;

