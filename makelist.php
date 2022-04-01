<?php

	$Database = array (
		'host'        => 'localhost',
		'username'    => 'geoip',
		'password'    => 'nnPCGiO3DhddUeJm',
		'dbname'      => 'geoip',
		'tablename'   => 'geolocations',
		'driver'      => 'mysql',
		'port'        => '3306',
		'dsn'         => 'MariaDB ODBC 3.0 Driver'
	);

	If ($Database['driver'] == 'mysql') {
		$pdo = new PDO("mysql:host=".$Database['host'].";port=".$Database['port'].";dbname=".$Database['dbname'], $Database['username'], $Database['password']);
	} ElseIf ($Database['driver'] == 'odbc') {
		$pdo = new PDO("odbc:Driver={".$Database['dsn']."};Server=".$Database['host'].";Port=".$Database['port'].";Database=".$Database['dbname'].";User=".$Database['username'].";Password=".$Database['password'].";");
	} Else {
		echo "Configuration Error - No database driver specified";
	}

	$sql = $pdo->prepare("SELECT * FROM ".$Database['tablename']." ORDER BY country_name ASC");
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "'".$row['country_code']."' => '".$row['country_name']."',<br>";
	}

?>