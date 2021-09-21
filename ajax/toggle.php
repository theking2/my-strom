<?php
$ch = curl_init();
$url = "http://" . $_GET["switch-ip"] . "/toggle";
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, 0 );
if( true!==curl_exec( $ch ) ) {
	error_log("toggle failed: $url");
}
curl_close( $ch );
