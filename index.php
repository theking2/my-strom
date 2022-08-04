<!doctype html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php

try {
	$db = new \PDO(
		"mysql:dbname=myStrom;host=localhost",
		"myStrom",
		"myStrom",
		[
			\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
			\PDO::ATTR_PERSISTENT => true
		]
	);

	$switches = [];
	foreach ($db->query("select SwitchName,IPAddr,RoomName from Switch inner join Room using(RoomID)") as $switch) {
		$switches[$switch['IPAddr']] = $switch['SwitchName'];
	}
} catch( Exception $x ) {
	error_log($x-> message );
}
?>
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


	function getSwitchState($switchIP) {
		$ch = curl_init();
		$url = "http://$switchIPreport";
		// please use curl_setopt_array here
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result);
	}

	foreach ($switches as $url => $name) {
		$state = getSwitchState($url);
		$state = $state->relay ? "on" : "";
		echo "<button class='switch-button $state' data-switch-ip='$url'>
<h2>$name</h2>
<p></p>
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
		xhr.onload = function() {
			if (xhr.response["relay"])
				sw.target.classList.add("on");
			else
				sw.target.classList.remove("on");
		};
		xhr.send();
	}

	window.onload = function() {
		let switchPanel = document.getElementById("switch-panel");
		switchPanel.addEventListener("click", toggle);

	};

	function checkSwitches() {
		let switches = document.querySelectorAll (".switch-button");

		switches.forEach((sw) => {
			if (sw) {
				let xhr = new XMLHttpRequest();
				let url = "./ajax/state.php?switch-ip=" + sw.dataset.switchIp;
				xhr.open("GET", url);
				xhr.responseType = "json";
				xhr.timeout = 2000;
				xhr.onload = function() {
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