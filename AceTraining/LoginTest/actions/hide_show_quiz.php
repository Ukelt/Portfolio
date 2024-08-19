<?php 
    include '../inc/sessionstart.php';
    include '../inc/connection.php';
    $quiz_id = $_GET['id'];
    $course_id = $_GET['cid'];  
    //check if post is currently hidden or showing using the start and end_date
    $sql = "SELECT `start_date`, `end_date` FROM `quiz_post` WHERE quiz_id = '$post_id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);
    $start_date = $row['start_date'];
    $end_date = $row['end_date'];
    var_dump($start_date);
    var_dump($end_date);
    var_dump($sql);
    var_dump($result);
    //Only get year, month and day from date
    $start_date = substr($start_date, 0, 10);
    $end_date = substr($end_date, 0, 10);

    // check if the post is currently hidden or visible
    if($start_date <= date("Y-m-d m:s:i") && $end_date >= date("Y-m-d m:s:i")){
        echo "2Start date is before today and end date is after today";
        echo "start_date = $start_date and end_date = $end_date";
        echo "start_date <= ".date('Y-m-d')." && end_date >= ".date('Y-m-d')."";
        //hide post
        $date = "2000-01-01";
        $sql = "UPDATE `quiz_post` SET `end_date` = '$date' WHERE quiz_id = '$quiz_id'";
        $result = mysqli_query($conn, $sql);
        var_dump($sql);
        var_dump($result);
        
        if ($result) {
          header("Location: ../courseExtended.php?id=$course_id&change=success");
        } else {
          header("Location: ../courseExtended.php?id=$course_id&change=failed");
        }
    }
    else{
        // show post
        $date = "2030-01-01";
        $sql = "UPDATE `quiz_post` SET `end_date` = '$date' WHERE quiz_id = '$quiz_id'";
        $result = mysqli_query($conn, $sql);
        var_dump($sql);
        var_dump($result);
        
        // check if start date is after today
        if($start_date > date("Y-m-d m:s:i")){
            // set start date to today
            $date = date("Y-m-d");
            $sql = "UPDATE `quiz_post` SET `start_date` = '$date' WHERE quiz_id = '$quiz_id'";
            $result = mysqli_query($conn, $sql);
            var_dump($sql);
            var_dump($result);
        }
        
        if ($result) {
          header("Location: ../courseExtended.php?id=$course_id&change=success");
        } else {
          header("Location: ../courseExtended.php?id=$course_id&change=failed");
        }
    }
    
?>
