<?php 
    include '../inc/sessionstart.php';
    include '../inc/connection.php';
    $quiz_id = $_GET['id'];
    $course_id = $_GET['cid'];    
    // delete quiz
    $sql = "DELETE FROM `quiz_post` WHERE quiz_id = '$quiz_id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    
    if ($result) {
      header("Location: ../courseExtended.php?id=$course_id&quizDeletion=success");
    } else {
      header("Location: ../courseExtended.php?id=$course_id&quizDeletion=failed");
    }
    
?>