<html>
	<head>
		<meta charset="utf-8"/>
	</head>
	<body>
		<h1> Estado do tempo em <?php echo $_GET['location'] ?></h1>
		<p><?php echo date("d-m-Y");?></p>
		<?php
		//api.worldweatheronline.com/free/v1/weather.ashx?q=perre&format=json&num_of_days=5&key=jx6a4hxmgej238dw8x4p8vvc
		$coreURL = "http://api.worldweatheronline.com/free/v1/weather.ashx?";
		$sep     ="&";
		$pformat ="format=json";
		$plocation = "q=".urlencode($_GET['location']);
		$pnumdays = "num_of_days=5";
		$pkey = "key=jx6a4hxmgej238dw8x4p8vvc";
		
		$callURL = $coreURL.$pformat.$sep.$plocation.$sep.$pnumdays.$sep.$pkey;
		//echo $callURL;
		
		$forecastJSON = file_get_contents($callURL);
		//echo($forecastJSON);
		
		//  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL,$callURL);
		// Execute
		$result=curl_exec($ch);
		// Closing
		curl_close($ch);
		echo("<hr/>");
		//var_dump($result);

		
		// descodificar o JSON => PHP
		echo("<hr/>");
		$forecastPHP = json_decode($forecastJSON);
		//var_dump($forecastPHP);
		
		$temperatura = $forecastPHP->data->current_condition[0]->temp_C;
		echo "A temperatura corrente é de ".$temperatura." ºC";
		
		?>
	</body>
</html>