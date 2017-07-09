<?php
function curlGetURL($url, $conn_timeout, $data_timeout, $proxy = '') {
    // look like timeout($data_timeout_ms) have a bug on production
    // cause very high timeout rate
    // however, our integration test need to use this function to prevent file_get_content return error on HTTP 400 response(for non utf-8 input)

    $data = FALSE;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,               $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,    1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    $conn_timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT,           $data_timeout);
    curl_setopt($ch, CURLOPT_USERAGENT,         'Mozilla/5.0 (compatible; Feebeebot/1.0; +http://feebee.com.tw)');
    curl_setopt($ch, CURLOPT_HTTPHEADER,        ['Connection: close']);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,    1);
    curl_setopt($ch, CURLOPT_MAXREDIRS,         7);
    curl_setopt($ch, CURLOPT_AUTOREFERER,       true);

    // $verbose    = fopen('php://temp', 'w');
    // curl_setopt($ch, CURLOPT_VERBOSE, true);
    // curl_setopt($ch, CURLOPT_STDERR, $verbose);


    if ($proxy) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    }
    $data = curl_exec($ch);
    if($data === false) {
        // rewind($verbose);
        // $debuginfo  = " (" . stream_get_contents($verbose) . ")";
        $debuginfo  = "";

        error("curl_exec error:", curl_errno($ch) . " : " . curl_error($ch) . " [$url]$debuginfo");
    }
    curl_close($ch);
    //fclose($verbose);

    return $data;
}

?>
