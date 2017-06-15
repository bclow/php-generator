<?php
include_once(__DIR__.'/../lib/CurlAsync.php');

function getMCURL() {
    //static $mcurl  = null;
    $mcurl  = null;
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

function f1($sleep, $key, $a, $b) : Generator {
    $url    = buildUrl($sleep, $a, $b);
    $mcurl  = getMCURL();
    $mcurl->$key($url);
    $val    = yield ;

    $res    = $mcurl->$key();
    $obj    = json_decode($res);
    yield $obj->res;
}


class gen {
    protected $gen;

    public function __construct($gen) {
        $this->gen = $gen;
        $this->gen->current();
    }

    public function wait() : int{
        $res        = $this->gen->send(null);
        return $res;
    }
}


$sleep = 500;

$task1 = new gen(f1(500, "g1", 1, 2));
$task2 = new gen(f1(400, "g2", 3, 4));
$task3 = new gen(f1(700, "g3", 30, 40));

$r1    = $task1->wait();
$r2    = $task2->wait();
$r3    = $task3->wait();

echo $r1, "\t", $r2, "\t", $r3, "\n";



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
