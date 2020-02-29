<?php
	session_start();


	if (isset($_SESSION['User'])) {
		include 'init.php';
		include $header;
		include $navbar;
		include $conf;
		include $functions;
		$do = isset($_GET['do']) ? $_GET['do'] : 'manage' ;
		if ($do == 'manage') {
			echo "<h1 class='text-center'>My Profile</h1>";
			$sellerid = intval($_SESSION['ID']);
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
			$stmt5 = $con->prepare('SELECT * FROM users WHERE userid=?');
			$stmt5->execute(array(intval($_SESSION['ID'])));
			$profile = $stmt5->fetch();

			?>
			<div class="container">
				<div class="myprofile alert alert-primary">
					<h2 class="text-center"><i class="fa fa-user" aria-hidden="true"></i></h2>
					<ul class="list-unstyled">
						<li><i class="fa fa-user" aria-hidden="true"></i> <?php echo "User Name : " . $profile['username']; ?></li>
						<li><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo "Email : " . $profile['email']; ?></li>
						<li><i class="fa fa-address-card" aria-hidden="true"></i> <?php echo "Full Name : " . $profile['fullname']; ?></li>
						<li><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo "Reg DATE : " . $profile['regdate']; ?></li>
					</ul>
				</div>
			</div>
<?php
			if ($count == 0 ) {
				echo '<div class="alert alert-warning" role="alert">You don\'t have any item</div>';
			}
			echo "<div class='container'>";
				echo "<div class='items row alert alert-primary'>";
				echo "<h2 class='text-center'>Items</h2>";
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
							if ($item['approve'] == 0) {
								echo "<h3 class='text-center alert alert-warning'>";
									echo "Not Approved";
								echo "</h3>";
							} 
							echo "<a class='btn btn-primary text-center' href='items.php?do=add&itemid=" . $item['itemid'] . "'>Edit</a><a class='text-center btn btn-danger' href='items.php?do=delete&id=" . $item['itemid'] . "'>Delete</a>";
						echo "</div>";
					}
				echo "</div>";
				echo "<a class='btn btn-primary text-center' href='items.php?do=add'>add</a>";
			echo "</div>";
		} elseif ($do == 'add') {
			echo "<h1 class='text-center'>";
				$pass = '?do=insert';
				$itemname = '';
				$description = '';
				$price = '';
				$counrtymade = '';
				$itemimage = '';
				$catpath = '';
				$selpath = '';
				$item = [];
				if (isset($_GET['itemid'])) {
					$id = $_GET['itemid'];
					$pass = '?do=update&id=' . $id;
					echo "<h1 class='text-center'>Edit Items</h1>";
					$stmt4 = $con->prepare("SELECT * FROM items WHERE itemid =" . $id);
					$stmt4->execute();
					$item = $stmt4->fetch();
					$itemname = 'value=' . $item['itemname'];
					$description = 'value=' . $item['description'];
					$price = 'value=' . $item['price'];
					$counrtymade = 'value=' . $item['countrymade'];
				} else {
					echo "<h1 class='text-center'>Add Items</h1>" ;
				}
			echo "</h1>"; 
				echo '<form action=' . $pass . ' method="POST">';

			?>
					<div class="form-group">
					    <label for="exampleInputEmail1">Item Name</label>
					    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required="required" name="itemname" placeholder="Item Name" <?php echo $itemname; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Description</label>
					    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="description" placeholder="Description" <?php echo $description; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Price</label>
					    <input type="text" name='price' class="form-control" required="required" placeholder="Item Price" <?php echo $price; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Country Made</label>
					    <input type="text" name='country' class="form-control" required="required" placeholder="Country where the Item made" <?php echo $counrtymade; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Status</label>
					    <select name="status" class="itemstatus form-control">
					    	<option value="0" <?php if ($item['status'] == 0) {echo "selected";} ?>>None</option>
					    	<option value="1" <?php if ($item['status'] == 1) {echo "selected";} ?>>New</option>
					    	<option value="2" <?php if ($item['status'] == 2) {echo "selected";} ?>>classy</option>
					    	<option value="3" <?php if ($item['status'] == 3) {echo "selected";} ?>>Used</option>
					    	<option value="4" <?php if ($item['status'] == 4) {echo "selected";} ?>>Old</option>
					    </select>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">The Seller</label>
					    <select name="sellername" class="itemstatus form-control">
					    	<option value="">None</option>
					    	<?php
					    		$stst5 = $con->prepare('SELECT username, userid FROM users ORDER BY userid');
					    		$stst5->execute();
					    		$countthis = $stst5->rowCount();
					    		$sellers = $stst5->fetchAll();
								foreach ($sellers as $seller) {
									echo '<option value="';
										echo $seller['userid'];
									echo '" ';
									if ($seller['userid'] == $item['memberid']) {echo "selected='selected'";}
									echo '>';
										echo $seller['username'];
									echo '</option>';
								}	    		
					    	?>
					    </select>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Category</label>
						<select name="category" class="itemstatus form-control">
					    	<option value="">None</option>
					    	<?php
					    		$stst6 = $con->prepare('SELECT name, id FROM categories ORDER BY id');
					    		$stst6->execute();
					    		$cats = $stst6->fetchAll();
								foreach ($cats as $cat) {
									echo '<option value="';
										echo $cat['id'];
									echo '" ';
									if ($cat['id'] == $item['catid']) {echo "selected='selected'";}
									echo '>';
										echo $cat['name'];
									echo '</option>';
								}	    		
					    	?>
					    </select>
					</div>
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-plus-square" aria-hidden="true"></i>
						Submit
					</button>
				</form>
				<button type="button" class="btn btn-primary anmem">
					<a href="items.php?do=manage">
						<i class="fa fa-pencil" aria-hidden="true"></i>
						Manage Categories
					</a>
				</button>

			<?php
		} elseif ($do == 'insert') {
			echo "<h1 class='text-center'>Insert Items</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$itemname = $_POST['itemname'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$country = $_POST['country'];
				$statuss = $_POST['status'];
				$sellerid = intval($_POST['sellername']);
				$category = intval($_POST['category']);

				

				$formError = [];

				if (strlen($itemname) < 4) {
					$formError[] = '<div class="alert alert-danger" role="alert">your Category name can not be less than 4 characters</div>';
				}

				foreach ($formError as $error) {
					echo $error;
				}

				if (empty($formError)) {
						$stmt7 = $con->prepare("INSERT INTO items(itemname, description, price, countrymade, adddate, `status`, catid, memberid) VALUES(:zname, :zdes, :zprice, :zcountry, now(), :zstat, :zcat, :zsell)");
						$stmt7->execute(array(
						'zname' => $itemname,
						'zdes' => $description,
						'zprice' => $price,
						'zcountry' => $country,
						'zstat' => $statuss,
						'zcat' => $category,
						'zsell' => $sellerid
						));
						$count = $stmt7->rowCount();
						if ($count > 0) {
						echo "<div class='alert alert-success'>$count Category was added</div>";
						} else {
						echo "<div class='alert alert-warning'>$count Category was added</div>";
						}
						echo "<a class='btn btn-primary text-center' href='items.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='items.php?do=manage'>Show Categories</a>";
					
				} else {
					echo "error";
				}
			

			} else {
				$message = "sorry you cant browse this page directiy";
				redirectHome($message);
			}
			echo "</div>";
		} elseif ($do == 'update') {
			echo "<h1 class='text-center'>Update Items</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = intval($_GET['id']);
				$itemname = $_POST['itemname'];
				$description = $_POST['description'];
				$price = $_POST['price'];
				$country = $_POST['country'];
				$statuss = $_POST['status'];
				$sellerid = intval($_POST['sellername']);
				$category = intval($_POST['category']);
				
				$formError = [];

				if (strlen($itemname) < 4) {
					$formError[] = '<div class="alert alert-danger" role="alert">your Item name can not be less than 4 characters</div>';
				}

				foreach ($formError as $error) {
					echo $error;
				}

				if (empty($formError)) {
						$stmt5 = $con->prepare("UPDATE `items` SET `itemname` = ?, `description` = ?, `price` = ?, `countrymade` = ?, `status` = ?, `catid` = ?, `memberid` = ? WHERE `items`.`itemid` = ?");
						$stmt5->execute(array($itemname, $description, $price, $country, $statuss, $category, $sellerid, $id));
						$count = $stmt5->rowCount();
						if ($count > 0) {
						echo "<div class='alert alert-success'>$count Item was Updated</div>";
						} else {
						echo "<div class='alert alert-warning'>$count Item was Updated</div>";
						}
						echo "<a class='btn btn-primary text-center' href='items.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='items.php?do=manage'>Show Items</a>";
					
				} 
				
			} else {
				echo '<div class="alert alert-danger" role="alert">You can\'t Navigate to this page directly</div>';
			}
				
			echo "</div>";
		} elseif ($do == 'delete') {
			echo "<h1 class='text-center'>Delete Items</h1>";
			$id = intval($_GET['itemid']);
			$stmt6 = $con->prepare("DELETE FROM items WHERE itemid = ?");
			$stmt6->execute(array($id));
			$count = $stmt6->rowCount();
			if ($count > 0) {
			echo "<div class='alert alert-success'>$count item was Deleted</div>";
			} else {
			echo "<div class='alert alert-warning'>$count item was Deleted</div>";
			}
			echo "<a class='btn btn-primary text-center' href='items.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='items.php?do=manage'>Show Categories</a>";
		}
	include $footer;
	} else {
		header('Location: admin.php');
	}
?>