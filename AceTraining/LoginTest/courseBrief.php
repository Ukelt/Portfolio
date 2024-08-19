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

<body>
    <?php
    include "inc/sessionstart.php";
    include "inc/connection.php";
    include "inc/permissions.php";
    //check if user is logged in
    if (!isset($_SESSION['displayName'])) {
        header("Location: index.php");
        exit();
    }
    echo '<br><br><div class="container border w-75">';
    $sql = "SELECT `course_id` FROM `student_course` WHERE `acc_id` = " . $_SESSION['id'] . "";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) < 1) {
        echo '<h4>Nothing to see here, you are not registered, come back later after you register<h4>';
    } else {
        $row = mysqli_fetch_array($result);

        $course_id = $row['course_id'];
        $sql = "SELECT `CourseName`,`image` FROM `courses` WHERE `course_id` = $course_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        $courseName = $row['CourseName'];
        $courseImage = $row['image'];
      //  var_dump($courseImage);

        // check if courseImage is null, if so default to default.png
        if ($courseImage == NULL) {
            $courseImage = "default.png";
        }

        echo '<div class="card" style="width: 18rem">
        <img class="card-img-top" src="img/course/' . $courseImage . '" alt="Course Image - ' . $courseName . '">
        <div class="card-body">
            <h5 class="card-title">' . $courseName . '</h5>
            <p class="card-text">Start your ' . $courseName . ' course now!</p>
            <a href="courseExtended.php?id='.$course_id.'" class="btn btn-primary">View Course >></a>
        </div>
      </div>
      </div>';
    }
    ?>
</body>