<?php
session_start();
require 'dbcon.php';

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Patient</title>
    <!-- CSS and Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Patient</h4>
                        <a href="welcome1.php" class="btn btn-danger float-end">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="diagnosis">Diagnosis</label>
                                <textarea name="diagnosis" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="age">Age</label>
                                <input type="number" name="age" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender">Gender</label><br>
                                <input type="radio" name="gender" value="Male" required> Male
                                <input type="radio" name="gender" value="Female" required> Female
                            </div>
                            <div class="mb-3">
                                <label for="allergies">Allergies</label><br>
                                <input type="checkbox" name="allergies[]" value="Pollen"> Pollen
                                <input type="checkbox" name="allergies[]" value="Dust"> Dust
                                <input type="checkbox" name="allergies[]" value="Nuts"> Nuts
                                <input type="checkbox" name="allergies[]" value="Shellfish"> Shellfish
                                <input type="checkbox" name="allergies[]" value="Other"> Other
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="add_patient" class="btn btn-primary">Save Patient</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
