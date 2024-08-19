<?php
include '../inc/connection.php';
//GET VALUES FROM addUserModal in usersTable.php
if(isset($_POST['addUser'])){

    //get values from addUserModal
    $firstname = mysqli_real_escape_string($conn, $_POST['Firstname']);
    $surname = mysqli_real_escape_string($conn, $_POST['Surname']);
    $email = $_POST['email'];
    $role = $_POST['userRole'];
    $course = $_POST['course'];
    $course_id;
    //get the current page from the form
    $page = $_POST['currentPage'];
    $c_id = mysqli_query($conn, "SELECT `course_id` FROM `courses` WHERE `CourseName` = '$course' ");
    //get the course_id from the c_id query
    $result = mysqli_fetch_assoc($c_id);
    var_dump($c_id);
    $course_id = $result['course_id'];
    var_dump($course_id);
     //get the password from form
     $password = $_POST['password'];
     //update the database and check for errors
     //check if user exists
     $sql = "SELECT * FROM `accounts` WHERE `email` = '$email'" or die(mysqli_error($conn));
     $result = mysqli_query($conn, $sql);
     var_dump($result);
     $row = mysqli_fetch_array($result);
     var_dump($row);
    if(!$row){
                //hash the password
  //  $password = password_hash($password, PASSWORD_DEFAULT);
  var_dump($firstname);
  var_dump($surname);
  var_dump($email);
  var_dump($role);
  var_dump($password);
  $sql = "INSERT INTO `accounts` (`id`, `Firstname`, `Surname`, `Pass` , `email`, `role`, `image`) VALUES (NULL, '$firstname', '$surname', '$password' ,'$email', '$role', '0')" or die(mysqli_error($conn));
  var_dump($sql);
  mysqli_query($conn, $sql) or die(mysqli_error($conn));
  $id = mysqli_insert_id($conn);
  var_dump($id);
    //if statement that inserts if id exists in student_course table and updates if it doesn't
    $sql2 = "INSERT INTO `student_course` (`std_c_id`, `acc_id`, `course_id`) VALUES (NULL, '$id', '$course_id')" or die(mysqli_error($conn));
    $res = mysqli_query($conn, $sql2);
    var_dump($sql2);
    var_dump($res);
    //redirect to the page the user was on
    header("Location: $page");
    }else{
                //if user exists redirect with error
                header("Location: $page?error=userExists");
    }
}
