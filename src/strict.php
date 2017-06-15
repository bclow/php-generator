<?php
declare(strict_types=1);
class C {

}
class D extends C {}

// This doesn't extend C.
class E {}

function f(C $c) {
    echo get_class($c)."\n";
}

function sum(int $a, int $b): string {
        return (string)($a + $b);
}
function x(float $a, float $b) : int {
    return (int)($a + $b);
}

/*
var_dump(sum(1, 2));
var_dump(sum("1.5", "2.9"));
 */

function g() {
    f(new C);
    f(new D);
    f(new E);
}

//g();
//var_dump(sum(2,2.3));
var_dump(sum(2,3));
