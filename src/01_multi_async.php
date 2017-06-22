<?php
include_once(__DIR__.'/../lib/CurlAsync.php');

function getMCURL() {
    return new CurlAsync();

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

function f1($sleep, $key, $a, $b) {
    $mcurl1  = getMCURL();
    $url1    = buildUrl($sleep, $a, $b);
    $mcurl1->$key($url1);

    $mcurl2  = getMCURL();
    $url2    = buildUrl($sleep, $a, $b);
    $mcurl2->$key($url2);

    $mcurl3  = getMCURL();
    $url3    = buildUrl($sleep, $a, $b);
    $mcurl3->$key($url3);


    $res1    = $mcurl1->$key();
    $res2    = $mcurl2->$key();
    $res3    = $mcurl3->$key();

    return "$res1, $res2, $res3\n";
}

function f2($sleep, $key, $a, $b) {
    $mcurl1  = getMCURL();
    $url1    = buildUrl($sleep, $a, $b);
    $mcurl1->key1($url1);
    $mcurl1->key2($url1);
    $mcurl1->key3($url1);

    $res1    = $mcurl1->key1();
    $res2    = $mcurl1->key2();
    $res3    = $mcurl1->key3();

    return "$res1, $res2, $res3\n";
}

function f3($sleep, $key, $a, $b) {
    $mcurl1  = getMCURL();
    //$mcurl2  = getMCURL();
    $mcurl2  = $mcurl1;
    //$mcurl3  = getMCURL();
    $mcurl3  = $mcurl1;

    $url1    = buildUrl($sleep, $a, $b);
    $mcurl1->key1($url1);
    $mcurl1->key2($url1);
    $mcurl2->key3($url1);
    $mcurl2->key4($url1);
    $mcurl3->key5($url1);
    $mcurl3->key6($url1);

    $res1    = $mcurl1->key1();
    $res2    = $mcurl1->key2();
    $res3    = $mcurl2->key3();
    $res4    = $mcurl2->key4();
    $res5    = $mcurl3->key5();
    $res6    = $mcurl3->key6();

    return "$res1, $res2, $res3, $res4, $res5, $res6\n";
}

//echo f1(1000, "a", 3, 3), "\n";
//echo f2(1000, "a", 3, 3), "\n";
echo f3(1000, "a", 3, 3), "\n";



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




    /*
    $sleep = 500;

$task1 = new gen(f1(2000, "g1", 1, 2));
$task2 = new gen(f1(2300, "g2", 3, 4));
$task3 = new gen(f1(2500, "g3", 30, 40));

$r1    = $task1->wait();
$r2    = $task2->wait();
$r3    = $task3->wait();

echo $r1, "\t", $r2, "\t", $r3, "\n";

 */


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
