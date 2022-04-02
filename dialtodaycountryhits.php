<script type="text/javascript">
	google.charts.load('current', {'packages':['gauge']});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {

	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
<?php
	include_once("config.php");
	include_once("functions.php");

	//Get guage max
	$sql = $pdo->prepare("
		SELECT	
			ROUND(((COUNT(DISTINCT(".$countryColumnName."))) * 1.2), -1) AS dailymax,
			DATE(timestamp) AS daily
		FROM ".$Database['tablename']." 
		GROUP BY daily
		ORDER BY dailymax DESC
		LIMIT 1
	");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		$redTo = $row['dailymax'];
	}
	//Set guage color marker points
	$redFrom = ($redTo / 1.2);
	$yellowTo = $redFrom;
	$yellowFrom = ($yellowTo * 0.75);

	//Get current (today's) bans
	$sql = $pdo->prepare("
		SELECT	
			COUNT(DISTINCT(".$countryColumnName.")) AS hits
		FROM (
			SELECT * 
			FROM ".$Database['tablename']." 
			WHERE '".date('Y-m-d')." 00:00:00' <= timestamp
		) AS A 
		WHERE timestamp <= '".date('Y-m-d')." 23:59:59'
	");
	$sql->execute();
	$hits = $sql->fetchColumn();
	echo "['Countries', ".$hits."]";
	echo "]);";

	echo "var options = { ";
	echo "width: 100, height: 100, ";
	echo "min: 0, max: ".$redTo.", ";
	echo "redFrom: ".$redFrom.", redTo: ".$redTo.", ";
	echo "yellowFrom: ".$yellowFrom.", yellowTo: ".$yellowTo.", ";
?>
		minorTicks: 10
	};

	var chart = new google.visualization.Gauge(document.getElementById('todays_countries_dial'));

	chart.draw(data, options);

	// setInterval(function() {
		// data.setValue(0, 1, 40 + Math.round(60 * Math.random()));
		// chart.draw(data, options);
	// }, 13000);
	}
</script>
