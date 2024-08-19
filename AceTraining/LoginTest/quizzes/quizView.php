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
    $quizId = $_GET['id'];
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
    $sql = "SELECT * FROM `quiz_post` WHERE `quiz_id` = " . $quizId . "" or die(mysqli_error($conn));
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $quizName = $row['quiz_name'];
    $quizDesc = $row['quiz_desc'];
    $quizMaxAttempts = $row['max_attempts'];
    $quizEndDate = $row['end_date'];
    $quizAuthor = $row['author'];
    // Get the data and display the quiz name, description, max_attempts, end date and author, display in a container and a start quiz button, which will hide the container and display the questions
    // query to see how many attempts the user has made
    //check if num_rows is equal to the max attempts
    $quizEndDate = date_create($quizEndDate);
    $quizEndDate = date_format($quizEndDate, 'd/m/Y H:i:s');
    //back button on the left
    echo "<a href='javascript:window.history.back()' class='btn btn-primary mt-4 ms-4 position-absolute'><i class='bi bi-arrow-left'></i> Back</a>";

    echo "<div id='quizHeading' class='container border rounded w-75 mt-3'>";
    echo "<h1>" . $quizName . "</h1>";
    //another container
    echo "<div class='container border rounded bg-light'>";
    echo "<p>" . $quizDesc . "</p>";
    echo "</div>";
    echo "<p>Max Attempts: " . $quizMaxAttempts . "</p>";
    echo "<p'>End Date: " . $quizEndDate . "</p>";
    echo "<p>Author: " . $quizAuthor . "</p><hr>";
    echo "</div>";



    // query the quiz_questions table to get the questions
    // Retrieve the questions from the database
    $sql2 = "SELECT * FROM `questions` WHERE `question_quiz_id` = $quizId" or die(mysqli_error($conn));
    $result2 = mysqli_query($conn, $sql2);
    //make container hidden
    echo "<div class='container border rounded w-75 mt-3' id='quizContainer'>";
    ?>
    <form action="submitquiz.php" method="post">
        <input type="hidden" name="quizId" value="<?php echo $quizId; ?>">
        <input type="hidden" name="courseId" value="<?php echo $course; ?>">
    <?php
    // Display each question with its answers as radio buttons
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $question_id = $row2['question_id'];
        $question_text = $row2['question_text'];
        //container for question and answers
        echo "<div class='container border rounded bg-light mt-3'>";
        echo "<p data-questionid='$question_id'>" . $question_text . "</p>";
        //query the answers table to get the answers
        $sql3 = "SELECT * FROM `answers` WHERE `question_id` = $question_id" or die(mysqli_error($conn));
        $result3 = mysqli_query($conn, $sql3);
        //turns results 3 into an array, then shuffle
        $result3 = mysqli_fetch_all($result3, MYSQLI_ASSOC);
        shuffle($result3);
            //loop to display answers
            foreach ($result3 as $row3) {
            $qId = $row3['question_id'];
            $answer_id = $row3['answer_id'];
            $answer_text = $row3['answer_text'];
            $correctAnswer = $row3['correct_answer'];
            echo "<div class='form-check'>";
            echo "<input class='form-check-input' type='radio' data-id=".$answer_id." data-correct=".$correctAnswer." name='answer[$qId]' id='answer' value='" . $answer_id . "'>";
            echo "<label class='form-check-label' for='answer'>" . $answer_text . "</label>";

            echo "</div>";
            }
        echo "</div>";
        echo "<hr>";
    }
 ?>
    </form>
 
    </div>
</body>