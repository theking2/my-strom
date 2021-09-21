<?php
$ch = curl_init();
error_log($_GET["switch-ip"]);
$url = "http://" . $_GET["switch-ip"] . "/report";
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, 0 );
curl_setopt( $ch, CURLOPT_TIMEOUT, 500 );
curl_exec( $ch );
curl_close( $ch );