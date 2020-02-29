<?php

	session_start();


	if (isset($_SESSION['User'])) {
		
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
<?php
		
		$stms = $con->prepare('SELECT * FROM categories');
		$stms->execute();
		$categories = $stms->fetchAll();
		$stms2 = $con->prepare('SELECT
									items.*,
									users.username
								FROM
									items
								INNER JOIN
									users
								ON
									users.userid = items.memberid
								WHERE 
									items.approve = 1');
		$stms2->execute();
		$items = $stms2->fetchAll();
		


		echo "<div class='container'>";
		


		foreach ($categories as $category) {
			
			echo "<div class='category'>";
				echo "<h2 class='text-center'>" . $category['name'] . "</h2>";
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
						if ($category['id'] == $item['catid']) {
							echo "<div class='item text-center col-md-4 col-xm-12'>";
								echo "<h3 class='text-center'><a href='buy.php?itemid=" . $item['itemid'] . "'>" . $item['itemname'] . "</a></h3>";
								echo "<p>" . $item['description'] . "</p>";
								echo $status;
								echo "<h2 class='text-center price'>" . $item['price'] . "</h2>";
								echo "<h3 class='seller'>Presented by <a href='shop.php?sellerid=" . $item['memberid'] . "&sellername=" . $item['username'] . "'>" . $item['username'] . "</a></h3>";
							echo "</div>";
						}
					}
				echo "</div>";
			echo "</div>";	
			
		}

		echo "</div>";


		include $footer;
	} else {
		header('Location: admin.php');
	}


?>