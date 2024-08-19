<?php

//PERMISSIONS AND WHAT EACH PERMISSION WILL SEE ON SITE
include("inc/sessionstart.php");
include("inc/connection.php");
$permissions;
$user = $_SESSION["email"];
$sql = "SELECT * FROM accounts WHERE email = '$user'";
if($result = mysqli_query($conn, $sql)or die(mysqli_error($conn))){;
while($row = mysqli_fetch_array($result)){
    $_SESSION['perms'] = $row['role'];
    $permissions = $_SESSION['perms'];
        mysqli_free_result($result);
    }
}

if($_SESSION['perms'] == "admin"){
    echo  '<nav class="navbar navbar-expand-lg bg-dark">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand text-white" href="#">Ace Training</a>
      </div>
      <ul class="nav navbar-nav">
        <li class="nav-item  border-end border-white"><a class="nav-link text-light" href="home.php">Home</a></li>
        <li class="nav-item border-start border-end border-white"><a class="nav-link text-light" href="userrequests.php">User Registration Confirmation</a></li>
        <li class="nav-item border-start  border-white"><a class="nav-link text-light" href="usersTable.php">Admin and Users</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">';
      // create a link to the profile page with a url parameter of the user id
      echo  '<li class="nav-item"><a class="nav-link text-light" href="profile.php?id='.$_SESSION["id"].'"><i class="fa fa-user-circle"></i>Profile</a></li>
        <li class="nav-item"><a class="nav-link text-light" href="sessiondestroy.php"><i class="fa fa-sign-out"></i>Logout</a></li>
      </ul>
    </div>
  </nav>' ;
  /*  echo '
    <ul class="navbar-list">
    <li class="navbar-item"><a href="userrequests.php">User Registration</a></li>
    <li class="navbar-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="navbar-item"><a href="usersTable.php"><i class="fas fa-people-arrows"></i>Course Users</a></li>
    <li class="navbar-item"><a href="report-stats"><i class="fas fa-chart-bar"></i> Report/Stats</a></li>
    <li class="navbar-item"><a href="userlist"><i class="fas fa-address-book"></i> Userlist</a></li>
    <li style="float: right;" class="navbar-item"><a href="sessiondestroy.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    <li style="float: right;" class="navbar-item"><a href="profile"><i class="fas fa-user-circle"></i> Profile</a></li>
    </ul>
    '; */
}elseif($_SESSION['perms'] == "lecturer/staff"){
  echo  '<nav class="navbar navbar-expand-lg bg-dark">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand text-white" href="#">Ace Training</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="nav-item  border-end border-white"><a class="nav-link text-light" href="home.php">Home</a></li>
      <li class="nav-item border-start border-end border-white"><a class="nav-link text-light" href="courseBrief.php">Courses</a></li>
      <li class="nav-item border-start  border-white"><a class="nav-link text-light" href="usersTable.php">Course Users</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">';
    // create a link to the profile page with a url parameter of the user id
    echo  '<li class="nav-item"><a class="nav-link text-light" href="profile.php?id='.$_SESSION["id"].'"><i class="fa fa-user-circle"></i>Profile</a></li>
      <li class="nav-item"><a class="nav-link text-light" href="sessiondestroy.php"><i class="fa fa-sign-out"></i>Logout</a></li>
    </ul>
  </div>
</nav>' ;
}elseif($_SESSION['perms'] == "student/guest"){
  echo  '<nav class="navbar navbar-expand-lg bg-dark">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand text-white" href="home.php">Ace Training</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="nav-item  border-end border-white"><a class="nav-link text-light" href="home.php">Home</a></li>
      <li class="nav-item border-start  border-white"><a class="nav-link text-light" href="courseBrief.php">Courses</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">';
    // create a link to the profile page with a url parameter of the user id
    echo  '<li class="nav-item"><a class="nav-link text-light" href="profile.php?id='.$_SESSION["id"].'"><i class="fa fa-user-circle"></i>Profile</a></li>
      <li class="nav-item"><a class="nav-link text-light" href="sessiondestroy.php"><i class="fa fa-sign-out"></i>Logout</a></li>
    </ul>
  </div>
</nav>' ;
}
?>