<?php
session_start();
if (isset($_SESSION['Username'])) {
	header('Location: dashboard.php');
}

$title = "admin";

include 'init.php';
include $header;
include $arabic;
include $conf;



if ($_SERVER['REQUEST_METHOD'] =='POST') {
	$username = $_POST['username'];
	$ps = $_POST['password'];
	$password = sha1($ps);


	$stmt = $con->prepare("SELECT userid, username, password FROM users WHERE username = ? AND password = ? AND groupid = 1 LIMIT 1");
	$stmt->execute(array($username, $password));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();


	if ($count > 0) {
		$_SESSION['Username'] = $username;
		$_SESSION['ID'] = $row['userid'];
		header('Location: dashboard.php');
		exit();
	}
};
?>

<h1 class="text-center">
	Admins LogIn
</h1>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<input class='form-control' type='text' name='username' placeholder='Your Name'/>
	<input class='form-control' type='password' name='password' placeholder='Password'/>
	<div class="btn btn-primary sigin-div">
		<i class="fa fa-sign-in" aria-hidden="true"></i>
		<input class='sigin-but' type='submit' value='login'/>
	</div>
	<a class="btn btn-primary" href='../index.php'><i class="fa fa-users" aria-hidden="true"></i> Users In</a>
</form>



<?php

include $footer;

?>