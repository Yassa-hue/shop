<?php

	session_start();


	if (isset($_SESSION['Username'])) {
		include 'init.php';
		include $header;
		include $navbar;
		include $conf;
		include $functions;
		$totalmem = countitems('userid', 'users');
		?>

		<h1 class="text-center">
			Dashboard
		</h1>
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-12  text-center">
					<div class="stat">
						<i class="fa fa-users" aria-hidden="true"></i>
						<h4>Total Members</h4>
						<span><a href="members.php?do=menage"><?php echo $totalmem; ?></a></span>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 text-center">
					<div class="stat">
						<i class="fa fa-clock-o" aria-hidden="true"></i>
						<h4>Pending Members</h4>
						<span>
							<a href="members.php?do=menage&pending=yes">
								<?php 
									$con;
									$statement = $con->prepare("SELECT userid FROM users WHERE regstatus = 0");
									$statement->execute();
									$count = $statement->rowCount();
									echo $count;
								?>
									
							</a>
						</span>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12  text-center">
					<div class="stat">
						<i class="fa fa-th-large" aria-hidden="true"></i>
						<h4>Total Itenms</h4>
						<span>
							<a href="items.php?do=manage">
								<?php 
									$con;
									$statement = $con->prepare("SELECT itemid FROM items");
									$statement->execute();
									$count = $statement->rowCount();
									echo $count;
								?>
							</a>
						</span>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12  text-center">
					<div class="stat">
						<i class="fa fa-comments" aria-hidden="true"></i>
						<h4>Total Comments</h4>
						<span>
							<a href="comments.php?do=manage">
								<?php 
									$con;
									$statement = $con->prepare("SELECT comid FROM comments");
									$statement->execute();
									$count = $statement->rowCount();
									echo $count;
								?>
							</a>
						</span>
					</div>
				</div>
			</div>
		</div>





		<?php


		include $footer;
	} else {
		header('Location: admin.php');
	}


?>