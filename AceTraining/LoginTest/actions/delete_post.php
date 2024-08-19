<?php 
    include '../inc/sessionstart.php';
    include '../inc/connection.php';
    $post_id = $_GET['id'];
    $course_id = $_GET['cid'];    
    // delete quiz
    $sql = "DELETE FROM `course_posts` WHERE post_id = '$post_id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    
    if ($result) {
      header("Location: ../courseExtended.php?id=$course_id&quizDeletion=success");
    } else {
      header("Location: ../courseExtended.php?id=$course_id&quizDeletion=failed");
    }
    
?>