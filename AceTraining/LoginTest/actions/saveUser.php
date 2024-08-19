<?php
//include connection file
include '../inc/connection.php';
//include session
include '../inc/sessionstart.php';

//
if(isset($_POST['saveUser'])) {
    //get the values from the form

    //still need to escape any characters in editable fields
    $firstname = mysqli_real_escape_string($conn, $_POST['Firstname']);
    $surname = mysqli_real_escape_string($conn, $_POST['Surname']);
    $id = $_POST['id'];
    $email = $_POST['email'];
    $role = $_POST['userRole'];
    $course = $_POST['course'];
    //get the current page from the form
    $page = $_POST['currentPage'];
    $sql = mysqli_query($conn, "SELECT `course_id` FROM `courses` WHERE `CourseName` = '$course' ") or die(mysqli_error($conn));
    $result = mysqli_fetch_all($sql);
    var_dump($sql);
    var_dump($result);
    $course_id = $result[0][0];
    //update the database and check for errors
    $sql = "UPDATE `accounts` SET `Firstname` = '$firstname', `Surname` = '$surname', `email` = '$email', `role` = '$role' WHERE `id` = '$id'"; 
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    var_dump($sql);
    var_dump($res);
    //INSERT INTO STUDENT COURSE TABLE acc_id and course_id where acc_id = $id and course is course_id of courseName from course table and on duplicate key update acc_id = $id and course_id = course_id of courseName from course table
    //if statement that inserts if id exists in student_course table and updates if it doesn't
    $sql2 = "INSERT INTO `student_course` (`std_c_id`,`acc_id`, `course_id`) VALUES (NULL ,'$id', '$course_id') ON DUPLICATE KEY UPDATE `course_id` = '$course_id'";
    var_dump($sql2);
    $res = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
    var_dump($res);
    var_dump($course_id);
    var_dump($course);
    //redirect to the page the user was on
    header("Location: $page");
    
};
?>
