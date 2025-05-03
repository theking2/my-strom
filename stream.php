<?php
require 'db.php';
require 'switches.php';

header( 'Content-Type: text/event-stream' );
header( 'Cache-Control: no-cache' );
header( 'Connection: keep-alive' );
header('X-Accel-Buffering: no');

function gatherData( array $switches )
{
    $states = [];
    foreach( $switches as $ip => $switch ) {
        $url = "http://{$ip}/report";
        $ch  = curl_init();
        curl_setopt_array( $ch,
            [
                CURLOPT_URL            => $url,
                CURLOPT_HEADER         => 0,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_RETURNTRANSFER => true,
            ]
        );
        //data: {"light":{"power":0,"Ws":0,"relay":false,"temperature":21.34,"boot_id":"B39B5F49","energy_since_boot":20043137.83,"time_since_boot":1462497},"media":{"power":0,"Ws":0,"relay":false,"temperature":22.06,"boot_id":"F8ACBB6A","energy_since_boot":48251956.15,"time_since_boot":1462013},"studie":{"power":0,"Ws":0,"relay":false,"temperature":27.91,"boot_id":"E078C0F4","energy_since_boot":2856718.12,"time_since_boot":1462465}}

        if( $result = curl_exec( $ch ) ) {
            $result = json_decode( $result, true );
            $states[$ip] = [
                "power" => $result["power"],
                "Ws" => $result["Ws"],
                "relay" => $result["relay"],
                "temperature" => $result["temperature"],
                "energy_since_boot" => $result["energy_since_boot"],
            ];
        }
        curl_close( $ch );
    }

    echo "event\n",
         'data: ' . json_encode( $states );
    echo "\n\n";

}

ob_implicit_flush(1);
@ini_set('implicit_flush',1);
@ob_end_clean();

while( true ) {
    gatherData( SWITCHES );
    sleep( 4 );
}
