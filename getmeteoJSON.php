<html>
	<head>
		<meta charset="utf-8"/>
		<style>
			div.forecast {
				background-color: yellow;
				border-color: black;
				border-style:solid;
				border-width:2px;
				border-radius:8px;
				width:200px;
				height:200px;
				margin:4px;
				padding:2px;
				display:inline-block; 
			}
		</style>
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
		
		//  Initiate curl-
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
		
		echo "<hr/>";
		
		$forecastArray = $forecastPHP->data->weather;
		
		function forecastHTML ($date,$desc,$icon,$tmax,$tmin) {
			$strHTML =  "
			<div class=\"forecast\">
			  <p class=\"date\"> $date </p>
			  <p class=\"desc\"> $desc </p>
			  <p><img src=\"$icon\"/></p>
			  <p class=\"temp\">min : $tmin -- max : $tmax</p>
		   </div>
		   ";
		   return $strHTML;
		}
		function forecastXML ($date,$desc,$icon,$tmax,$tmin) {
			$xml = new SimpleXMLElement("<div/>");
			$xml->addAttribute("class","forecast");
			$dateXml = $xml->addChild("p",$date);
			$dateXml->addAttribute("class","date");
			$descXml = $xml->addChild("p",$desc);
			$descXml->addAttribute("class","desc");
			$iconXml = $xml->addChild("img");
			$iconXml->addAttribute("src",$icon);
			$tempXml = $xml->addChild("p","min : $tmin -- max: $tmax");
			$tempXml->addAttribute("class","temp");
			$strXML = $xml->asXML();
			return $strXML;
		}
		
		//echo forecastHTML("10-10-2014","sol","http://cdn.worldweatheronline.net/images/wsymbols01_png_64/wsymbol_0017_cloudy_with_light_rain.png",30,15);
		foreach($forecastArray as $forecastDay) {
			//echo forecastHTML($forecastDay->date, $forecastDay->weatherDesc[0]->value, $forecastDay->weatherIconUrl[0]->value, $forecastDay->tempMaxC, $forecastDay->tempMinC);	
			echo forecastXML($forecastDay->date, $forecastDay->weatherDesc[0]->value, $forecastDay->weatherIconUrl[0]->value, $forecastDay->tempMaxC, $forecastDay->tempMinC);
		}
		
		?>
	</body>
</html>