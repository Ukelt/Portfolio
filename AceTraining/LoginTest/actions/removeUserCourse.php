<?php
include "../inc/connection.php";
include "../inc/sessionstart.php";

if (isset($_POST['userRemove'])) {
    $uId = $_POST['userRemove'];

    $sql = "DELETE FROM `student_course` WHERE `acc_id` = $uId";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        $_SESSION['sucMsg'] = "User successfully removed from course";
        exit();
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        $_SESSION['errMsg'] = "Failed to remove user from course";
        exit();
    }
}
