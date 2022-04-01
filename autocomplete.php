<?php
	include_once("config.php");
	include_once("functions.php");

	$search = $_GET['term'];

	$count_sql = $pdo->prepare("SELECT COUNT(*) FROM ".$Database['tablename']." WHERE country LIKE '%".$search."%' OR ipaddress LIKE '%".$search."%'");
	$count_sql->execute();
	$count = $count_sql->fetchColumn();

	$all_sql = $pdo->prepare("SELECT COUNT(*) FROM ".$Database['tablename']);
	$all_sql->execute();
	$countall = $all_sql->fetchColumn();

	$sql = $pdo->prepare("
		SELECT DISTINCT(TRIM(BOTH '\"' FROM country)) AS result FROM ".$Database['tablename']." WHERE country LIKE '%".$search."%' 
		UNION
		SELECT ipaddress AS result FROM ".$Database['tablename']." WHERE ipaddress LIKE '%".$search."%' 
	"); 
	$sql->execute();

	$arr = array(); 
	if (($count > 0) && ($count != $countall)) {

		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$data['value'] = $row['result'];
			array_push($arr, $data);
		} 
	}
	echo json_encode($arr);
?>