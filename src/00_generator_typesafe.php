<?php
function f() : Generator {
    $x = [ 1, 2, 3, 4, 5];
    foreach($x as $item) {
        yield $item;
    }
}


foreach(f() as $x) {
    echo $x , "\n";
}
?>
