<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-0oXjKpyJg8nV1sGQ1rK3+qdfYJ05TdwfB4Q4xqX3tXd/Dm8fK85tJIZL0DNNpJNYKlWTa0Kio5B5h9X5Hwx+tw==" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-Ha4W8L0fKbTuNUo3St7kRyW36NF1Cv/Tc5M8Cx6UTN0W+5PvO62P8hJQ/vszh1x1" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-0JmCr44p9XeIJWc1+0sTqxJr3l3qT8Ll0+b/mHaJxRZp9X9FSlcW1uDnix1rbsQ3q3dA8YDJnTvkTvn+Eg6krA==" crossorigin="anonymous"></script>
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
    <script src="selection.js"></script>
    <script src="datepicker.js"></script>
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
    <br>
    <div class="container w-50">
        <div class="row">
            <div class="col">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Headings</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">Timings</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">Content</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                        <br>
                        <form action="actions/postCreation.php?id=<?php echo $course ?>" method="POST" enctype="multipart/form-data">
                            <!-- Tab 1 content here -->
                            <div class="form-floating mb-3">

                                <input type="text" class="form-control" id="floatingInput" name="postAuthor" <?php if (isset($_SESSION['fullname'])) {
                                                                                                echo 'value=' . $_SESSION['fullname'] . '';
                                                                                            } ?> disabled>
                                <label for="floatingInput">Author</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingTitle" name="postTitle" placeholder="Post Title" required>
                                <label for="floatingTitle">Title</label>
                            </div><br>
                            <div class="form-floating">
                                <select class="form-select" id="floatingSelect" name="postType" aria-label="Floating label select example" placeholder="Please select a post type" required>
                                    <option selected disabled hidden value="">Please select a post type</option>
                                    <option value="Post">Post</option>
                                    <option value="Document">Document</option>
                                </select>
                                <label for="floatingSelect">Post Type</label>
                            </div>
                    </div>
                    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <!-- Tab 2 content here -->
                            <div class="input-group date">
                                <div class="col form-floating">
                                    <input type="date" class="form-control" placeholder="End Date" id="quizEndDate" name="endDate" min="<?= date('Y-m-d'); ?>" required disabled>
                                    <label for="quizEndDate">End Date</label>
                                </div>
                                <div class="col form-floating">
                                    <input type="date" class="form-control" placeholder="Start Date" id="quizStartDate" name="startDate" min="<?= date('Y-m-d'); ?>" required>
                                    <label for="quizStartDate">Start Date</label>
                                </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                        <!-- Tab 3 content here -->
                        <div class="form-floating">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="description" name="postBrief" placeholder="Description"></textarea>
                                <label for="description">Description</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="content" name="postContent" placeholder="Content" style="height: 100px;" required></textarea>
                                <label for="content" required>Content</label>
                            </div>

                        </div>
                        <hr>
                        <div class="input-group mb-3" id="documentUpload">
                            <input type="file" class="form-control" id="inputGroupFile02" name="postAttachment">
                            <label class="input-group-text" for="inputGroupFile02">Upload</label>
                        </div>
                        <button type="submit" class="btn btn-primary" id="postSubmit" name="postCreation" disabled>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>