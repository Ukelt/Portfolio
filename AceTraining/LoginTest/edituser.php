//Depreciated for now, not in use!
<?php
include 'inc/sessionstart.php';
include 'inc/connection.php';
include 'views/header.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
}else if(isset($_POST['edituser'])){
     $id = $_POST['accept'];
}
?>
<body>
<?php
include 'inc/permissions.php';


?>

</body>
