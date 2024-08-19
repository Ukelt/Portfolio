<?php
include 'inc/sessionstart.php';
include 'inc/connection.php';

if(isset($_POST['quizWizardSubmit'])){
    // get the data from the form
    $quizName = $_SESSION['quizName'];
    $quizDesc = $_SESSION['quizDesc'];
    $quizStartDate = $_SESSION['quizStartDate'];
    $quizEndDate = $_SESSION['quizEndDate'];
    $quizMaxAttempts = $_SESSION['quizMaxAttempts'];
    $quizNumQuestions = $_SESSION['quizNumQuestions'];
 //   var_dump($_SESSION['course']);
    $sql = "INSERT INTO `quiz_post` (`quiz_course_id`, `quiz_name`, `quiz_desc`, `author`, `start_date`, `end_date`, `max_attempts`) 
        VALUES (".$_SESSION['course'].", '$quizName', '$quizDesc', '".$_SESSION['fullname']."', '$quizStartDate', '$quizEndDate', $quizMaxAttempts)" 
        or die(mysqli_error($conn));

 //   var_dump($sql);
    $result = mysqli_query($conn, $sql);
 //   var_dump($result);

    $quizId = mysqli_insert_id($conn);
 //   var_dump($quizId);
    // store the questions and answers in arrays
    for ($i = 1; $i <= $quizNumQuestions; $i++) {
        $question = $_POST['question'.$i];
        $answer = $_POST['answer'.$i];
    //    var_dump($question);
   //     var_dump($answer);
        $sql = "INSERT INTO `questions` (`question_quiz_id`, `question_text`) 
                VALUES ($quizId, '$question')" or die(mysqli_error($conn));
    //   var_dump($sql);
        $result = mysqli_query($conn, $sql);
   //    var_dump($result);
        $questionId = mysqli_insert_id($conn);
  //      var_dump($questionId);
        //for loop to go through the answers, if the answer is at index 0, then it is the correct answer
        for($j = 0; $j < count($answer); $j++){
      //      var_dump($j);
            $answerLoop = $answer[$j];
            if($j == 0){
                $sql = "INSERT INTO `answers` (`answer_quiz_id`, `answer_text`, `correct_answer`, `question_id`)
                VALUES ($quizId, '$answerLoop', 1, $questionId)" or die(mysqli_error($conn));
       //        var_dump($sql);
            $result = mysqli_query($conn, $sql);
        //    var_dump($result);
            }else{
            $sql = "INSERT INTO `answers` (`answer_quiz_id`, `answer_text`, `correct_answer`, `question_id`)
            VALUES ($quizId, '$answerLoop', 0, $questionId)" or die(mysqli_error($conn));
     //    var_dump($sql);
            $result = mysqli_query($conn, $sql);
     //       var_dump($result);
        }
    }

        if ($result = "11"){
     //       print "you won the special prize!! also jay is the best and most powerful! >:)";
        }
    }
    header("location: courseExtended.php?id=".$_SESSION['course']);


}
