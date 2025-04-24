<?php

$settings = parse_ini_file( 'config.ini', true );
class myStromSwitch
{
	public function __construct(
		public readonly string $ip,
		public readonly string $name,
		public readonly string $room_name )
	{
	}
}
try {
	$db = new \mysqli(
		$settings['db']['server'],
		$settings['db']['user'],
		$settings['db']['passwort'],
		$settings['db']['name']
	);

	if( $db->connect_error ) {
		throw new Exception( "Connection failed: " . $db->connect_error );
	}

	$db->set_charset( "utf8" );

	$switches = [];
	foreach( $db->query( "select switch.name switch_name,ip,room.name room_name from switch join room using(room_id)" ) as $switch ) {
		$switches[$switch['ip']] = new myStromSwitch(
			$switch['ip'],
			$switch['switch_name'],
			$switch['room_name']
		);
	}
} catch ( Exception $x ) {
	error_log( $x->getMessage() );
	exit( 0 );
}

function getSwitchState( $switchIP )
{
	$ch  = curl_init();
	$url = "http://{$switchIP}/report";
	// use curl_setopt_array to set multiple options
	curl_setopt_array( $ch, [
		CURLOPT_HTTPHEADER     => ['Content-Type:application/json'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_URL            => $url
	] );
	$result = curl_exec( $ch );
	curl_close( $ch );

	return json_decode( $result );
}

?>
<!doctype html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
	.flex-container {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	.flex-container>button {
		background: #5AB400;
		width: 200px;
		height: 200px;
		margin: 10px;
		text-align: center;

	}

	.flex-container>button.on {
		background: #7AD400;
	}
</style>
<div class="flex-container" id="switch-panel">
	<?php

	foreach( $switches as $url => $switch ) {
		$name  = $switch->name;
		$room  = $switch->room_name;
		$state = getSwitchState( $url );
		$state = $state?->relay ? "on" : "";
		echo "<button class='switch-button $state' data-switch-ip='$url'>
<h2>$name</h2>
<p>$room</p>
<p style='font-size:small;'>0.0</p>
<p>0.0</p>
</button>";
	}
	?>
</div>

<script>
	function toggle(sw) {
		let xhr = new XMLHttpRequest();
		let url = "./ajax/toggle.php?switch-ip=" + sw.target.dataset["switchIp"];
		xhr.open("GET", url);
		xhr.responseType = "json";
		xhr.timeout = 1000;
		xhr.onload = function () {
			if (xhr.response["relay"])
				sw.target.classList.add("on");
			else
				sw.target.classList.remove("on");
		};
		xhr.send();
	}

	window.onload = function () {
		let switchPanel = document.getElementById("switch-panel");
		switchPanel.addEventListener("click", toggle);

	};

	function checkSwitches() {
		let switches = document.querySelectorAll(".switch-button");

		switches.forEach((sw) => {
			if (sw) {
				let xhr = new XMLHttpRequest();
				let url = "./ajax/state.php?switch-ip=" + sw.dataset.switchIp;
				xhr.open("GET", url);
				xhr.responseType = "json";
				xhr.timeout = 2000;
				xhr.onload = function () {
					let temp = xhr.response["temperature"];
					let power = xhr.response["power"];
					sw.getElementsByTagName("p")[1].innerText = temp.toFixed(1) + '°C';
					sw.getElementsByTagName("p")[2].innerText = power.toFixed(1) + ' watt';
					if (xhr.response["relay"])
						sw.classList.add("on");
					else
						sw.classList.remove("on");
				};
				xhr.send();
			}
		});
	}
	window.setInterval(checkSwitches, 2500);
</script>