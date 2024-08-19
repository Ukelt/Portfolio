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
    <script src="../script/startquiz.js"></script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

<body>

    <?php
    include "../inc/sessionstart.php";
    include "../inc/connection.php";
    include "../inc/permissions_onestep.php";
    //check if user is logged in
    if (!isset($_SESSION['displayName'])) {
        header("Location: index.php");
        exit();
    }
    $postId = $_GET['id'];
    $course = $_GET['cid'];
    //query the student_course table to make sure the user is enrolled in the course
    $sql = "SELECT `course_id` FROM `student_course` WHERE `acc_id` = " . $_SESSION['id'] . "" or die(mysqli_error($conn));
    $result = mysqli_query($conn, $sql);
    // if not enrolled or does not match id of the users enrolled course, redirect
    //  var_dump($result);
    if (mysqli_num_rows($result) == 0) {
        header("Location: courseBrief.php");
        exit();
    }
    while ($row = mysqli_fetch_assoc($result)) {
        //   var_dump($row);
        if ($row['course_id'] != $course) {
            header("Location: courseBrief.php");
            exit();
        }
    }
    // query the quiz table to get the quiz name
    $sql = "SELECT * FROM `course_posts` WHERE `post_id` = " . $postId . "" or die(mysqli_error($conn));
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $type = $row['type'];
    $title = $row['title'];
    $content = $row['content'];
    $start_date = $row['start_date'];
    $end_date = $row['end_date'];
    $author = $row['author'];
    $attachment = $row['attachment'];
    $start_date = date_create($start_date);
    $start_date = date_format($start_date, 'd/m/Y H:i:s');
    $end_date = date_create($end_date);
    $end_date = date_format($end_date, 'd/m/Y H:i:s');
    echo "<a href='javascript:window.history.back()' class='btn btn-primary mt-4 ms-4 position-absolute'><i class='bi bi-arrow-left'></i> Back</a>";

    
        echo "<div id='quizHeading' class='container border rounded w-75 mt-3'>";
        echo "<p class='text-wrap card-text'><h1>".$title."</h1></p>";
        echo "<p class='text-muted'>Author: $author </p>";
        echo "<div class='container border rounded bg-light p-3'>";
        echo '<p class="text-muted">'.$type.'</p>';
        echo '<div class="container bg-light p-2 border rounded">';
        echo '<p class="card-text text-smaller fw-bold">' . $row['content'] . '<br>
        <a href="../uploads/documents/'.$attachment.'">'.$attachment.'</a></p>';
        echo '</div>';
        echo '<div class="d-flex justify-content-between">';
        echo '<p class="card-text text-muted">Start Date: ' . date("d/m/Y", strtotime($row['start_date'])) . '</p>';
        echo '<p class="card-text text-muted">End Date: ' . date("d/m/Y", strtotime($row['end_date'])) . '</p>';
        echo '</div>';
        echo '</div><br>';
        echo '</div>';  
                ?>

</body>