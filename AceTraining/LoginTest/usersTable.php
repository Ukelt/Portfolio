<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script src="editModal.js" defer></script>
</head>

<?php

include "inc/sessionstart.php";
include "inc/connection.php";
//check if user is logged in
if (!isset($_SESSION['displayName'])) {
    header("Location: index.php");
    exit();
}
?>


<body>

<?php
include 'inc/permissions.php';
$results_per_page = 10;
//GET ALL RESULTS WHERE A STUDENT DOESN'T HAVE A COURSE
//NOT EXISTS (SELECT NULL FROM `student_course` WHERE `student_course`.`acc_id` = `accounts`.`id`)
$query = "SELECT * FROM `accounts` WHERE `Accepted` = 1" or die(mysqli_error($conn));
$result = mysqli_query($conn, $query);

$number_of_result = mysqli_num_rows($result);
//GET PAGE AMOUNT FOR AMOUNT OF RESULTS

$number_of_page = ceil($number_of_result / $results_per_page);

// IF URL REQUESTS A SPECIFIC PAGE GO TO IT, DEFAULT TO 1
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
//NOT EXISTS (SELECT NULL FROM `student_course` WHERE `student_course`.`acc_id` = `accounts`.`id`) AND -- put into the query below to make it only students with no course
$page_first_result = ($page - 1) * $results_per_page;
//Get course id of user, using the user id
$courseId = mysqli_query($conn, "SELECT `course_id` FROM `student_course` WHERE `acc_id` = " . $_SESSION['id']) or die(mysqli_error($conn));
$courseId = mysqli_fetch_assoc($courseId);
$courseId = $courseId['course_id'];
if($_SESSION['perms'] == 'admin'){
$users = mysqli_query($conn, "SELECT `id`, `Firstname`, `Surname`, `email`, `role` FROM `accounts` WHERE `Accepted` = 1 ORDER BY `role` LIMIT " . $page_first_result . "," . $results_per_page) or die(mysqli_error($conn));
}else if($_SESSION['perms'] == 'lecturer/staff'){
    $users = mysqli_query($conn, "SELECT a.`id`, a.`Firstname`, a.`Surname`, a.`email`, a.`role`
    FROM `accounts` AS a
    JOIN `student_course` AS sc ON a.`id` = sc.`acc_id`
    WHERE a.`Accepted` = 1 AND sc.`course_id` = $courseId
    ORDER BY a.`role`
    LIMIT " . $page_first_result . "," . $results_per_page) or die(mysqli_error($conn));
}
?>

    <?php
    
    echo '<br>';
    //create a small search bar with a search icon in it and center it
    echo '<div class="container mb-3">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
            <div class="input-group">
                <form class="d-flex" action="search.php" method="POST">
                   
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" name="search" style="border-top-right-radius: 0;border-bottom-right-radius: 0;">
                    <span class="input-group-text rounded-left" id="basic-addon1" style="border-top-left-radius: 0;border-bottom-left-radius: 0;"><i class="bi bi-search"></i></span>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>';

if (isset($_SESSION['delErr'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['delErr'] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['delErr']);
}else if(isset($_SESSION['delMsg'])){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['delMsg'] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['delMsg']);

}
    //CREATE A TABLE OF DATA FOR ALL USERS SPLIT INTO PAGES

    echo '<div class="container border rounded-top">';
    echo '<table class="table table-striped table-hover text-center">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Firstname</th>';
    echo '<th>Surname</th>';
    echo '<th>Email</th>';
    echo '<th>Course</th>';
    echo '<th>Role</th>';
    echo '<th>Edit User</th>';
    echo '<th>' . ($_SESSION['perms'] === 'admin' ? 'Delete' : 'Remove') . '</th>'; // Display Delete if admin and remove if not
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($data = mysqli_fetch_array($users)) {
        $query2 = "SELECT c.CourseName
        FROM accounts a
        JOIN student_course sc ON a.id = sc.acc_id
        JOIN courses c ON sc.course_id = c.course_id
        WHERE a.id = ".$data['id']."";
        $result2 = mysqli_query($conn, $query2);
        $row = mysqli_fetch_array($result2);
        $courseName = $row['CourseName'];


        ?>

        
        <tr>
        <td> <?php echo $data["id"]; ?> </td>
        <td> <?php echo $data["Firstname"]; ?></td>
        <td> <?php echo $data["Surname"]; ?></td>
        <td> <?php echo $data["email"]; ?> </td>
        <td> <?php echo $data["role"]; ?></td>
        <td> <?php echo $courseName; ?></td>
        <!-- button that will get the data from the row and put it into the modal -->
        <td><button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal" data-bs-id="<?php echo $data["id"];?>" data-bs-firstname="<?php echo $data["Firstname"];?>" data-bs-surname="<?php echo $data["Surname"]; ?>" data-bs-email="<?php echo $data["email"]; ?>" data-bs-role="<?php echo $data["role"]; ?> . '">Edit User</button></td>
        <?php
        if($_SESSION['perms'] === 'admin'){
            ?>
        <td><button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserConfirm" data-bs-id="<?php echo $data["id"]; ?>">x</button></td>
        <?php 
        }else{
            ?>
            <form id="removeUser" action="actions/removeUserCourse.php"method="POST"></form>
        <td><button  class='btn-danger btn btn-sm' form='removeUser' type='Submit' name='userRemove' value='<?php echo $data["id"]; ?>'>Remove</button></td>
        <?php
        }
        ?>
        </tr>
        <?php
    } #end of while
    if(mysqli_num_rows($users) < 1){
        echo '<tr><td colspan="6">No users found</td></tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<ul class="pagination justify-content-center">';
    for ($page = 1; $page <= $number_of_page; $page++) {
        echo '<li class="page-item"><a class="page-link" href = "usersTable.php?page=' . $page . '">' . $page . ' </a></li>';
    }
    echo '</ul>';
    echo '</div>';





    /*   echo '<dialog id="editUserModal">';
    echo '<form action="actions/actions/saveUser.php" method="POST">';
    echo    '<input type="text" readonly placeholder="id" name="id" required>';
    echo    '<input type="text" placeholder="Firstname" name="Firstname" required>';
    echo    '<input type="text" placeholder="Surname" name="Surname" required>';
    echo    '<input type="text" placeholder="email" readonly name="email" required>';
    echo    '<select name="userRole" id="userRole" required>';
    echo        '<option value="student/guest">Student/Guest</option>';
    echo        '<option value="lecturer/staff">Lecturer/Staff</option>';
    echo        '<option value="admin">Admin</option>';
    echo    '</select>';
    echo    '<select name="course" required>';
    echo        '<option value="Computer Science">Computer Science</option>';
    echo        '<option value=""></option>';
    echo        '<option value=""></option>';
    echo        '<option value=""></option>';
    echo    '</select>';
    echo    '<button name="saveUser" id="saveUser" type="Submit">Save</button>';
    //get the current page
    echo    '<input type="hidden" name="currentPage" value="' . $_SERVER['REQUEST_URI'] . '">';
    echo '</form>';
    echo '</dialog>';*/
    ?>
    <br>

    <!--Buttons for opening the modals -->
    <?php
    if($_SESSION['perms'] === 'admin'){
        ?>
    <div class="container-fluid container text-center">
        <button type="button" class="btn btn-info text-center" data-bs-toggle="modal" data-bs-target="#fileUpload">
            Upload File
        </button>

        <button type="button" class="btn btn-secondary text-center" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add User
        </button>
        
        <button type="button" class="btn btn-info text-center" data-bs-toggle="modal" data-bs-target="#newCourse">
            New Course
        </button>
    </div>
    <?php
    }
    ?>



    <!--Modal for uploading a file -->
    <div class="modal fade" id="fileUpload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create records with a file</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body f-flex justify-content-center text-center">
                    <form action=" actions/uploadUsers.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="file" id="fileUserUpload" class="form-control" accept=".csv"><br>
                        *.csv files only<br><br>
                        <button name="upload" id="userUploadBtn" type="Submit" class="btn btn-success" disabled>Upload</button>
                        <?php echo '<input type="hidden" name="currentPage" value="' . $_SERVER['REQUEST_URI'] . '">'; ?>
                    </form>
                </div>
                <div class="modal-footer">                
                    <a class="btn btn-secondary" href="downloads/exampleUploadFile.txt" download>Download Example File</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

        <!--Modal for creating a course -->
        <div class="modal fade" id="newCourse" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Course Creator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body f-flex justify-content-center text-center">
                    <form action=" actions/newCourse.php" method="POST" enctype="multipart/form-data">
                        <input type="text" name="newCourseName" id="cName" class="form-control" placeholder="Course Name" required><br>
                        <input type="file" name="cImageFile" id="cImageUpload" class="form-control" accept=".jpg, .png" required><br>
                        *.jpg, .png files only - for course image<br><br>
                        <button name="newCSubmit" id="newCBtn" type="Submit" class="btn btn-success">Upload</button>
                        <?php echo '<input type="hidden" name="currentPage" value="' . $_SERVER['REQUEST_URI'] . '">'; ?>
                    </form>
                </div>
                <div class="modal-footer">                
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <!--Modal for editing the users -->
    <div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body f-flex justify-content-center text-center">
                    <form action="actions/saveUser.php" method="POST" class="form-floating">
                        <div class="form-floating">
                        <input type="text" readonly placeholder="id" name="id" id="editId" class="form-control" required>
                        <label for="editId">ID</label>
                        </div><br>
                        <div class="form-floating">
                        <input type="text" placeholder="Firstname" name="Firstname" id="editFirstName" class="form-control" required>
                        <label for="editFirstName">Firstname</label>
                        </div><br>
                        <div class="form-floating">
                        <input type="text" placeholder="Surname" name="Surname" id="editSurname" class="form-control" required>
                        <label for="editSurname">Surname</label>
                        </div><br>
                        <div class="form-floating">
                        <input type="text" placeholder="email" readonly name="email" id="editEmail" class="form-control" required>
                        <label for="editEmail">Email</label>
                        </div><br>
                        <div class="form-floating">
                        <select name="userRole" id="editRole" class="form-control" required>
                            <option value="student/guest">Student/Guest</option>
                            <option value="lecturer/staff">Lecturer/Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                        <label for="editRole">User Role</label>
                        </div><br>
                        <div class="form-floating">
                        <select name="course" class="form-control" required>
                        <?php
                                $sql = "SELECT * FROM courses";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['CourseName'] . '">' . $row['CourseName'] . '</option>';
                                }
                                ?>
                        </select>
                        <label for="editCourse">Course</label>
                        </div><br>
                        <button class="btn-success btn" name="saveUser" id="saveUser" type="Submit">Save</button>
                        <?php echo    '<input type="hidden" name="currentPage" value="' . $_SERVER['REQUEST_URI'] . '">'; ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <!--Modal for adding a user -->
    <div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create a User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body f-flex justify-content-center text-center">
                    <form action="actions/addUsers.php" method="POST" class="form-floating">
                        <div class="form-floating">
                            <input type="text" placeholder="Firstname" name="Firstname" id="newFirstName" class="form-control " required>
                            <label for="newFirstName">Firstname</label>
                        </div><br>
                        <div class="form-floating">
                            <input type="text" placeholder="Surname" name="Surname" id="newSurName" class="form-control" required>
                            <label for="newSurName">Surname</label>
                        </div><br>
                        <div class="form-floating">
                            <input type="email" placeholder="email" name="email" id="newEmail" class="form-control" required>
                            <label for="newEmail">Email</label>
                        </div><br>
                        <div class="form-floating">
                            <input type="password" placeholder="Password" name="password" id="newPassword" class="form-control" required>
                            <label for="newPassword">Password</label>
                        </div><br>
                        <div class="form-floating">
                            <select name="userRole" id="newRole" class="form-control" required>
                                <option value="student/guest">Student/Guest</option>
                                <option value="lecturer/staff">Lecturer/Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <label for="newRole">Role v</label>
                        </div><br>
                        <div class="form-floating drop-down">
                            <select name="course" id="newCourse" class="form-control" required>
                                <?php
                                $sql = "SELECT * FROM courses";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['CourseName'] . '">' . $row['CourseName'] . '</option>';
                                }
                                ?>
                            </select>
                            <label for="newCourse">Course V</label>
                        </div><br>
                        <button class="btn-success btn" name="addUser" id="addUser" type="Submit">Save</button>
                        <?php echo    '<input type="hidden" name="currentPage" value="' . $_SERVER['REQUEST_URI'] . '">'; ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

        <!--Modal for confirming user deletion -->
        <div class="modal fade" id="deleteUserConfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Warning!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body f-flex justify-content-center text-center">
                    <p>You are about to delete this user, do you want to continue?</p>
                    <form action=" actions/deleteUser.php" method="POST" enctype="multipart/form-data">                        
                        <?php echo '<input type="hidden" name="currentPage" value="' . $_SERVER['REQUEST_URI'] . '">'; ?>
                        <input type="hidden" id="delUser" name="userId" value=>
                        <button type="submit" name="userDelete" class="btn btn-primary">Delete</button>
                    </form>
                </div>
                <div class="modal-footer">                
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <?php


    //footer of the page
    echo '<footer>';
    echo    '<p>© 2020 - 2021</p>';
    echo '</footer>';

    ?>


</body>
<?php
include 'views/footer.php';
?>

</html>