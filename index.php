<?php include("head.php") ?>

	<!-- START DIALS -->
	<div class="section">
		<h2>Today's Activity:</h2>
		<div style="float:left;width:50%;">
			<center>
				<div id="todays_hits_dial"></div>
				IPs Added
			</center>
		</div>
		<div style="float:right;width:50%;">
			<center>
				<div id="todays_countries_dial"></div>
				Countries Added
			</center>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END DIALS -->

	<!-- START CHARTS -->
	<div class="section">
		<div class="secleft">
			<h2>Last hits per day:</h2>
			<div id="chart_hitsperday"></div>
		</div>
		<div class="secright">
			<h2>Last hits per day by country:</h2>
			<div id="chart_countries_per_day"></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="section">
		<div class="secleft">
			<h2>Last hits averaged per hour:</h2>
			<div id="chart_hitsperhour"></div>
		</div>
		<div class="secright">
			<h2>Last hits by country averaged per hour:</h2>
			<div id="chart_countries_per_hour"></div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END CHARTS -->


	<div class="section">
		<!-- START OF LAST 5 DUPLICATES -->
		<div class="secleft">
			<h2>Top 10 IPs:</h2>

		<?php
			include_once("config.php");
			include_once("functions.php");

			$sql_total = $pdo->prepare("SELECT SUM(HITS) FROM ".$Database['tablename']);
			$sql_total->execute();
			$all_rows = $sql_total->fetchColumn();

			$sql = $pdo->prepare("
				SELECT 
					TRIM(BOTH '\"' FROM country) AS trimcountry, 
					hits,
					ipaddress
				FROM ".$Database['tablename']." 
				ORDER BY hits DESC
				LIMIT 10
			");
			$sql->execute();
			echo "
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>IP</div>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>";
			while($row = $sql->fetch(PDO::FETCH_ASSOC)){
				echo "
				<div class='div-table-row'>
					<div class='div-table-col ip' data-column='IP'>".$row['ipaddress']."</div>
					<div class='div-table-col' data-column='Country'>".$row['trimcountry']."</div>
					<div class='div-table-col center' data-column='Hits'>".number_format($row['hits'])."</div>
					<div class='div-table-col center' data-column='Percent'>".round(($row['hits'] / $all_rows * 100),2)."%</div>
				</div>";
			}
			echo "
			</div>"; // End table
		?>
		<br>
		</div> 
		<!-- END OF LAST 5 DUPLICATES -->

		<!-- START OF TOP 5 SPAMMER COUNTRIES -->
		<div class="secright">
			<h2>Top 10 countries:</h2>

		<?php
			include_once("config.php");
			include_once("functions.php");

			$sql_total = $pdo->prepare("SELECT count(*) FROM ".$Database['tablename']);
			$sql_total->execute();
			$all_rows = $sql_total->fetchColumn();

			$sql = $pdo->prepare("
				SELECT 
					TRIM(BOTH '\"' FROM country) AS trimcountry, 
					COUNT(country) AS countries
				FROM ".$Database['tablename']." 
				GROUP BY country 
				ORDER BY countries DESC
				LIMIT 10
			");
			$sql->execute();
			echo "
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col'>Country</div>
					<div class='div-table-col'>Hits</div>
					<div class='div-table-col'>Percent</div>
				</div>";
			while($row = $sql->fetch(PDO::FETCH_ASSOC)){
				echo "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'>".$row['trimcountry']."</div>
					<div class='div-table-col center' data-column='Hits'>".number_format($row['countries'])."</div>
					<div class='div-table-col center' data-column='Percent'>".round(($row['countries'] / $all_rows * 100),2)."%</div>
				</div>";
			}
			echo "
			</div>"; // End table
		?>
		<br>
		</div> <!-- End Section -->
		<!-- END OF TOP 5 SPAMMER COUNTRIES -->

		<div class="clear"></div>
	</div> <!-- END OF SECTION -->

<?php include("foot.php") ?>