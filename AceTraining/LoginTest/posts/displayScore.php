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
    //GET quizId from URL and attempt 
    $quizId = $_GET['id'];
    $attempt = $_GET['attempt'];

    $sql = "SELECT
    qs.quiz_id,
    qs.question_id,
    qs.answer_id,
    q.question_text,
    a.answer_text,
    a.correct_answer
  FROM
    quiz_student_answers qs
    JOIN questions q ON qs.question_id = q.question_id AND qs.quiz_id = q.question_quiz_id
    JOIN answers a ON qs.answer_id = a.answer_id AND qs.question_id = a.question_id AND qs.quiz_id = a.answer_quiz_id
  WHERE
    qs.quiz_id = '$quizId' AND qs.attempt_num = '$attempt' AND qs.student_id = " . $_SESSION['id'] . "" or die(mysqli_error($conn));
    // var_dump($sql);
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);
    //  var_dump($result);
    // get the amount of questions in the quiz
    $sql2 = "SELECT * FROM questions WHERE question_quiz_id = '$quizId'";
    $result2 = mysqli_query($conn, $sql2);
    $rows2 = mysqli_num_rows($result2);
    $score = 0;
    echo "<div class='container w-75 position-relative'>";

    while ($row = mysqli_fetch_assoc($result)) {
        $questionId = $row['question_id'];
        $questionText = $row['question_text'];
        $answerId = $row['answer_id'];
        $correctAnswer = $row['correct_answer'];
        $answerText = $row['answer_text'];
        /*    var_dump($questionId);
        var_dump($questionText);
        var_dump($answerId);
        var_dump($correctAnswer);
        var_dump($answerText);*/
        // Create a container, then a container within that container for the question and answer, the question and answer will be separated by a horitzonal rule
        echo "<div class='container border rounded mt-3 mb-3 w-75'>";
        echo "<br><p>Question: \n";
        echo $questionText . '</p><hr>';
        if ($correctAnswer == 1) {
            //echo the answer w/ a bootstrap tick next to a radio button with their answer
            echo '<p><i class="fa fa-check"></i> '/*<input type="radio" checked>*/ . $answerText . '</p>';

            //   echo '<i class="fa fa-check"></i> ' . $answerText . ' ';
            $score++;
        } else {
            //echo the a cross with the answer they inputted
            echo '<p><i class="fa fa-times"></i> ' . $answerText . '</p>';
        }
        echo "</div>";
    }
    ?>
    <div class="position-absolute top-50 start-0" style="background-color: #007bff; padding: 10px; border-radius: 5px; color: #fff;">
        <span style="font-size: 20px; font-weight: bold;">Score:</span>
        <span style="font-size: 24px; font-weight: 500;"><?php echo $score . '/' . $rows2; ?></span>
    </div>
    </div>
