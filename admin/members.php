<?php

	session_start();


	if (isset($_SESSION['Username'])) {
		include 'init.php';
		include $header;
		include $navbar;
		include $conf;
		include $functions;

		$do = '';

		if (isset($_GET['do'])) {
			$do = $_GET['do'];
		} else {
			$do = 'menage';
		}

		if ($do == 'home') {
			echo 'wellcome in home page';
		} elseif ($do == 'menage') { 
			$pending = '';
			if (isset($_GET['pending']) && $_GET['pending'] == 'yes') {
				$pending = 'AND regstatus = 0';
			}

			$stmt = $con->prepare("SELECT * FROM users WHERE groupid != 1 " . $pending);
			$stmt->execute();
			$rows = $stmt->fetchAll();
			?>
			<h1 class="text-center">
				Menage Membres
			</h1>
			<div class="container">
				<table class="table text-center">
					<thead class="thead-dark">
					    <tr>
					        <th scope="col">ID</th>
					        <th scope="col">UserName</th>
					        <th scope="col">Email</th>
					        <th scope="col">FullName</th>
					        <th scope="col">TrustStatus</th>
					        <th scope="col">RegStatus</th>
					        <th scope="col">Regdate</th>
					        <th scope="col">Options</th>
					    </tr>
					</thead>
					<tbody>
						<thead class="thead-light">
						    <?php  
						    	foreach ($rows as $row) {
						    		echo "<tr>";
						    			echo '<th scope="col">' . $row['userid'] . '</th>';
						    			echo '<th scope="col">' . $row['username'] . '</th>';
						    			echo '<th scope="col">' . $row['email'] . '</th>';
						    			echo '<th scope="col">' . $row['fullname'] . '</th>';
						    			echo '<th scope="col">' . $row['truststatus'] . '</th>';
						    			echo '<th scope="col">' . $row['regstatus'] . '</th>';
						    			echo '<th scope="col">' . $row['regdate'] . '</th>';
						    			echo '<th scope="col">
						    					<a href="members.php?do=edit&userid=' . $row['userid'] . '" class="btn btn-success">
						        					<i class="fa fa-pencil" aria-hidden="true"></i>
						        					Edit
						        				</a>
									        	<a href="members.php?do=delete&userid=' . $row['userid'] . '" class="btn btn-danger confirm">
									        		<i class="fa fa-trash" aria-hidden="true"></i>
									        		Delete
									        	</a>';
									        	if ($row['regstatus'] == 0) {
									        		echo '<a href="members.php?do=activate&userid=' . $row['userid'] . '" class="btn btn-info confirm activate">
									        		<i class="fa fa-check-square" aria-hidden="true"></i>
									        		Activate
									        		</a>';
									        	}

									    echo '</th>';
						    		echo "</tr>";
						    	}

						    ?>
						 
						</thead>
					</tbody>
				</table>
			</div>
			<div class="text-center">
				<a href="members.php?do=add" class="btn btn-primary">
					<i class="fa fa-user-plus" aria-hidden="true"></i>
					Add Anther Member
				</a>
			</div>

			


		<?php
		}elseif ($do == 'activate') {
			echo "<h1 class='text-center'>Activate</h1>";
			$userid = intval($_GET['userid']);
			$stmt = $con->prepare("UPDATE users SET regstatus = 1 WHERE userid = ?");
			$stmt->execute(array($userid));
			$count = $stmt->rowCount();
			if ($count > 0) {
				echo "<div class='alert alert-success'>$count Member was Activated</div>";
			} else {
				echo "<div class='alert alert-warning'>$count Member was Activated</div>";
			}
			echo "<a class='btn btn-primary text-center' href='members.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='members.php?do=menage'>Show Members</a>";
		} elseif ($do =='delete') {
			echo "<h1 class='text-center'>Delete</h1>";
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : $userid = 0; 
			$stmt = $con->prepare("DELETE FROM users WHERE userid = ?");
			$stmt->execute(array($userid));
			$count = $stmt->rowCount();
			if ($count > 0) {
				echo "<div class='alert alert-success'>$count Member was Deleted</div>";
			} else {
				echo "<div class='alert alert-warning'>$count Member was Deleted</div>";
			}
			echo "<a class='btn btn-primary text-center' href='members.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='members.php?do=menage'>Show Members</a>";


		} elseif ($do == 'add') { ?>
		
			<h1 class="text-center">Add Member</h1>

				<form action="?do=insert" method="POST">
					<div class="form-group">
					    <label for="exampleInputEmail1">User Name</label>
					    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required="required" name="username" placeholder="Member username">
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Password</label>
					    <input type="password" class="password form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="password" placeholder="Member Password" required="required">
					    <i class="show-pass fa fa-eye"></i>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Email address</label>
					    <input type="email" required="required" name='email' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Member Email">
					</div>
					<div class="form-group">
					    <label for="exampleInputPassword1">Full Name</label>
					    <input type="text" required="required" class="form-control" id="exampleInputPassword1" name="fullname" placeholder="Member Fullname">
					</div>
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-plus-square" aria-hidden="true"></i>
						Add
					</button>
				</form>
				<button type="button" class="btn btn-primary anmem">
					<a href="members.php?do=menage">
						<i class="fa fa-pencil" aria-hidden="true"></i>
						Manage Membre
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

				foreach ($formError as $error) {
					echo $error;
				}

				if (empty($formError)) {
						$stmt = $con->prepare("INSERT INTO users(username, password, email, fullname, regdate, regstatus) VALUES(:uname, :pword, :mail, :fname, now(), 1)");
						$stmt->execute(array(
						'uname' => $username,
						'pword' => $password,
						'mail' => $email,
						'fname' => $fullname,
						));
						$count = $stmt->rowCount();
						if ($count > 0) {
						echo "<div class='alert alert-success'>$count Member was added</div>";
						} else {
						echo "<div class='alert alert-warning'>$count Member was added</div>";
						}
						echo "<a class='btn btn-primary text-center' href='members.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='members.php?do=menage'>Show Members</a>";
					
				}
				

			} else {
				$message = "sorry you cant browse this page directiy";
				redirectHome($message);
			}
			echo "</div>";


		} elseif ($do == 'edit') {  
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : $userid = 0;
			$stmt = $con->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0) 
			{
			
			?>

				<h1 class="text-center">Edit Member</h1>

				<form action="?do=update" method="POST">
					<input type="hidden" name="userid" value=' <?php echo $userid ?>'>
					<input type="hidden" name="oldpass" value=' <?php echo $row['password'] ?>'>
					<div class="form-group">
					    <label for="exampleInputEmail1">User Name</label>
					    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required="required" name="username" value=<?php echo $row['username']; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Password</label>
					    <input type="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="newpass" placeholder="leave it empty if you don't wanna change">
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Email address</label>
					    <input type="email" required="required" name='email' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value=<?php echo $row['email']; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputPassword1">Full Name</label>
					    <input type="text" required="required" class="form-control" id="exampleInputPassword1" name="fullname" value=<?php echo $row['fullname']; ?>>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>

		<?php 
			
			} else {
				echo "sorry, there is no such id";
			}
		} elseif ($do=='update') {
			echo "<h1 class='text-center'> Update </h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$userid = $_POST['userid'];
				$username = $_POST['username'];
				$email = $_POST['email'];
				$fullname = $_POST['fullname'];
				$password = '';
				if (empty($_POST['newpass'])) {
					$password = $_POST['oldpass'];
				} else {
					$password = sha1($_POST['newpass']);
				}

				$formError = [];

				if (strlen($username) < 4) {
					$formError[] = '<div class="alert alert-danger" role="alert">your user name can not be less than 4 characters</div>';
				}
				if (strlen($username) > 20) {
					$formError[] = '<div class="alert alert-danger" role="alert">your user name can not be more than 20 characters</div>';
				}
				if (empty($email)) {
					$formError[] = '<div class="alert alert-danger" role="alert">your email can not be empty</div>';
				}
				if (empty($fullname)) {
					$formError[] = '<div class="alert alert-danger" role="alert">your full name can not be empty<div>';
				}

				foreach ($formError as $error) {
					echo $error;
				}

				if (empty($formError)) {
					$stmt = $con->prepare("UPDATE users SET username=?, email=?, fullname=?, password=? WHERE userid=?");
					$stmt->execute(array($username, $email, $fullname, $password, $userid));
					echo '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . ' record updated</div>';
				}
				

			} else {
				echo "sorry you cant browse this page this is insert page";
			}
			echo "</div>";
		} else {
			header('Location: members.php');
		}

		
		include $footer;
	} else {
		header('Location: admin.php');
	}


?>