<?php include '../inc/sessionstart.php';
require_once '../inc/connection.php';

//$username = $_POST['username'];
//$userpass = $_POST['password'];

/*if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $userpass = $_POST['password'];
    
     if(empty($username || empty($userpass))){  --Validation when needed
        echo 'Enter your login details.'
    } else
    $query = "SELECT * from login WHERE Username='$username'";
    $result = mysqli_query($con, $query);
} */
 /* Checks email and password 
 */
//NEED TO ESCAPE SPECIAL CHARACTERS - NOT DONE YET, CHECK EMAIL AND PASSWORD AGAINST DB, SEND TO SITE OR ERROR
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (trim($email) != "" && trim($password) != "") {
        $query = mysqli_query($conn, "SELECT * FROM `accounts` WHERE `email`='$email' AND `Pass`='$password'" ) or die(mysqli_error($conn));
        $numrows = mysqli_num_rows($query);
        if ($numrows > 0) {
            echo 'Login Worked';
            $name = mysqli_query($conn, "SELECT `id`, `Firstname`, `role`, `accepted` from `accounts` WHERE `email` = '$email'" ) or die(mysqli_error($conn));
            while($row = mysqli_fetch_array($name)){
                $_SESSION['id'] = $row['id'];
                $_SESSION['displayName'] = $row['Firstname'];
                $_SESSION['fullname'] = $row['Firstname'] . " " . $row['Surname'];
                $_SESSION['perms'] = $row['role'];
                $_SESSION['accepted'] = $row['accepted'];
                $_SESSION['email'] = $row['email'];
            };
            if($_SESSION['accepted'] != 0){
                if(strtolower($password) != 'password'){
                    header("Location: ../home.php?login=success");
                    exit();
                }else{
            header("Location: ../passwordchange.php?login=success");
            exit();
                }
            }else{
                $_SESSION['message'] = "Account does not have access";
                header("Location: ../index.php?login=noaccess");
                exit();
            }
        } else {
            $_SESSION['message'] = 'Incorrect Email or Password';
            header("Location: ../index.php?login=failure");
            exit();
        }
    }
}
