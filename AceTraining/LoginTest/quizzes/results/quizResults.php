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
    //include the permissions, connection and sessionstart files, ensuring that their paths are correcr
    include_once '../../inc/permissions_twostep.php';
    include_once '../../inc/connection.php';
    include_once '../../inc/sessionStart.php';
    //check if user is logged in
    if (!isset($_SESSION['displayName'])) {
        header("Location: index.php");
        exit();
    }
    echo "<a href='javascript:window.history.back()' class='btn btn-primary mt-4 ms-4 position-absolute'><i class='bi bi-arrow-left'></i> Back</a>";

    //GET quizId from URL and attempt 
    $quizId = $_GET['id'];
    $courseId = $_GET['cid'];
    $sql = "SELECT sc.`acc_id`, a.`role`
    FROM `student_course` sc
    JOIN `accounts` a ON sc.`acc_id` = a.`id`
    WHERE sc.`course_id` = '$courseId'";

$result = mysqli_query($conn, $sql);
//var_dump($result);
// filter out users with permission level other than "student" or "guest"
$allowedUserIds = array();
while ($row = mysqli_fetch_assoc($result)) {
if ($row['role'] == 'student/guest') {
    $allowedUserIds[] = $row['acc_id'];
}
}
    echo "<div class='container border rounded w-75 mt-4'>";
    echo "<table class='table table-striped table-hover table-center text-center'>";
    echo "<tr><th>User ID</th><th>Name</th><th>Highest Score</th><th>Attempts</th></tr>";
    
    foreach ($allowedUserIds as $userId) {
        // fetch quiz result and highest attempts for the user
        $sql = "SELECT MAX(`score`) AS `score`, MAX(`attempt_num`) AS `attempts` FROM `quiz_attempt` WHERE `quiz_id` = '$quizId' AND `user_id` = $userId" or die(mysqli_error($conn));
     //   var_dump($sql);
        
        $result = mysqli_query($conn, $sql);
       // var_dump($result);
      //  var_dump(mysqli_fetch_assoc($result));
        $sql2 = "SELECT COUNT(question_id) AS `totalQuestions` FROM `questions` WHERE `question_quiz_id` = '$quizId'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $totalQuestions = $row2['totalQuestions'];
        //check if rows return NULL
        $sql3 = "SELECT `Firstname`, `Surname` FROM `accounts` WHERE `id` = '$userId'";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);
        $firstname = $row3['Firstname'];
        $lastname = $row3['Surname'];
    
        // check if user has taken the quiz
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        //    var_dump($row);
            $score = $row['score'];
            $attempts = $row['attempts'];
            if ($row['score'] === null && $row['attempts'] === null) {
                // display a message if no one has attempted the quiz
                $score = 0;
                $attempts = 0;
            }
        } else {
            $score = 0;
            $attempts = 0;
        }
     //   var_dump($score);
     //   var_dump($attempts);
        // display the user's result and attempts in the table
        echo "<tr><td>$userId</td><td>$firstname $lastname</td><td>$score/$totalQuestions</td><td>$attempts</td></tr>";
    }
    
    echo "</table>";


