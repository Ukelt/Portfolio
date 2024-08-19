<?php
include 'inc/sessionstart.php';
include 'inc/connection.php';

//IF USER IS ACCEPTED, CHANGE THEIR PERMISSIONS TO BE ABLE TO ACCESS THE SITE IF NOT, DELETE THE ACCOUNT FROM DB
if(isset($_POST['accept'])){
     $id = $_POST['accept'];
    mysqli_query($conn, "UPDATE `accounts` SET `accepted` = 1 WHERE `id`=$id");
    $id2 = $_REQUEST['accept'];
}elseif(isset($_POST['deny'])){
    $id = $_POST['deny'];
    mysqli_query($conn, "DELETE FROM `accounts` WHERE `id`=$id");
};
header("Location: userrequests.php")
?>