<?php
session_start();


	if (isset($_SESSION['User'])) {
		
		include 'init.php';
		include $header;
		include $navbar;
		include $conf;
		include $functions;

		$itemid = $_GET['itemid'];


		$stms2 = $con->prepare('SELECT
									items.*,
									users.username,
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
									items.itemid = ?');
		$stms2->execute(array($itemid));
		$item = $stms2->fetch();

		?>
		<h1 class="text-center"><?php echo $item['name']; ?> Category</h1>
		<div class="container">
			<div class="row">
				<div class="alert alert-primary buy col-md-4 col-sm-12">
					<h3><?php echo $item['itemname']; ?></h3>
					<p><h3>Description </h3><?php echo $item['description']; ?></p>
					<ul class="list-unstyled text-center">
						<li>Presented by <?php echo $item['username']; ?></li>
						<li>Made in <?php echo $item['countrymade']; ?></li>
						<li>Added in <?php echo $item['adddate']; ?></li>
						<li>Price <?php echo $item['price']; ?></li>
						<li>Status 
							<?php 
							$status = array('New', 'Classy', 'Used', 'Old');
							echo $status[intval($item['status'])];
							?>
						</li>
					</ul>
				</div>
				<div class="comment col-md-8">
					<form action="<?php echo 'postcom.php?itemid=' . $item['itemid'];?>" method="POST">
						<div class="form-group">
						    <textarea placeholder="leave your comment" autocomplete="no" class="form-control" name="comment"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-plus-square" aria-hidden="true"></i>
							Post
						</button>
					</form>
					<div class="comitem">
						<?php 
							$stmt = $con->prepare("SELECT
														comments.*,
														users.username
													FROM 
														comments
													INNER JOIN
														users
													ON
														users.userid = comments.memberid
													WHERE
														comments.itemid = ?");
							$stmt->execute(array($item['itemid']));
							$comments = $stmt->fetchAll();
							$count = $stmt->rowCount();
							if ($count > 0) {
								foreach ($comments as $comment) {
									echo '<div>';
										echo '<h3 font-weight-bold>' . $comment['username'] . "</h3>";
										echo "<span>" . $comment['comdate'] . "</span>";
										echo "<p>" . $comment['comment'] . "</p>";
									echo "</div>";
								}

							} else {
								echo "There is no comments for this item";
							}

						?>
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