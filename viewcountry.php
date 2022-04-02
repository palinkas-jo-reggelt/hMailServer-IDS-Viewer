<?php include("head.php") ?>

<?php
	include_once("config.php");
	include_once("functions.php");

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		$display_pagination = 1;
	} else {
		$page = 1;
		$total_pages = 1;
		$display_pagination = 0;
	}
	if (isset($_GET['search'])) {
		$search = $_GET['search'];
		$search_SQL = "WHERE country LIKE '%".$search."%'";
		$search_ph = $search;
		$search_hidden = "<input type='hidden' name='search' value='".$search."'>";
		$search_page = "&search=".$search;
	} else {
		$search = "";
		$search_SQL = "";
		$search_ph = "";
		$search_hidden = "";
		$search_page = "";
	}
	if (isset($_GET['clear'])) {
		header("Location: country.php");
	}
	
	if (isset($_GET['sort1'])) {
		$sort1_val = $_GET['sort1'];
		$sort1_page = "&sort1=".$sort1_val;
		$sort1_hidden = "<input type='hidden' name='sort1' value='".$sort1_val."'>";
		if ($_GET['sort1'] == "hitsasc") {$sort1_sql = "sumhits ASC"; $sort1_ph = "&#8593; Hits";}
		else if ($_GET['sort1'] == "hitsdesc") {$sort1_sql = "sumhits DESC"; $sort1_ph = "&#8595; Hits";}
		else if ($_GET['sort1'] == "newest") {$sort1_sql = "maxts ASC"; $sort1_ph = "&#8593; Date";}
		else if ($_GET['sort1'] == "oldest") {$sort1_sql = "maxts DESC"; $sort1_ph = "&#8595; Date";}
		else if ($_GET['sort1'] == "countryasc") {$sort1_sql = "trimcountry ASC"; $sort1_ph = "&#8593; Country";}
		else if ($_GET['sort1'] == "countrydesc") {$sort1_sql = "trimcountry DESC"; $sort1_ph = "&#8595; Country";}
		else {unset($_GET['sort1']); $sort1_sql = ""; $sort1_ph = "Sort";}
	} else {
		$sort1_val = "";
		$sort1_sql = "";
		$sort1_ph = "Sort";
		$sort1_page = "";
		$sort1_hidden = "";
	}
	if ((isset($_GET['sort1'])) || (isset($_GET['sort2']))) {
		$orderby = "ORDER BY ";
	} else {
		$orderby = "ORDER BY trimcountry ASC";
	}


	echo "
	<div class='section'>
		<div style='line-height:24px;display:inline-block;'>
			<form autocomplete='off' action='viewcountry.php' method='GET'>
				<select name='sort1' onchange='this.form.submit()'>
					<option value='".$sort1_val."'>".$sort1_ph."</option>
					<option value='hitsasc'>&#8593; Hits</option>
					<option value='hitsdesc'>&#8595; Hits</option>
					<option value='newest'>&#8593; Date</option>
					<option value='oldest'>&#8595; Date</option>
					<option value='countryasc'>&#8593; Country</option>
					<option value='countrydesc'>&#8595; Country</option>
				</select>
				".$search_hidden."
			</form>";
	
	echo "
			<form autocomplete='off' action='viewcountry.php' method='GET'><br>
				<input type='text' size='20' id='autocomplete' name='search' placeholder='Search Country...' value='".$search_ph."'>
				<input type='submit' value='Search'>
				<button class='button' type='submit' name='clear'>Reset</button>
				".$sort1_hidden."
			</form>
		</div>
	</div>
	
	<div class='section'>";


	$offset = ($page-1) * $no_of_records_per_page;
	
	$total_pages_sql = $pdo->prepare("
		SELECT COUNT(DISTINCT(TRIM(BOTH '\"' FROM country))) AS count 
		FROM ".$Database['tablename']." 
		".$search_SQL."
	");
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql = $pdo->prepare("
		SELECT 
			TRIM(BOTH '\"' FROM country) AS trimcountry, 
			SUM(hits) AS sumhits, 
			MAX(timestamp) AS maxts 
		FROM ".$Database['tablename']." 
		".$search_SQL."
		GROUP BY trimcountry
		".$orderby.$sort1_sql."
		LIMIT ".$offset.", ".$no_of_records_per_page
	);
	$sql->execute();

	if ($search==""){
		$search_res="";
	} else {
		$search_res=" for \"<b>".$search."</b>\"";
	}

	if ($total_pages < 2){
		$pagination = "";
	} else {
		$pagination = "(Page: ".number_format($page)." of ".number_format($total_pages).")";
	}

	if ($total_rows == 1){$singular = '';} else {$singular= 's';}
	if ($total_rows == 0){
		if ($search == "" && $sort1_val == ""){
			echo "Please enter a search term";
		} else {
			echo "No results ".$search_res;
		}	
	} else {
		echo "
		<span style='font-size:0.8em;'>Results ".$search_res.": ".number_format($total_rows)." Record".$singular." ".$pagination."<br></span>
		<div class='div-table'>
			<div class='div-table-row-header'>
				<div class='div-table-col'>Country</div>
				<div class='div-table-col'>Hits</div>
				<div class='div-table-col'>Last</div>
			</div>";
		
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			echo "
			<div class='div-table-row'>
				<div class='div-table-col mobile-bold' data-column='Country'>".$row['trimcountry']."</div>
				<div class='div-table-col center' data-column='Hits'>".number_format($row['sumhits'])."</div>
				<div class='div-table-col center' data-column='Last'>".date("y/m/d H:i:s", strtotime($row['maxts']))."</div>
			</div>";
		}
		echo "
		</div>"; // End table

		if ($total_pages == 1){
			echo "";
		} else {
			echo "
		<span class='nav'>
			<ul>
				";
			if($page <= 1){echo "<li>First</li>";} else {echo "<li><a href=\"?page=1".$search_page.$sort1_page."\">First</a><li>";}
			if($page <= 1){echo "<li>Prev</li>";} else {echo "<li><a href=\"?page=".($page - 1).$search_page.$sort1_page."\">Prev</a></li>";}
			if($page >= $total_pages){echo "<li>Next</li>";} else {echo "<li><a href=\"?page=".($page + 1).$search_page.$sort1_page."\">Next</a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?page=".$total_pages.$search_page.$sort1_page."\">Last</a></li>";}
			echo "
			</ul>
		</span>";
		}
	}

	// JS autocomplete
	echo "
	<script>
	$(function() {
		$('#autocomplete').autocomplete({
			source: 'autocomplete.php',
			select: function( event, ui ) {
				event.preventDefault();
				$('#autocomplete').val(ui.item.value);
			}
		});
	});
	</script>";

?>

	</div> <!-- end of section -->

<?php include("foot.php") ?>