<?php

function test2()
{
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);
    $address = $_SERVER['SERVER_NAME'];
    $socket = stream_socket_client("ssl://$address:443", $errno, $errstr, 1, STREAM_CLIENT_ASYNC_CONNECT, $context);

    if (!$socket) {
        echo "$errstr ($errno)<br />\n";
    } else {
        $vars = [
            'number' => rand(1, 1000)
        ];
        $content = http_build_query($vars);

        var_dump($content);

        $out = "POST /socket HTTP/1.1\r\n";
        $out .= "Host: $address\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($content) . "\r\n";
        $out .= "Connection: close\r\n";
        $out .= "\r\n";
        fwrite($socket, $out);
        fwrite($socket, $content);

        var_dump($socket);
        echo 'cos juz po';
    }
}