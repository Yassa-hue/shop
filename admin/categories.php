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
			echo "<h1 class='text-center'>Manage Categories</h1>";
			$stmt3 = $con->prepare("SELECT * FROM categories ORDER BY ordering");
			$stmt3->execute();
			$cats = $stmt3->fetchAll();
			?>
			<div class="container">
				<table class="table text-center">
					<thead class="thead-dark">
					    <tr>
					        <th scope="col">ID</th>
					        <th scope="col">Name</th>
					        <th scope="col">Description</th>
					        <th scope="col">Ordering</th>
					        <th scope="col">Visibility</th>
					        <th scope="col">Allow Comments</th>
					        <th scope="col">Allow Ads</th>
					        <th scope="col">Options</th>
					    </tr>
					</thead>
					<tbody>
						<thead class="thead-light">
						    <?php  
						    	foreach ($cats as $cat) {
						    		echo "<tr>";
						    			echo '<th scope="col">' . $cat['id'] . '</th>';
						    			echo '<th scope="col">' . $cat['name'] . '</th>';
						    			echo '<th scope="col">';
						    				if ($cat['description'] == '') {
						    					echo "Undescriped";
						    				} else {
						    					echo $cat['description'];
						    				}
						    			echo '</th>';
						    			echo '<th scope="col">';
						    				if ($cat['ordering'] == '') {
						    					echo "Unordered";
						    				} else {
						    					echo $cat['ordering'];
						    				}
						    			echo '</th>';
						    			echo '<th scope="col">'; 
						    				if ($cat['visibility'] == 1) {
						    					echo "Visible";
						    				} else {
						    					echo "Hidden";
						    				}
						    			echo '</th>';
						    			echo '<th scope="col">'; 
						    				if ($cat['allowcomments'] == 1) {
						    					echo "Allowed";
						    				} else {
						    					echo "Unallowed";
						    				}
						    			echo '</th>';
						    			echo '<th scope="col">'; 
						    				if ($cat['allowads'] == 1) {
						    					echo "Allowed";
						    				} else {
						    					echo "Unallowed";
						    				}
						    			echo '</th>';

						    			echo '<th scope="col">
						    					<a href="categories.php?do=add&id=' . $cat['id'] . '" class="btn btn-success">
						        					<i class="fa fa-pencil" aria-hidden="true"></i>
						        					Edit
						        				</a>
									        	<a href="categories.php?do=delete&id=' . $cat['id'] . '" class="btn btn-danger confirm">
									        		<i class="fa fa-trash" aria-hidden="true"></i>
									        		Delete
									        	</a>';

									    echo '</th>';
						    		echo "</tr>";
						    	}

						    ?>
						 
						</thead>
					</tbody>
				</table>
			</div>
			<div class="text-center">
				<a href="categories.php?do=add" class="btn btn-primary">
					<i class="fa fa-user-plus" aria-hidden="true"></i>
					Add Anther Category
				</a>
			</div>
			<?php
		} elseif ($do == 'add') {
			echo "<h1 class='text-center'>";
				$pass = '?do=insert';
				$name = '';
				$description = '';
				$order = '';
				if (isset($_GET['id'])) {
					$id = $_GET['id'];
					$pass = '?do=update&id=' . $id;
					echo "<h1 class='text-center'>Edit Categories</h1>";
					$stmt4 = $con->prepare("SELECT * FROM categories WHERE id =" . $id);
					$stmt4->execute();
					$categ = $stmt4->fetch();
					$name = 'value=' . $categ['name'];
					$description = 'value=' . $categ['description'];
					$order = 'value=' . $categ['ordering'];
				} else {
					echo "<h1 class='text-center'>Add Categories</h1>" ;
				}
			echo "</h1>"; 
				echo '<form action='. $pass .' method="POST">';

			?>
					<div class="form-group">
					    <label for="exampleInputEmail1">Category Name</label>
					    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required="required" name="categoryname" placeholder="Category Name" <?php echo $name; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Description</label>
					    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="description" placeholder="Description" <?php echo $description; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputEmail1">Ordering</label>
					    <input type="text" name='ordering' class="form-control" placeholder="Put number If you want order" <?php echo $order; ?>>
					</div>
					<div class="form-group">
					    <label for="exampleInputPassword1" class="vis-label">Visibility</label>
					    <div class="vis-check">
					    	<input id="vis-yes" type="radio" name="visibility" value="1" checked>
					    	<label for="vis-yes">Visible</label>
					    </div>
					    <div class="vis-check">
					    	<input id="vis-no" type="radio" name="visibility" value="0">
					    	<label for="vis-no">Hide</label>
					    </div>
					</div>
					<div class="form-group">
					    <label for="exampleInputPassword1" class="vis-label">Allow Comments</label>
					    <div class="vis-check">
					    	<input id="com-yes" type="radio" name="allowcomments" value="1" checked>
					    	<label for="com-yes">Allow</label>
					    </div>
					    <div class="vis-check">
					    	<input id="com-no" type="radio" name="allowcomments" value="0">
					    	<label for="com-no">Unallow</label>
					    </div>
					</div>
					<div class="form-group">
					    <label for="exampleInputPassword1" class="vis-label">Allow Ads</label>
					    <div class="vis-check">
					    	<input id="ads-yes" type="radio" name="allowads" value="1" checked>
					    	<label for="ads-yes">Allow</label>
					    </div>
					    <div class="vis-check">
					    	<input id="ads-no" type="radio" name="allowads" value="0">
					    	<label for="vis-no">Unallow</label>
					    </div>
					</div>
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-plus-square" aria-hidden="true"></i>
						Submit
					</button>
				</form>
				<button type="button" class="btn btn-primary anmem">
					<a href="categories.php?do=manage">
						<i class="fa fa-pencil" aria-hidden="true"></i>
						Manage Categories
					</a>
				</button>

			<?php
		} elseif ($do == 'insert') {
			echo "<h1 class='text-center'>Insert Categories</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$categoryname = $_POST['categoryname'];
				$description = $_POST['description'];
				$ordering = $_POST['ordering'];
				$visibility = $_POST['visibility'];
				$allowcomments = $_POST['allowcomments'];
				$allowads = $_POST['allowads'];

				$formError = [];

				if (strlen($categoryname) < 4) {
					$formError[] = '<div class="alert alert-danger" role="alert">your Category name can not be less than 4 characters</div>';
				}

				foreach ($formError as $error) {
					echo $error;
				}

				if (empty($formError)) {
						$stmt2 = $con->prepare("INSERT INTO categories(name, description, ordering, visibility, allowcomments, allowads) VALUES (:name, :des, :order, :vis, :com, :ads)");
						$stmt2->execute(array(
						'name' => $categoryname,
						'des' => $description,
						'order' => intval($ordering),
						'vis' => intval($visibility),
						'com' => intval($allowcomments),
						'ads' => intval($allowads)
						));
						$count = $stmt2->rowCount();
						if ($count > 0) {
						echo "<div class='alert alert-success'>$count Category was added</div>";
						} else {
						echo "<div class='alert alert-warning'>$count Category was added</div>";
						}
						echo "<a class='btn btn-primary text-center' href='categories.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='categories.php?do=manage'>Show Categories</a>";
					
				} else {
					echo "error";
				}
				

			} else {
				$message = "sorry you cant browse this page directiy";
				redirectHome($message);
			}
			echo "</div>";
		} elseif ($do == 'update') {
			echo "<h1 class='text-center'>Update Categories</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = $_GET['id'];
				$categoryname = $_POST['categoryname'];
				$description = $_POST['description'];
				$ordering = $_POST['ordering'];
				$visibility = $_POST['visibility'];
				$allowcomments = $_POST['allowcomments'];
				$allowads = $_POST['allowads'];
				
				$formError = [];

				if (strlen($categoryname) < 4) {
					$formError[] = '<div class="alert alert-danger" role="alert">your Category name can not be less than 4 characters</div>';
				}

				foreach ($formError as $error) {
					echo $error;
				}

				if (empty($formError)) {
						$stmt5 = $con->prepare("UPDATE categories SET name=?, description=?, ordering=?, visibility=?, allowcomments=? ,allowads=? WHERE id=?");
						$stmt5->execute(array($categoryname, $description, $ordering, $visibility, $allowcomments, $allowads, $id));
						$count = $stmt5->rowCount();
						if ($count > 0) {
						echo "<div class='alert alert-success'>$count Category was Updated</div>";
						} else {
						echo "<div class='alert alert-warning'>$count Category was Updated</div>";
						}
						echo "<a class='btn btn-primary text-center' href='categories.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='categories.php?do=manage'>Show Categories</a>";
					
				} 
				
			} else {
				echo '<div class="alert alert-danger" role="alert">You can\'t Navigate to this page directly</div>';
			}
				
			echo "</div>";
		} elseif ($do == 'delete') {
			echo "<h1 class='text-center'>Delete Items</h1>";
			if (isset($_GET['id'])) {
				$id = intval($_GET['id']);
				$stmt6 = $con->prepare("DELETE FROM categories WHERE id = ?");
				$stmt6->execute(array($id));
				$count = $stmt6->rowCount();
				if ($count > 0) {
				echo "<div class='alert alert-success'>$count Category was Deleted</div>";
				} else {
				echo "<div class='alert alert-warning'>$count Category was Deleted</div>";
				}
				echo "<a class='btn btn-primary text-center' href='categories.php?do=add'>Add Another one</a><a class='text-center btn btn-primary' href='categories.php?do=manage'>Show items</a>";
			} else {
				echo '<div class="alert alert-danger" role="alert">You can\'t Navigate to this page directly</div>';
			}
		}


		include $footer;
	} else {
		header('Location: admin.php');
	}

?>