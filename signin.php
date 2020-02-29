<?php 





	include 'init.php';
	include $header;
	include $conf;
	include $functions;

	$do = '';

	if (isset($_GET['do'])) {
		$do = $_GET['do'];
	} else {
		$do = 'inform';
	}

	if ($do == 'inform') { ?>
		<h1 class="text-center">Sign In</h1>
		<form action="?do=insert" method="POST">
			<div class="form-group">
			    <label for="exampleInputEmail1">User Name</label>
			    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required="required" name="username" placeholder="Member username">
			</div>
			<div class="form-group">
			    <label for="exampleInputEmail1">Password</label>
			    <input type="password" class="password form-control" id="exampleInputPassword1" aria-describedby="emailHelp" name="password" placeholder="Member Password" required="required">
			    <i class="show-pass fa fa-eye"></i>
			</div>
			<div class="form-group">
			    <label for="exampleInputEmail1">Email address</label>
			    <input type="email" required="required" name='email' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Member Email">
			</div>
			<div class="form-group">
			    <label for="exampleInputPassword1">Full Name</label>
			    <input type="text" required="required" class="form-control" name="fullname" placeholder="Member Fullname">
			</div>
			<button type="submit" class="btn btn-primary">
				<i class="fa fa-plus-square" aria-hidden="true"></i>
				Sign In
			</button>
		</form>
		<button type="button" class="btn btn-primary anmem">
			<a href="index.php">
				<i class="fa fa-pencil" aria-hidden="true"></i>
				Log In
			</a>
		</button>
	
	
<?php
	} elseif ($do == 'insert') {
		echo "<h1 class='text-center'> Insert </h1>";
		echo "<div class='container'>";
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = $_POST['username'];
			$pass = $_POST['password'];
			$password = sha1($pass);
			$email = $_POST['email'];
			$fullname = $_POST['fullname'];

			$stmt2 = $con->prepare("SELECT username FROM users WHERE username=?");
			$stmt2->execute(array($username));
			$count = $stmt2->rowCount();

			$formError = [];

			if (strlen($username) < 4) {
				$formError[] = '<div class="alert alert-danger" role="alert">your user name can not be less than 4 characters</div>';
			}
			if (strlen($username) > 20) {
				$formError[] = '<div class="alert alert-danger" role="alert">your user name can not be more than 20 characters</div>';
			}
			if (empty($pass)) {
				$formError[] = '<div class="alert alert-danger" role="alert">your password can not be empty</div>';
			}
			if (empty($email)) {
				$formError[] = '<div class="alert alert-danger" role="alert">your email can not be empty</div>';
			}
			if (empty($fullname)) {
				$formError[] = '<div class="alert alert-danger" role="alert">your full name can not be empty<div>';
			}
			if ($count > 0) {
				$formError[] = '<div class="alert alert-danger" role="alert">This username already exsists, Try useing another one<div>';
			}


			foreach ($formError as $error) {
				echo $error;
			}

			if (empty($formError)) {
					$stmt3 = $con->prepare("INSERT INTO users(username, password, email, fullname, regdate) VALUES(:uname, :pword, :mail, :fname, now())");
					$stmt3->execute(array(
					'uname' => $username,
					'pword' => $password,
					'mail' => $email,
					'fname' => $fullname,
					));
					$count = $stmt3->rowCount();
					if ($count > 0) {
					echo "<div class='alert alert-success'>You Signed in , Wait for approvement </div>";
					} else {
					echo "<div class='alert alert-warning'>There was an error with your sign in</div>";
					}
					echo "<a class='text-center btn btn-primary' href='index.php'>Log In</a>";
				
			}
			

		} else {
			$message = "sorry you cant browse this page directiy";
			redirectHome($message);
		}
		echo "</div>";
	}
	include $footer;
?>