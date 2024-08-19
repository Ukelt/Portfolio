<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<?php

include "inc/sessionstart.php";
include "inc/connection.php";
//check if user is logged in
if (!isset($_SESSION['displayName'])) {
    header("Location: index.php");
    exit();
}
?>
<body>
    <?php
    if (!isset($_GET['id'])) {
        header("Location: #?id={$_SESSION['id']}");
        //redirect to current page with the the parameter of session id
        
        
    }
    if($_GET['id'] != $_SESSION['id']){
      //  go to the page of the session id of the user logged in

        header("Location: #?id={$_SESSION['id']}");
    }
    include "inc/permissions.php";
    $sql = "SELECT * FROM `accounts` WHERE `id` = ".$_SESSION['id']."";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $firstname = $row['Firstname'];
    $surname = $row['Surname'];
    $email = $row['email'];
    $role = $row['role'];
    $image = $row['image'];
    $fullname = $firstname . " " . $surname;

    $sql = "SELECT `course_id` FROM `student_course` WHERE `acc_id` = " . $_SESSION['id'] . "";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if($row == 0){
        $course_id = NULL;
    }else{
        $course_id = $row['course_id'];
        $sql = "SELECT `CourseName` FROM `courses` WHERE `course_id` = " . $course_id . "";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $course_name = $row['CourseName'];
    }

    ?>
    <br><br>
    <div class="border-rounded border w-50 container text-center">
    <div class="card" style="width: 18rem;">
  <img class="card-img-top" width="400px" height="300px" <?php
    if($image != 0){
        echo "src='img/profiles/{$image}'";
        }else {
            echo "src='img/profiles/default.png'";
        } ?> alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">Profile Card - <?php echo "".$fullname.""; ?></h5>
  <ul class="list-group list-group-flush text-center">
    <li class="list-group-item"><?php echo "$role"; ?></li>
    <li class="list-group-item"><?php echo "$email"; ?></li>
    <li class="list-group-item"><?php if($course_id != NULL){
        echo "$course_name";
    }else {
        echo "No Course";
    }?></li>
  </ul>
  <div class="card-body text-center">
    <a href="#" class="card-link">Card link</a>
    <a href="#" class="card-link">Another link</a>
  </div>
</div>
</body>