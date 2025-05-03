<?php declare(strict_types=1);
class myStromSwitch
{
	public function __construct(
		public readonly string $ip,
		public readonly string $name,
		public readonly string $room_name )
	{
	}
}

$switches = [];
foreach( $db->query( "select switch.name switch_name,ip,room.name room_name from switch join room using(room_id)" ) as $switch ) {
    $switches[$switch[ 'ip' ]] = new myStromSwitch(
        $switch[ 'ip' ],
        $switch[ 'switch_name' ],
        $switch[ 'room_name' ]
    );
}

define( 'SWITCHES', $switches );