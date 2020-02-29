<?php
	$dsn = 'mysql:host=localhost;dbname=shop'; //data source name 
	$user = 'root';
	$pass = '';
	$options = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' , 
	);

	try {
		
		$con = new PDO($dsn, $user, $pass,$options);

	}


	catch(PDOException $e) {
		
		echo "failed " . $e->getMessage();

	}


?>
