<?php
function f() : Generator {
    $x = [ 1, 2, 3, 4, 5];
    foreach($x as $item) {
        yield $item;
    }
}

$x1=f();
$x2=f();
echo $x1->current(), "\n";
echo $x2->current(), "\n";
$x1->next();
echo $x1->current(), "\n";
$x1->next();
echo $x1->current(), "\n";
$x2->next();
echo $x2->current(), "\n";
$x2->next();
echo $x2->current(), "\n";
/*
foreach(f() as $x) {
    echo $x , "\n";
}
 */
//echo $x2->next(), "\n";
//echo $x2->next(), "\n";
?>
