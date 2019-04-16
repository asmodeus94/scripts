<?php

function nonBlocking($address, $postParameters)
{
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    $socket = stream_socket_client("ssl://$address:443", $errno, $errstr, 1, STREAM_CLIENT_ASYNC_CONNECT, $context);

    if (!$socket) {
        return false;
    }

    $content = http_build_query($postParameters);

    $out = "POST /socket HTTP/1.1\r\n";
    $out .= "Host: $address\r\n";
    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out .= "Content-Length: " . strlen($content) . "\r\n";
    $out .= "Connection: close\r\n";
    $out .= "\r\n";
    fwrite($socket, $out);
    fwrite($socket, $content);

    return true;
}
