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
    <script src="toggle.js" defer></script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $(document).ready(function() {
            $('dropdown-toggle').dropdown()
        })
    </script>
</head>

<body>

    <?php
    include "inc/sessionstart.php";
    include "inc/connection.php";
    include "inc/permissions.php";
    //check if user is logged in
    if (!isset($_SESSION['displayName'])) {
        header("Location: index.php");
        exit();
    }
    $course = $_GET['id'];
    $_SESSION['course'] = $course;
    //query the student_course table to make sure the user is enrolled in the course
    $sql = "SELECT `course_id` FROM `student_course` WHERE `acc_id` = " . $_SESSION['id'] . "" or die(mysqli_error($conn));
    $result = mysqli_query($conn, $sql);
    // if not enrolled or does not match id of the users enrolled course, redirect
    //  var_dump($result);
    if (mysqli_num_rows($result) == 0) {
        header("Location: courseBrief.php");
        exit();
    }
    while ($row = mysqli_fetch_assoc($result)) {
        //   var_dump($row);
        if ($row['course_id'] != $course) {
            header("Location: courseBrief.php");
            exit();
        }
    }




    ?>
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="container w-75 border">
        <h4 class="mt-5 text-center">Welcome to your course!</h4>
        <?php
        //check if user has permission to view this page
        if ($_SESSION['perms'] == 'admin' || $_SESSION['perms'] == 'lecturer/staff') {
            // DROPDOWN MENU
            echo '<div class="dropdown mt-4 text-end">';
            echo '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">';
            echo 'Create New';
            echo '</button>';
            echo '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';
            echo '<li><a class="dropdown-item" href="./createPost.php?id=' . $course . '">Post</a></li>';
            echo '<li><a class="dropdown-item" href="./createQuizParams.php?id=' . $course . '">Quiz</a></li>';
            echo '</ul>';
            echo '</div>';
        }
        // Query to get the course_posts and quiz_posts from the database ordered by start date -- Copy order of fields, but select NULL as for fields that the other does not contain
        if ($_SESSION['perms'] == 'admin' || $_SESSION['perms'] == 'lecturer/staff') {
            $sql = "SELECT * FROM
            (SELECT 'course_post' as post_type, `post_id`, `post_course_id`, `type` ,NULL as `quiz_course_id`, `author`, `title`, `brief`, `start_date`, `end_date`, NULL as `quiz_id`
             FROM `course_posts`
             WHERE `post_course_id` = $course
             UNION
             SELECT 'quiz_post' as post_type, NULL as `post_id`, NULL as `post_course_id`, NULL as `type` ,`quiz_course_id`, `author`, `quiz_name`, `quiz_desc`, `start_date`, `end_date` ,`quiz_id`
             FROM `quiz_post`
             WHERE `quiz_course_id` = $course) AS posts
          ORDER BY `start_date` DESC" or die(mysqli_error($conn));
        } else {
            $sql = "SELECT * FROM
            (SELECT 'course_post' as post_type, `post_id`, `post_course_id`, `type`, NULL as `quiz_course_id`, `author`, `title`, `brief`, `start_date`, `end_date`, NULL as `quiz_id`
             FROM `course_posts`
             WHERE `post_course_id` = $course
             UNION
             SELECT 'quiz_post' as post_type, NULL as `post_id`, NULL as `post_course_id`, NULL as `type`, `quiz_course_id`, `author`, `quiz_name`, `quiz_desc`, `start_date`, `end_date` ,`quiz_id`
             FROM `quiz_post`
             WHERE `quiz_course_id` = $course) AS posts
          WHERE `start_date` <= NOW() AND `end_date` >= NOW() 
          ORDER BY `start_date` DESC" or die(mysqli_error($conn));
        }
        $result = mysqli_query($conn, $sql);
        //   var_dump($result);

        // Check if there are any posts
        $numRows = mysqli_num_rows($result);
        //loop through the results and display the data in a table


        //   var_dump($numRows);
        if ($numRows > 0) {
            // If there are posts, display them
            while ($row = mysqli_fetch_assoc($result)) {
             //   var_dump($row);
                //     var_dump($row);
                // Check if the post is a quiz or a post
                if ($row['post_type'] == 'course_post') {
                    // If it is a course post, display it
                    echo '<div class="card mt-4">';
                    echo '<div class="card-body">';
                    // if user is a student, display a button to take the quiz, else if user is a lecturer, display a button to view the quiz and a button to see results
                    if ($_SESSION['perms'] == 'lecturer/staff') {
                        // create a button with a cog icon, and a drop down, with the items of Hide/Show and Delete
                        echo '<div class="dropdown text-end float-end top-0 m-1">';
                        echo '<i class="fa fa-cogs btn btn-secondary" id="cog-dropdown" aria-hidden="true" data-bs-toggle="dropdown" aria-expanded="false">';
                        echo '</i>';
                        echo '<ul class="dropdown-menu" aria-labelledby="cog-dropdown">';
                        echo '<li><a class="dropdown-item" href="./actions/hide_show_post.php?id='.$row['post_id'].'&cid='.$row['post_course_id'].'">Hide/Show</a></li>';
                        echo '<li><a class="dropdown-item" href="./actions/delete_post.php?id='.$row['post_id'].'&cid='.$row['post_course_id'].'">Delete</a></li>';
                        echo '</ul>';
                        echo '</div>';
                    }
                    echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                    // echo the author with a muted text class
                    echo '<p class="card-text text-muted">Created By: ' . $row['author'] . '<span class="badge bg-primary text-wrap ms-2">'.$row['type'].'</span>';
                    if ($_SESSION["perms"] == "admin" || $_SESSION["perms"] == "lecturer/staff") {
                        if (strtotime($row["start_date"]) <= strtotime(date("Y-m-d")) && strtotime($row["end_date"]) >= strtotime(date("Y-m-d"))) {
                            echo '<span class="badge bg-success text-wrap ms-2">Showing</span>';
                        } else {
                            echo '<span class="badge bg-danger text-wrap ms-2">Hidden</span>';
                        }
                    }
                        '</p>';

                        // echo a container with a slightly darker background colour, smaller but bold text
                        echo '<div class="container bg-light p-2 border rounded">';
                        echo '<p class="card-text text-smaller fw-bold">' . $row['brief'] . '</p>';
                        echo '</div>';

                        // echo the start date and end date, at opposite ends of the card in a muted text class
                        echo '<div class="d-flex justify-content-between">';
                        echo '<p class="card-text text-muted">Start Date: ' . date("d/m/Y", strtotime($row['start_date'])) . '</p>';
                        echo '<p class="card-text text-muted">End Date: ' . date("d/m/Y", strtotime($row['end_date'])) . '</p>';
                        echo '</div>';
                        //view post button
                        echo '<a href="./posts/view_post.php?id=' . $row['post_id'] . '&cid=' . $row['post_course_id'] . '" class="btn btn-primary">View Post</a>';
                        echo '</div>';
                        echo '</div>';
                        

                } else {
                    // If it is a quiz post, display it
                    // Create a card with the quiz name and description, a pill badge with the post_type
                    echo '<div class="card mt-4">';
                    echo '<div class="card-body">';
                    // if user is a student, display a button to take the quiz, else if user is a lecturer, display a button to view the quiz and a button to see results
                    if ($_SESSION['perms'] == 'lecturer/staff') {
                        // create a button with a cog icon, and a drop down, with the items of Hide/Show and Delete
                        echo '<div class="dropdown text-end float-end top-0 m-1">';
                        echo '<i class="fa fa-cogs btn btn-secondary" id="cog-dropdown" aria-hidden="true" data-bs-toggle="dropdown" aria-expanded="false">';
                        echo '</i>';
                        echo '<ul class="dropdown-menu" aria-labelledby="cog-dropdown">';
                        echo '<li><a class="dropdown-item" href="./actions/hide_show_quiz.php?id='.$row['quiz_id'].'&cid='.$row['quiz_course_id'].'">Hide/Show</a></li>';
                        echo '<li><a class="dropdown-item" href="./actions/delete_quiz.php?id='.$row['quiz_id'].'&cid='.$row['quiz_course_id'].'">Delete</a></li>';
                        echo '</ul>';
                        echo '</div>';
                        echo '<a href="./quizzes/quizView.php?id=' . $row['quiz_id'] . '&cid=' . $row['quiz_course_id'] . '" class="btn btn-info float-end top-0 m-1">View Quiz</a>';
                        echo '<a href="./quizzes/results/quizResults.php?id=' . $row['quiz_id'] . '&cid=' . $row['quiz_course_id'] . '" class="btn btn-info float-end top-0 m-1">View Results</a>';
                    } else {
                        echo '<a href="./posts/quiz.php?id=' . $row['quiz_id'] . '&cid=' . $row['quiz_course_id'] . '" class="btn btn-info float-end top-0">Take Quiz</a>';
                    }
                    echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                    // echo the author with a muted text class
                    echo '<p class="card-text text-muted">Created By: ' . $row['author'] . '<span class="badge bg-primary text-wrap ms-2">Quiz</span>';
                    if ($_SESSION["perms"] == "admin" || $_SESSION["perms"] == "lecturer/staff") {
                        if (strtotime($row["start_date"]) <= strtotime(date("Y-m-d")) && strtotime($row["end_date"]) >= strtotime(date("Y-m-d"))) {
                            echo '<span class="badge bg-success text-wrap ms-2">Showing</span>';
                        } else {
                            echo '<span class="badge bg-danger text-wrap ms-2">Hidden</span>';
                        }
                    }

                        '</p>';

                        // echo a container with a slightly darker background colour, smaller but bold text
                        echo '<div class="container bg-light p-2 border rounded">';
                        echo '<p class="card-text text-smaller fw-bold">' . $row['brief'] . '</p>';
                        echo '</div>';

                        // echo the start date and end date, at opposite ends of the card in a muted text class
                        echo '<div class="d-flex justify-content-between">';
                        echo '<p class="card-text text-muted">Start Date: ' . date("d/m/Y", strtotime($row['start_date'])) . '</p>';
                        echo '<p class="card-text text-muted">End Date: ' . date("d/m/Y", strtotime($row['end_date'])) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                }
            }
        }



        ?>
    </div>
</body>