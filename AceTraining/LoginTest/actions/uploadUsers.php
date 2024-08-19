<?php
include '../inc/connection.php';
//parse file from fileModal in usersTable.php
if(isset($_POST['upload'])){
    //get the current page
    if(isset($POST_['currentPage'])){
        $currentPage = $_POST['currentPage'];
    }else
    {
        $currentPage = 1;
    }
    //get the file -- this is the file from the fileModal
    $file = $_FILES['file'];
    //check if the file is empty
    if(empty($file)){
        header("Location: ../usersTable.php?page=".$currentPage);
        $_SESSION['fileErr'] = "No file selected";
        exit();
    }
    //get the file name -- this is the name of the file that the user uploads
    $fileName = $_FILES['file']['name'];
    //get the file temp name -- this is where the file is stored on the server
    $fileTmpName = $_FILES['file']['tmp_name'];
    //get the file size -- not used
    $fileSize = $_FILES['file']['size'];
    //get the file error -- if there is one
    $fileError = $_FILES['file']['error'];
    //get the file type -- not used
    $fileType = $_FILES['file']['type'];
    //get the file extension -- explode the file name by the . and get the last element
    $fileExt = explode('.', $fileName);
    //get the file extension in lowercase
    $fileActualExt = strtolower(end($fileExt));
    //allowed file extensions
    $allowed = array('csv');
    //check if the file extension is allowed
    if(in_array($fileActualExt, $allowed)){
        //check if there are no errors
        if($fileError === 0){
            //check if the file size is less than 1MB
            if($fileSize < 1000000){
                //create a unique id for the file -- this is the name of the file that will be stored on the server
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                //set the file destination -- this is where the file will be stored on the server
                $fileDestination = '../uploads/'.$fileNameNew;
                //move the file to the destination
                move_uploaded_file($fileTmpName, $fileDestination);
                //open the file
                $file = fopen($fileDestination, "r");
                //loop through the file
                //read the file line by line
                while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
                    //get the values from the file
                    $Firstname = $column[0];
                    $Surname = $column[1];
                    $userRole = $column[2];
                    $email = $column[3];
                    $accepted = $column[4];
                    $image = $column[5];
                    //check if the email is already in the database -- 
                    $sql = "SELECT * FROM `accounts` WHERE email = '$email'" or die(mysqli_error($conn));
                    $result = mysqli_query($conn, $sql);
                    $resultCheck = mysqli_num_rows($result);
                    //if the email is not in the database then insert the values into the database
                    if($resultCheck < 1){
                        //hash the password
                   //     $hashedPwd = password_hash($email, PASSWORD_DEFAULT);
                        //insert the values into the database
                        $passWord = "password";
                        $sql = "INSERT INTO `accounts` ( `id`,`Firstname`, `Surname`, `email`, `Pass`, `role`, `Accepted`, `image`) VALUES (NULL , '$Firstname', '$Surname', '$email', '$passWord','$userRole', '$accepted', $image)";
                        $result = mysqli_query($conn, $sql);
                        if(!$result){
                            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                        }
}
                    //if the email is in the database
                    else{
                        //redirect to the page the user was on with an error
                        header("Location: ../usersTable.php?page=$currentPage?error=userExists");
                    }
                }
                //close the file
                fclose($file);
                //redirect to the page the user was on
                header("Location: ../usersTable.php?page=$currentPage");
            }
            //if the file size is too big
            else{
                //redirect to the page the user was on
                header("Location: ../usersTable.php?page=$currentPage&error=filesize");
            }
        }
        //if there is an error
        else{
            //redirect to the page the user was on
            header("Location: ../usersTable.php?page=$currentPage&error=uploaderror");
        }
    }
    //if the file extension is not allowed
    else{
        //redirect to the page the user was on
        header("Location: ../usersTable.php?page=$currentPage&error=invalidfile");
    }
}
?>