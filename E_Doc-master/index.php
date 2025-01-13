<?php
session_start();

include("php/config.php");

if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($con,$_POST['email']);
    $password = mysqli_real_escape_string($con,$_POST['password']);

    $result = mysqli_query($con,"SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Select Error");
    $row = mysqli_fetch_assoc($result);

    if(is_array($row) && !empty($row)){
        $_SESSION['valid'] = $row['Email'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['id'] = $row['Id'];
        header("Location: welcome1.php");
    }else{
        $error_message = "<div class='message'>
            <p>Wrong Username or Password</p>
        </div> <br>";
        $back_button = "<a href='index.php'><button class='btn'>Go Back</button>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css">

    <title>Login</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/img" />
        <!-- Bootstrap Icons-->

</head>
<body style="background-color:rgba(34, 61, 102, 0.89);">
      <div class="container" style=" background-image: url('assets/img/bg-masthead.jpg'); background-size: cover;">
        <div class="box form-box">
            <?php
                if(isset($error_message)){
                    echo $error_message;
                }
                if(isset($back_button)){
                    echo $back_button;
                }
            ?>
            <?php if(!isset($_SESSION['valid'])): ?>
                <header>Login</header>
                <form action="" method="post">
                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" required>
                    </div>

                    <div class="field">
                        <input type="submit" class="btn btn-primary" name="submit" value="Login" required>
                    </div>
                    <div class="links">
                        Don't have account? <a href="register.php">Sign Up Now</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>