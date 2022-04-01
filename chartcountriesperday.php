<?php include_once("config.php") ?>
<?php include_once("functions.php") ?>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart", "line"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Countries');
	data.addRows([
<?php 
	$query = $pdo->prepare("
		SELECT 
			DATE(timestamp) AS daily, 
			DATE_FORMAT(timestamp, '%Y') AS year,
			(DATE_FORMAT(timestamp, '%c') - 1) AS month,
			DATE_FORMAT(timestamp, '%e') AS day,
			COUNT(DISTINCT(country)) AS countryperday 
		FROM ".$Database['tablename']." 
		WHERE DATE(timestamp) < DATE(NOW()) 
		GROUP BY daily ASC
	");
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		echo "[new Date(".$row['year'].", ".$row['month'].", ".$row['day']."), ".$row['countryperday']."],";
	}
?>
	]);

	var chart = new google.visualization.LineChart(document.getElementById('chart_countries_per_day'));
	  chart.draw(data, {
		width: 350,
		height: 200,
		colors: ['#ff0000'],
		legend: 'none',
		trendlines: { 0: { 
			type: 'polynomial',
			degree: 1,
			visibleInLegend: true,
			}
		}
	  });
}	
</script>
