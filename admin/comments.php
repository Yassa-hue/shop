<?php 

session_start();


	if (isset($_SESSION['Username'])) {
		include 'init.php';
		include $header;
		include $navbar;
		include $conf;
		include $functions;
		$do = isset($_GET['do']) ? $_GET['do'] : 'manage' ;
		if ($do == 'manage') {
			echo "<h1 class='text-center'>Manage Comments</h1>";
			$stmt3 = $con->prepare("SELECT
										comments.*,
										items.itemname AS itemname,
										users.username AS usercom
									FROM
										comments
									INNER JOIN 
										items
									ON 
										items.itemid = comments.itemid
									INNER JOIN
										users
									ON
										users.userid = comments.memberid
									ORDER BY 
										items.itemname");
			$stmt3->execute();
			$comments = $stmt3->fetchAll();
			?>
			<table class="table text-center container">
				<thead class="thead-dark">
				    <tr>
				        <th scope="col">ID</th>
				        <th scope="col">Comment</th>
				        <th scope="col">Status</th>
				        <th scope="col">Date</th>
				        <th scope="col">Item</th>
				        <th scope="col">User commented</th>
				        <th scope="col">Options</th>
				    </tr>
				</thead>
				<tbody>
					<thead class="thead-light">
					    <?php  
					    	foreach ($comments as $comment) {
					    		echo "<tr>";
					    			echo '<th scope="col">' . $comment['comid'] . '</th>';
					    			echo '<th scope="col">' . $comment['comment'] . '</th>';
					    			echo '<th scope="col">';
					    				if ($comment['comstat'] == 0) {
					    					echo "Unapproved";
					    				} else {
					    					echo "Approved";
					    				}
					    			echo '</th>';
					    			echo '<th scope="col">' . $comment['comdate'] . '</th>';
					    			echo '<th scope="col">' . $comment['itemname'] . '</th>';
					    			echo '<th scope="col">' . $comment['usercom'] . '</th>';
					    			echo '<th scope="col">
								        	<a href="comments.php?do=delete&comid=' . $comment['comid'] . '" class="btn btn-danger confirm">
								        		<i class="fa fa-trash" aria-hidden="true"></i>
								        		Delete
								        	</a>';
								        	if ($comment['comstat'] == 0) {
								        		echo '<a href="comments.php?do=approve&comid=' . $comment['comid'] . '" class="btn btn-info confirm activate">
								        		<i class="fa fa-check-square" aria-hidden="true"></i>
								        		Approve
								        		</a>';
								        	}
								    echo '</th>';
					    		echo "</tr>";
					    	}

					    ?>
					 
					</thead>
				</tbody>
			</table>
			<?php
		} elseif ($do == 'approve') {
			echo "<h1 class='text-center'>Approve Items</h1>";
			$id = intval($_GET['comid']);
			$stmt6 = $con->prepare("UPDATE `comments` SET `comstat` = 1 WHERE `comments`.`comid` = ?");
			$stmt6->execute(array($id));
			$count = $stmt6->rowCount();
			if ($count > 0) {
			echo "<div class='alert alert-success'>$count Comment was Approved</div>";
			} else {
			echo "<div class='alert alert-warning'>$count comment was Approved</div>";
			}
			echo "<a class='text-center btn btn-primary' href='comments.php?do=manage'>Show Comments</a>";
		} elseif ($do == 'delete') {
			echo "<h1 class='text-center'>Delete Comments</h1>";
			$id = intval($_GET['comid']);
			$stmt6 = $con->prepare("DELETE FROM comments WHERE comid = ?");
			$stmt6->execute(array($id));
			$count = $stmt6->rowCount();
			if ($count > 0) {
			echo "<div class='alert alert-success'>$count Comment was Deleted</div>";
			} else {
			echo "<div class='alert alert-warning'>$count Comment was Deleted</div>";
			}
			echo "<a class='text-center btn btn-primary' href='comments.php?do=manage'>Show Comments</a>";
		} elseif ($do == 'add') {

		} elseif ($do == 'add') {

		} elseif ($do == 'add') {

		}

		include $footer;
	} else {
		header('Location: admin.php');
	}

?>