<?php include_once("config.php") ?>
<?php include_once("functions.php") ?>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('timeofday', 'Hour');
	data.addColumn('number', 'Avg Hits');
	data.addRows([
<?php 
	$query = $pdo->prepare("
		SELECT 
			hour, 
			ROUND(AVG(numhits), 1) AS avghits 
		FROM (
			SELECT 
				DATE(timestamp) AS day, 
				HOUR(timestamp) AS hour, 
				COUNT(DISTINCT(".$countryColumnName.")) as numhits 
			FROM ".$Database['tablename']." 
			GROUP BY DATE(timestamp), HOUR(timestamp)
		) d 
		GROUP BY hour 
		ORDER BY hour ASC
	");
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		echo "[[".$row['hour'].", 0, 0], ".$row['avghits']."],";
	}
?>
	]);

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_countries_per_hour'));
	  chart.draw(data, {
		width: 350,
		height: 200,
		legend: 'none',
		colors: ['#ff0000']
	  });
}	
</script>
