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
    if(!isset($_POST['submitQuiz'])){
        header("Location: ../courseExtended.php?id=".$_SESSION['course']."");
        exit();
    }

// Get values of the questions and answers
// Get the quiz id
$quizId = $_POST['quizId'];
// Get the course id
$courseId = $_POST['courseId'];
// Get the answers
$answers = $_POST['answer'];
//var_dump($answers);
$sql5 = "SELECT COUNT(DISTINCT `attempt_num`) FROM `quiz_attempt` WHERE `user_id` = '".$_SESSION['id']."' AND `quiz_id` = '$quizId'";
$result5 = mysqli_query($conn, $sql5);
//var_dump($result5);  
//var_dump(mysqli_num_rows($result5));
if(mysqli_num_rows($result5) == 0) {
    // No matching records found, set attempt_num to 1
    $attempt_num = 1;
} else {
    // get the amount of different attempts
    $row5 = mysqli_fetch_assoc($result5);
  //  var_dump($row5);
    $attempt_num = $row5['COUNT(DISTINCT `attempt_num`)'] + 1;
}
// Insert the quiz results into the database


//var_dump($attempt_num);

$score = 0;
echo '<div class="container mt-2 position-relative">';
//loop through answers

foreach ($answers as $answer) {
//get the question of the answer
$sql = "SELECT * FROM `answers` WHERE `answer_id` = $answer" or die(mysqli_error($conn));
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$question = $row['question_id'];
$answerId = $row['answer_id'];
//get the text of the question
$sql2 = "SELECT * FROM `questions` WHERE `question_id` = $question" or die(mysqli_error($conn));
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$questionText = $row2['question_text'];
//container for question and answer
echo '<div class="container w-75 border rounded">';
//echo the question
echo "<br><p>Question: \n";
echo $questionText .'</p><hr>';
//check the answer id against the correct answer
$sql3 = "SELECT * FROM `answers` WHERE `answer_id` = $answer" or die(mysqli_error($conn));
$result3 = mysqli_query($conn, $sql3);
$row = mysqli_fetch_assoc($result3);
$answerText = $row['answer_text'];
$correctAnswer = $row['correct_answer'];
$sql4 = "INSERT INTO `quiz_student_answers` (student_id, course_id, quiz_id, question_id , answer_id, attempt_num) VALUES ('".$_SESSION['id']."', '$courseId', '$quizId', '$question', '$answerId', '$attempt_num')" or die(mysqli_error($conn));
$result4 = mysqli_query($conn, $sql4);

//if the answer is correct, add 1 to the score
if ($correctAnswer == 1) {
    //echo the answer w/ a bootstrap tick next to a radio button with their answer
    echo '<i class="fa fa-check"></i> <input type="radio" checked>' . $answerText . '<br>';

 //   echo '<i class="fa fa-check"></i> ' . $answerText . ' ';
    $score++;
}else{
    //echo the a cross with the answer they inputted
    echo '<i class="fa fa-times"></i> ' . $answerText . '<br>';
}
echo '<hr></div>';
}
echo '<div class="position-absolute top-0 left-50" id="score">';
echo '<br><p>Score: </p>';
echo $score . '/' . count($answers);
echo '</div>';
echo '</div>';
$sql6 = "INSERT INTO `quiz_attempt` (user_id, quiz_id, attempt_num, score, date_submitted) VALUES (".$_SESSION['id'].", ".$quizId.", ".$attempt_num.", ".$score.", now())";
$result6 = mysqli_query($conn, $sql6);
//var_dump($sql6);
//var_dump($result6);
header('location: ./displayScore.php?id='.$quizId.'&attempt='.$attempt_num.'')



?>
