<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $("#btnSubmit").click(function() {
                alert("Clicked");
            });
        });
    </script>
    </script>
    <script src="datepicker.js"></script>
</head>

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
    $course = $_GET['id'];
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


    ?>
    <br>
    <div class="container">
        <form action="createQuiz.php?id=<?php echo $course ?>" method="post">
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Quiz Name" name="quizName" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Quiz Description" name="quizDesc" required>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col form-floating">
                    <input type="date" class="form-control" placeholder="Start Date" id="quizStartDate" name="quizStartDate" min="<?= date('Y-m-d'); ?>" required>
                    <label for="quizStartDate">Start Date</label>
                </div>
                <div class="col form-floating">
                    <input type="date" class="form-control" placeholder="End Date" id="quizEndDate" name="quizEndDate" min="<?= date('Y-m-d'); ?>" required disabled>
                    <label for="quizEndDate">End Date</label>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <input type="number" class="form-control" placeholder="Quiz Max Attempts" name="quizMaxAttempts" max=10 required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="number" class="form-control" placeholder="Number of Questions" name="quizNumQuestions" min="1" required>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col">
                    <button type="submit" id="btnSubmit" class="btn btn-primary" name="createQuiz">Create Quiz</button>
                </div>
            </div>
        </form>
    </div>



</body>