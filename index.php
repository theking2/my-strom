<?php declare(strict_types=1);
require 'db.php';
require 'switches.php';

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
		width: 150px;
		height: 100%;
		margin: 10px;
		text-align: center;

	}

	.flex-container>button.on {
		background: #7AD400;
	}
</style>
<div class="flex-container" id="switch-panel">
	<?php
	foreach( SWITCHES as $url => $switch ) {
		$name = $switch->name;
		$room = $switch->room_name;

		echo "<button class='switch-button' data-switch-ip='$url'>
<h2>$name</h2>
<p>$room</p>
<p id='temperature' style='font-size:small;'>...</p>
<p id='watts'>...</p>
</button>";
	}
	?>
</div>

<script>
	/**
	 * Listen for the stream of sensor data from the server
	 * and update the UI accordingly.
	 */
	const evtSource = new EventSource('stream.php');

	evtSource.onmessage = event => {
		data = JSON.parse(event.data);
		for (const [key, value] of Object.entries(data)) {
			let button = document.querySelector(`button[data-switch-ip="${key}"]`);
			if (value.relay) {
				button.classList.add("on");
			} else {
				button.classList.remove("on");
			}

			button.querySelector("#temperature").innerText = value.temperature;
			button.querySelector("#watts").innerText = `${value.power}/${value.Ws}`;
		}

	};

	const buttons = document.querySelectorAll(".switch-button");
	buttons.forEach(button => {
		button.addEventListener("click", toggle, { capture: true });
	});


	async function toggle(sw) {
		sw.preventDefault();

		if (null == sw.currentTarget.dataset["switchIp"]) {
			console.log("No switch IP found");
			console.log(sw.target);
			return;
		}
		let url = "./ajax/toggle.php?switch-ip=" + sw.target.dataset["switchIp"];
		// Cross-Origin Resource Sharing (CORS) 
		//let url = `http://${sw.target.dataset["switchIp"]}/toggle`;
		try {
			let response = await fetch(url);
			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}

		} catch (error) {
			console.error("Error toggling switch:", error);
		}
	}

</script>
