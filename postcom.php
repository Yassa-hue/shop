<?php 
session_start();


	if (isset($_SESSION['User'])) {
		
		include 'init.php';
		include $header;
		include $navbar;
		include $conf;
		include $functions;

		$itemid = $_GET['itemid'];
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo $_POST['comment'];	
			$stms = $con->prepare('INSERT INTO `comments` (`comid`, `comment`, `comstat`, `comdate`, `itemid`, `memberid`) VALUES (NULL, :tcom, "1", 	now(), :titem, :tuser)');
			$stms->execute(array('tcom' => $_POST['comment'], 'titem' => intval($itemid), 'tuser' => $_SESSION['ID']));
			header('Location: buy.php?itemid=' . $itemid);

		} else {
			header('Location: buy.php?itemid=' . $itemid);
		}

		include $footer;
	} else {
		header('Location: admin.php');
	}



?>