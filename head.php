<?php
	include_once("config.php");

	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		if (!(($_COOKIE['username'] === $user_name) && ($_COOKIE['password'] === md5($pass_word)))) {
			header('Location: login.php');
		}
	} else {
		header('Location: login.php');
	}
?>

<!DOCTYPE html> 
<html>
<head>
<title>hMailServer IDS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Style -->
<link rel="stylesheet" type="text/css" media="all" href="stylesheet.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"> 

<!-- Autocomplete jquery -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Goolag charts -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php 
	if (preg_match('/index\.php$/', $_SERVER['PHP_SELF'])) {
		include("charthitsperday.php");
		include("charthitsperhour.php");
		if (!$useGeoIP) {include("dialtodayhits.php");}
		if ($useGeoIP) {
			include("dialtodaybannedhits.php");
			include("dialtodayallowedhits.php");
			include("dialtodaycountryhits.php");
			include("chartcountriesperday.php");
			include("chartcountriesperhour.php");
		}
	}
?>

</head>
<body>

<?php include("header.php") ?>

<div class="wrapper">
<br>