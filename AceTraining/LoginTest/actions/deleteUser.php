<?php 
include "../inc/connection.php";
include "../inc/sessionstart.php";

if(isset($_POST['userDelete'])){
    $uId = $_POST['userId'];
    //query to delete user from accounts
    $sql = "DELETE FROM `accounts` WHERE `id` = $uId";
    $result = mysqli_query($conn, $sql);
    var_dump($sql);
    var_dump($result);

    if($result){
        header("Location: " . $_SERVER['HTTP_REFERER']);
            $_SESSION['delMsg'] = "User successfully deleted";
            exit();
    }else{
        header("Location: " . $_SERVER['HTTP_REFERER']);
        $_SESSION['delErr'] = "Failed to delete user";
            exit();
    }
}

?>