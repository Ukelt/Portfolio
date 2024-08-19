<?php include 'inc/sessionstart.php';
include 'inc/connection.php';
include 'views/header.php';
/*if(isset($_POST['accept'])){
     $id = $_POST['accept'];
     $_SESSION['id'] = $_POST['accept'];
    mysqli_query($conn, "UPDATE `accounts` SET `accepted` = 1 WHERE `id`=$id");
    $id2 = $_REQUEST['accept'];
};*/
if(!isset($_SESSION['displayName'])){
    header("Location: index.php");
    exit();
}


$results_per_page = 40;  

$query = "SELECT * FROM `accounts`";  
$result = mysqli_query($conn, $query);  
$number_of_result = mysqli_num_rows($result);  

$number_of_page = ceil ($number_of_result / $results_per_page);  

if (!isset ($_GET['page']) ) {  
    $page = 1;  
} else {  
    $page = $_GET['page'];  
} 

$page_first_result = ($page-1) * $results_per_page;  

?>
<body>
    <?php
    include 'inc/permissions.php';
    echo '<br>';
    $users = mysqli_query($conn, "SELECT `id`, `Firstname`, `Surname`, `email`, `role` FROM `accounts` WHERE `accepted` = 0 LIMIT ".$page_first_result.",".$results_per_page);
            echo '<div class="container border rounded-bottom bg-light">';
            echo '<table class="table table-striped table-hover text-center border">';
            echo '<tr>' ;
            echo '<th>ID</th>';
            echo '<th>Firstname</th>';
            echo '<th>Surname</th>';
            echo '<th>Email</th>';
            echo '<th>Role</th>';
            echo '<th>Course</th>';
            echo '<th>Accept</th>';
            echo '<th>Deny</th>';
            echo '</tr>';

            while ($data=mysqli_fetch_array($users)) {
                //same sql statement from usertable.php
                $query2 = "SELECT c.CourseName
        FROM accounts a
        JOIN student_course sc ON a.id = sc.acc_id
        JOIN courses c ON sc.course_id = c.course_id
        WHERE a.id = ".$data['id']."";
        $result2 = mysqli_query($conn, $query2);
        $row = mysqli_fetch_array($result2);
        $courseName = $row['CourseName'];

                echo '<form id="idform" action="actions/accept_deny_user.php"method="POST"></form>';
                echo '<tr>' ;
            echo '<td>'.$data["id"].'</td>';
            echo '<td>'.$data["Firstname"].'</td>';
            echo '<td>'.$data["Surname"].'</td>';
            echo '<td>'.$data["email"].'</td>';
            echo '<td>'.$data["role"].'</td>';
            echo '<td>'.$courseName.'</td>';
            echo "<td><button  class='btn-success btn btn-sm' form='idform' type='submit' name='accept' value='{$data['id']}'>Accept</button></td>";  
            echo "<td><button  class='btn-danger btn btn-sm' form='idform' type='submit' name='deny' value='{$data['id']}'>Deny</button></td>";
            echo '</tr>';

    
    
                    }#end of while
                    if(mysqli_num_rows($users) < 1){
                        echo '<tr><td colspan="6">No users found</td></tr>';
                    }
                    echo '</table>';
                    echo '<ul class="pagination justify-content-center">';
                    for($page = 1; $page<= $number_of_page; $page++) {  
                        echo '<li class="page-item"><a class="page-link" href = "userrequests.php?page=' . $page . '">' . $page . ' </a></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
    ?>
</body>