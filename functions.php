<?php

	If ($Database['driver'] == 'mysql') {
		$pdo = new PDO("mysql:host=".$Database['host'].";port=".$Database['port'].";dbname=".$Database['dbname'], $Database['username'], $Database['password']);
	} ElseIf ($Database['driver'] == 'odbc') {
		$pdo = new PDO("odbc:Driver={".$Database['dsn']."};Server=".$Database['host'].";Port=".$Database['port'].";Database=".$Database['dbname'].";User=".$Database['username'].";Password=".$Database['password'].";");
	} Else {
		echo "Configuration Error - No database driver specified";
	}

	function redirect($url) {
		if (!headers_sent()) {    
			header('Location: '.$url);
			exit;
		} else {
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>'; exit;
		}
	}

	function allowedCountries() {
		global $allowedCountries,$countriesArray;
		include_once("countryarray.php");

		$countryNameList[] = "^(";
		foreach ($allowedCountries as $countryCode) {
			$countryNameList[] = $countriesArray[$countryCode]."|";
		}
		return preg_replace("/\|$/",")$",implode($countryNameList)); 
	}

	function bannedCountries() {
		global $allowedCountries, $countriesArray;
		include_once("countryarray.php");
		
		$countryNameList[] = "^(?!";
		foreach ($allowedCountries as $countryCode) {
			$countryNameList[] = $countriesArray[$countryCode]."|";
		}
		return preg_replace("/\|$/",").*$",implode($countryNameList));
	}
	
?>
