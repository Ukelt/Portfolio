<?php include '../inc/sessionstart.php';

include '../inc/connection.php';

//$newFirstname = mysqli_real_escape_string($conn,$_POST['newFirstname']);
//$newSurname = mysqli_real_escape_string($conn,$_POST['newSurname']);
//$newPassword = mysqli_real_escape_string($conn,$_POST['newPassword']);


//$query = mysqli_query($conn,'INSERT INTO accounts (Username, Pass, email, FirstName, Surname, Accepted) VALUES ('.$newUsername.','.$newPassword.','.$newName.')');

//ESCAPE SPECIAL CHARACTERS FOR SQL STATEMENTS
if (isset($_POST['newSubmit'])) {
    $newFirstname = mysqli_real_escape_string($conn,$_POST['newFirstname']);
    $newSurname = mysqli_real_escape_string($conn,$_POST['newSurname']);
    $newPassword = mysqli_real_escape_string($conn,$_POST['newPassword']);
    $newEmail = mysqli_real_escape_string($conn,$_POST['email']);
    $requestedRole = mysqli_real_escape_string($conn,$_POST['role']) or die(mysqli_error($conn));

    //FORM VALIDATION AND INSERTION OR ERROR
    if (trim($newFirstname) != "" && trim($newSurname) != "" && trim($newPassword) != "" && trim($newEmail != "")) {
        $query = mysqli_query($conn, "SELECT * FROM `accounts` WHERE `email`='$newEmail'") or die(mysqli_error($conn));
        $numrows = mysqli_num_rows($query);
        if ($numrows == 1) {
            echo 'Email already in use';
        } else {
            $register = mysqli_query($conn,"INSERT INTO `accounts` (`Firstname`, `Pass`, `Surname`, `email`, `role`, `image`) VALUES('$newFirstname','$newPassword','$newSurname', '$newEmail', '$requestedRole', '0')" )or die(mysqli_error($conn));
            $nameQuery = mysqli_query($conn, "SELECT 'Firstname' from `accounts` WHERE `email` = '$email'") or die(mysqli_error($conn));
            $_SESSION['displayName'] = $newFirstname;
            $_SESSION['fullname'] = $newFirstname . " " . $newSurname;
            $_SESSION['message'] = "New user has not been accepted yet, wait for an admin to give you access.";
            header('Location: ../index.php?register=success?login=noaccess');
        }
    }else{
        $_SESSION['message'] = 'All fields required';
        header("Location: ../home.php?login=failure");
    }
}

?>