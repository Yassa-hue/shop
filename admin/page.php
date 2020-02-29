<?php


function redirectHome($errorMsge, $seconds = 3){
	echo "<div class='alert alert-danger'>$errorMsge</div>";
	echo "<div class='alert alert-info'>You Will be redirected to Home page after $seconds seconds</div>";
	header('Refresh:$seconds,url=admin.php');
	exit();
};



function checkItem($slct, $frm, $value) {
	global $con;
	$statement = $con->prepare("SELECT $slct FROM $frm WHERE $slct = ?");
	$statement->execute(array($value));
	$count = $statement->rowCount();
	return $count;
};


function countitems($item, $table) {
	global $con;
	$stmt2 = $con->prepare("SELECT COUNT($item) AS totalmem FROM $table");
	$stmt2->execute();
	$rtrn = $stmt2->fetch();
	return $rtrn['totalmem'];
};


?>