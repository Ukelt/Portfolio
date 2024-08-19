<?php
include '../inc/sessionstart.php';
include '../inc/connection.php';

if (isset($_POST['postCreation'])) {
    // get the data from the form
    $type = $_POST['postType'];
    $title = $_POST['postTitle'];
    $content = $_POST['postContent'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $course_id = $_GET['id'];
    $author = $_SESSION['fullname'];
    $brief = $_POST['postBrief'];
    //check if postAttachment has a value or not
    $attachment = "";
    var_dump($_FILES['postAttachment']);
    if (isset($_FILES['postAttachment'])) {
        //print out the file information
        var_dump($_FILES['postAttachment']);
        $attachment = $_FILES['postAttachment'];
        // upload the file, then get the file name
        $target_dir = "../uploads/documents/";
        $target_file = $target_dir . basename($_FILES["postAttachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $attachment = $_FILES['postAttachment']['name'];
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["postAttachment"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (
            $imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx"
            && $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "txt"
        ) {
            echo "Sorry, only PDF, DOC, DOCX, PPT, PPTX & TXT files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        // if everything is ok, try to upload file, then get the file name
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["postAttachment"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["postAttachment"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        }
    } else {
        $attachment = "";
    }
    // insert the data into the database
    $sql = "INSERT INTO `course_posts` (`type`, `title`, `content`, `start_date`, `end_date`, `post_course_id`, `author`, `brief`, `attachment`) VALUES ('$type', '$title', '$content', '$startDate', '$endDate', '$course_id', '$author', '$brief', '$attachment')" or die(mysqli_error($conn));
    var_dump($sql);
    $result = mysqli_query($conn, $sql);
    var_dump($result);
    if ($result) {
           header("Location: ../courseExtended.php?id=$course_id&postCreation=success");
    } else {
           header("Location: ../courseExtended.php?id=$course_id&postCreation=failed");
    }
