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
    <body>
        <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['message'] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        unset($_SESSION['message']);
    }
    ?>
        <div class="position-absolute top-50 start-50 translate-middle w-50 bg-secondary">
        <div class="container border text-center w-100">
            <br>
            <img src="./img/Logo/Ace Training Image.png" alt="Ace Education Logo" class="text-light" height="150">
            <hr>
            <form action="actions/InsertDetails.php" method="POST">
                <div class="form-floating mb-3 w-50 mx-auto">
                    <input type="text" name="newFirstname" class="form-control input-sm" id="floatingFname" placeholder="Firstname">
                    <label for="floatingFname">Firstname</label>
                </div>
                <div class="form-floating w-50 mb-3 mx-auto">
                    <input type="text" name="newSurname" class="form-control input-sm" id="floatingSname" placeholder="Password">
                    <label for="floatingSname">Surname</label>
                </div>
                <div class="form-floating w-50 mb-3 mx-auto">
                    <input type="password" name="newPassword" class="form-control input-sm" id="floatingPassword">
                    <label for="floatingPassword">Password</label>
                </div>
                <div class="form-floating w-50 mb-3 mx-auto">
                    <input type="email" class="form-control input-sm" name="email" placeholder="example@example.com" id="newEmail">
                    <label for="newEmail">Email</label>
                </div>
                <div>
                    <select name="role" class="form-select w-50 mb-3 mx-auto" aria-label="Default select example">
                        <option selected value="3">Student</option>
                        <option value="2">Staff</option>
                    </select>
                </div>
                <button class="btn btn-primary" type="submit" name="newSubmit" value="newAccount">Register</button>
            </form>
            <hr>
            <button type="button" class="btn bg-info">
                <a href="index.php" class="text-white text-decoration-none">Already have an account</a>
            </button>
            <br><br>
        </div>
    </div>

<!--    <form action="actions/InsertDetails.php" method="POST">
        <input type="text" name="newFirstname" placeholder="FirstName">
        <input type="text" name="newSurname" placeholder="Surname">
        <input type="password" name="newPassword" placeholder="Password">
        <input type="email" name="email" placeholder="example@example.com">
        <select name="role">
            <option value="3">Student</option>
            <option value="2">Staff</option>
        </select>
        <button type="submit" name="newSubmit" value="newAccount">Register</button>
    </form>

    <a href="index.php">Already have an Account?</a> -->
</body>

</html>