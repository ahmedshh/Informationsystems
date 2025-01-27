<?php
session_start();
include("php/config.php");

// Function to verify PBKDF2-hashed passwords
function verify_pbkdf2_password($password, $hashed_password) {
    // Ensure the hash starts with 'pbkdf2:sha256'
    if (!str_starts_with($hashed_password, 'pbkdf2:sha256:')) {
        throw new Exception("Invalid password hash format.");
    }

    // Remove the prefix and split the rest
    $hash_body = substr($hashed_password, strlen('pbkdf2:sha256:'));
    $parts = explode('$', $hash_body);

    // Ensure the remaining parts are valid
    if (count($parts) !== 3) {
        throw new Exception("Invalid password hash format.");
    }

    // Extract components
    $iterations = (int) $parts[0]; // Convert iterations to an integer
    $salt = $parts[1];
    $stored_hash = $parts[2];

    // Calculate the hash using PBKDF2
    $calculated_hash = hash_pbkdf2("sha256", $password, $salt, $iterations, 64);

    // Compare the calculated hash with the stored hash
    return hash_equals($calculated_hash, $stored_hash);
}

if (isset($_POST['submit'])) {
    // Sanitize and trim the email
    $email = trim(mysqli_real_escape_string($con, $_POST['email'] ?? ''));
    $password = mysqli_real_escape_string($con, $_POST['password'] ?? '');

    echo "Debug: Entered email - $email<br>";

    if (!empty($email) && !empty($password)) {
        // Debugging: Print the query being executed
        $query = "SELECT * FROM doctors WHERE Email='$email'";
        echo "Debug: SQL Query - $query<br>";

        $result = mysqli_query($con, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($con));
        }

        $row = mysqli_fetch_assoc($result);

        if ($row) {
            echo "Debug: Found row - ";
            print_r($row);

            // Verify the password using PBKDF2
            if (verify_pbkdf2_password($password, $row['password'])) {
                $_SESSION['valid'] = $row['email'];
                $_SESSION['username'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                header("Location: welcome1.php");
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "No doctor found with this email.";
        }
    } else {
        $error_message = "Email and password are required.";
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
</head>
<body style="background-color:rgba(34, 61, 102, 0.89);">
    <div class="container" style="background-image: url('assets/img/bg-masthead.jpg'); background-size: cover;">
        <div class="box form-box">
            <?php if (isset($error_message)): ?>
                <div class="message">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>
            <?php if (!isset($_SESSION['valid'])): ?>
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
                        <input type="submit" class="btn btn-primary" name="submit" value="Login">
                    </div>
                    <div class="links">
                        Don't have an account? <a href="register.php">Sign Up Now</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
