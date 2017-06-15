<?php
function f($input) {
    $x = [ 1, 2, 3, 4, 5];
    if($input == 0) {
        foreach($x as $item) {
            yield $item;
        }
    } else {
        //yield [ 'a', 'b', 'c'];
        return [ 'a', 'b', 'c'];
    }
}


/*
foreach(f(0) as $x) {
    echo $x , "\n";
}
 */
// f(1) is a generator but not return  by yield
foreach(f(1) as $x) {
    foreach($x as $yy) {
        echo $yy, "\n";
    }
}
?>
