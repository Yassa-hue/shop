
<?php  

include 'init.php';
include $conf;

$stat = $con->prepare('SELECT name ,id FROM categories');
$stat->execute();
$cats = $stat->fetchAll();


?>






<nav class="navbar navbar-expand-lg navbar-light bg-light text-center">
  <a class="navbar-brand" href="dashboard.php">Yassa</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
<?php
      foreach ($cats as $cat) {
        echo '<li class="nav-item"><a class="nav-link" href="cats.php?id=' . $cat['id'] . '">' . $cat['name'] . '</a></li>';
      }
      
?>     
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Account
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['ID'] ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit</a>
          <a class="dropdown-item" href="items.php"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>