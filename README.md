# Example usage

```php
<?php

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


```

see example.php

