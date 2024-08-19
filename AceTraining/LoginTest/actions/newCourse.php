<?php
include "../inc/connection.php";
include "../inc/sessionstart.php";
if (isset($_POST['newCSubmit'])) {
    // Get the form data
    $currentPage = $_POST['currentPage'];
    $newCourseName = $_POST['newCourseName'];

    // Process the uploaded file
    $file = $_FILES['cImageFile'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];

    // Where to place image
    $uploadDirectory = '../img/course/';
    // Move the uploaded file to the desired location
    $destination = $uploadDirectory . $fileName;
    move_uploaded_file($fileTmpName, $destination);

    //Add coursename and image filename to db
    $sql = "INSERT INTO `courses` (`CourseName`, `image`) VALUES ('$newCourseName', '$fileName')";
    $result = mysqli_query($conn, $sql);
    var_dump($sql);
    var_dump($result);

    // Redirect back to the previous page
    header('Location: ' . $currentPage);
    exit();
}
?>
