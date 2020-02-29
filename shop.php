<?php 

session_start();


	if (isset($_SESSION['User'])) {
		
		include 'init.php';
		include $header;
		include $navbar;
		include $conf;
		include $functions;

		echo "<h1 class='text-center'>" . $_GET['sellername'] . " Shop</h1>";
		$sellerid = intval($_GET['sellerid']);
		$stms2 = $con->prepare('SELECT
									items.* ,
									users.username ,
									categories.name
								FROM
									items
								INNER JOIN
									users
								ON
									users.userid = items.memberid
								INNER JOIN
									categories
								ON
									categories.id = items.catid
								
								WHERE
									items.memberid = ?');
		$stms2->execute(array($sellerid));
		$items = $stms2->fetchAll();
		$count = $stms2->rowCount();
		echo $count;
		echo "<div class='container'>";
			echo "<div class='items row'>";
				foreach ($items as $item) {
					$status = '';
					if ($item['status'] == 1) {
						$status = '<span class="New">New</span>';
					} elseif ($item['status'] == 2) {
						$status = '<span class="Classy">Classy</span>';
					} elseif ($item['status'] == 3) {
						$status = '<span class="Used">Used</span>';
					} elseif ($item['status'] == 4) {
						$status = '<span class="Old">Old</span>';
					}
					echo "<div class='item text-center col-md-4 col-xm-12'>";
						echo "<h3 class='text-center'>" . $item['itemname'] . "</h3>";
						echo "<p>" . $item['description'] . "</p>";
						echo $status;
						echo "<h2 class='text-center price'>" . $item['price'] . "</h2>";
						echo "<h3 class='text-center'>" . $item['name'] . " Category</h3>";
					echo "</div>";
				}
			echo "</div>";
		echo "</div>";
		include $footer;
	} else {
		header('Location: admin.php');
	}



?>