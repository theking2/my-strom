<?php
header('Content-Type: application/json');
try {
  $db = new \PDO( "mysql:dbname=myStrom;host=localhost",
	"myStrom",
	"myStrom",
	[
		\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		\PDO::ATTR_PERSISTENT => true
	]
  );

  $states = [];
  foreach( $db->query("select SwitchName,IPAddr,RoomName from Switch inner join Room using(RoomID)") as $switch) {
	$url = "http://{$switch['IPAddr']}/report";
	$options = 
		[ CURLOPT_URL => $url
		, CURLOPT_HEADER => 0
		, CURLOPT_TIMEOUT => 500
		, CURLOPT_RETURNTRANSFER => true
		];
	$ch = curl_init();
	curl_setopt_array( $ch, $options );
	if( $result = curl_exec( $ch ) ) {
		$states[$switch['SwitchName']] = json_decode($result);
	}
    curl_close( $ch );

  }
  echo json_encode( $states );

} catch( Exception $x ) { print_r( $x ); }

