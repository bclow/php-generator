<?php
include_once(__DIR__.'/../lib/CurlAsync.inc');
include_once(__DIR__.'/../lib/CurlSync.inc');

function buildUrl($sleep, $x, $y) {
    $baseurl    = "http://n4.in.sitetag.info/bidCompare/php/util/slow.php";
    $url        = "{$baseurl}?sleep={$sleep}&x=$x&y=$y";
    return $url;
}

function f1($sleep, $a, $b, $timeout=1) : Generator {
    $mcurl  = CurlAsync::getInstance();

    $url    = buildUrl($sleep, $a, $b);
    $future = $mcurl->submitURL(buildUrl($sleep, $a, $b), $timeout);

    yield null;

    $res    = $future->get();
    if($res) {
        $obj    = json_decode($res);
        yield $obj->res;
    } else {
        yield null;
    }

}




$time_start = microtime(true);
$sleep = 500;

// TODO: should only take 1s but it took 1.3s
$task1 = new FunctionGenerator(f1(300, 1, 2));
$task3 = new FunctionGenerator(f1(500, 30, 40, 2));
$r3    = $task3->get();
$task2 = new FunctionGenerator(f1(200, 3, 4));

/*
$url   = buildUrl(200, 10, 10);
$res=curlGetURL($url, 1, 1);
print_r($res);
 */

$taskx = new FunctionGenerator(f1(200, 10, 10));


$r1    = $task1->get();
$r2    = $task2->get();
//$r3    = $task3->get();
$taskx = $taskx->get();

$url   = buildUrl(200, 10, 10);
$res=curlGetURL($url, 1, 1);
print_r($res);
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
?>
