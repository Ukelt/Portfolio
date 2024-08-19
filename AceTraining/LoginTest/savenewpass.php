<?php 
include "/inc/connection.php";
include "/inc/sessionstart.php";

if(isset($_POST['newPassSubmit'])){
    $newPass = $_POST['nPassword'];

    $sql = "UPDATE `accounts` SET `password` = '$newPass' WHERE `id` = ".$_SESSION['id']."";
    $result = mysqli_query($conn, $sql);
    var_dump($sql);
    var_dump($result);

    if($result){
        header("Location: home.php");
            $_SESSION['passMsg'] = "Password successfully changed";
            exit();
    }else{
        header("Location: home.php");
        $_SESSION['passErr'] = "Failed to change password";
            exit();
    }
}
?>
