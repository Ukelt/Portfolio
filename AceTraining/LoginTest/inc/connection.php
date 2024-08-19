<?php
//DB CONNECTION
$conn = mysqli_connect("localhost", "root", "root", "aceeducation");

if($conn->connect_errno > 0){
    die("Unable to Connect");
}/*else{
    echo 'successful connection';  //Testing if connection has worked
}*/

?>