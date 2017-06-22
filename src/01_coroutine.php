<?php
include_once(__DIR__.'/../lib/CurlAsync.php');

function getMCURL() {
    static $mcurl  = null;
    if($mcurl == null) {
        $mcurl = new CurlAsync();
    }

    return $mcurl;
}

function buildUrl($sleep, $x, $y) {
    $baseurl    = "http://n4.in.sitetag.info/bidCompare/php/util/slow.php";
    $url        = "{$baseurl}?sleep={$sleep}&x=$x&y=$y";
    return $url;
}

function f1($sleep, $a, $b, $timeout=1) : Generator {
    $url    = buildUrl($sleep, $a, $b);
    $mcurl  = getMCURL();

    $future = $mcurl->submitURL(buildUrl($sleep, $a, $b), $timeout);

    yield;

    $res    = $future->get();
    if($res) {
        $obj    = json_decode($res);
        yield $obj->res;
    } else {
        yield null;
    }

}


class gen {
    protected $gen;

    public function __construct($gen) {
        $this->gen = $gen;
        $this->gen->current();
    }

    public function get() {
        $res        = $this->gen->send(null);
        return $res;
    }
}


$time_start = microtime(true);
$sleep = 500;

// TODO: should only take 1s but it took 1.3s
$task1 = new gen(f1(300, 1, 2));
$task3 = new gen(f1(2500, 30, 40, 2));
$task2 = new gen(f1(200, 3, 4));

$r1    = $task1->get();
$r2    = $task2->get();
$r3    = $task3->get();
$time_end = microtime(true);

if($r1) {
    echo "r1:$r1\n";
}
if($r2) {
    echo "r2:$r2\n";
}
if($r3) {
    echo "r3:$r3\n";
}

echo "usage : ", ($time_end-$time_start)*1000, "\n";


/*

$gen1 = f1($sleep, "g1", 1, 2);
$gen2 = f1($sleep, "g2", 3, 4);

$gen1->current();
$gen2->current();
$r1   = $gen1->send(null);
$r2   = $gen2->send(null);

var_dump($r1);
var_dump($r2);
 */

/*
function coroutine1() : Generator {
}
 */
function gen() : Generator {
    $ret = (yield 'yield1');
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}

/*
$gen = gen();
var_dump($gen->current());    // string(6) "yield1"
var_dump($gen->send('ret1')); // string(4) "ret1"   (the first var_dump in gen)
                              // string(6) "yield2" (the var_dump of the ->send() return value)
var_dump($gen->send('ret2')); // string(4) "ret2"   (again from within gen)
 */
                              // NULL               (the return value of ->send())
?>
