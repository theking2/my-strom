<?php
$ch = curl_init();
error_log($_GET["switch-name"]);
$url = "http://" . $_GET["switch-name"] . "/report";
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, 0 );
curl_exec( $ch );
curl_close( $ch );