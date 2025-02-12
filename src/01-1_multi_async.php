<?php
include_once(__DIR__.'/../lib/CurlAsync.inc');

function buildUrl($sleep) {
    // https://httpbin.org/drip?duration=2&numbytes=10&code=200&delay=2
    $baseurl    = 'https://httpbin.org/drip?duration=0&numbytes=10&code=200&delay=';
    $baseurl    .= $sleep;
    return $baseurl;
}

function f1() {
    $mcurl  = CurlAsync::getInstance();

    $sleepArr  = [ 1, 2, 4];
    // $sleepArr  = [ 4, 2, 1];
    //$sleepArr  = [ 4, 4, 4];
    $futureRes = [];
    foreach($sleepArr as $s) {
        $url = buildUrl($s);
        $fres = $mcurl->submitURL($url, 10);
        $futureRes[] = $fres;
    }



    foreach($futureRes as $res) {
        echo $res->get(), "\n";
    }
}

f1();

