<?php
$ch = curl_init();
$url = "http://" . $_GET["switch-ip"] . "/toggle";
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, 0 );
curl_exec( $ch );
curl_close( $ch );
