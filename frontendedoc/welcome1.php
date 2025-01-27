<?php
session_start();
require 'dbcon.php';

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

// Fetch the logged-in doctor's details
$user_id = $_SESSION['id'];
$user_query = "SELECT * FROM doctors WHERE id = ?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    echo "Doctor not found.";
    exit();
}

$user = $user_result->fetch_assoc();

// Fetch patients assigned to the logged-in doctor
$patient_query = "SELECT * FROM patients WHERE doctor_id = ?";
$patient_stmt = $con->prepare($patient_query);
$patient_stmt->bind_param("i", $user_id);
$patient_stmt->execute();
$patient_result = $patient_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to E-Doc</title>
    <!-- CSS and Fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #FAF3F0; color: #000; }
        .nav { background-color: #2e7d32; color: white; }
        .intro { text-align: center; padding: 30px 0; }
        .card-header h4 { margin: 5px 2px; }
        .btn-view { background-color: #28a745; color: #fff; }
        .btn-edit { background-color: #ffc107; color: #000; }
        .btn-delete { background-color: #dc3545; color: #fff; }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="welcome1.php">E-Doc</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="edit.php?Id=<?= $user['id'] ?>">Change Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="patient-create.php">Add Patient</a></li>
                    <li class="nav-item"><a class="nav-link" href="php/logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <div class="intro">
        <h1><b>Welcome To Your E-Doc, <?= htmlspecialchars($user['name']); ?>!</b></h1>
    </div>

    <!-- Patient Details Table -->
    <main class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-info">Patient Details</h4>
                        <a href="patient-create.php" class="btn btn-primary float-end">Add Patient</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Image</th>
                                        <th>Diagnosis</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Allergies</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($patient_result->num_rows > 0): ?>
                                        <?php while ($patient = $patient_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $patient['id']; ?></td>
                                                <td><?= htmlspecialchars($patient['name']); ?></td>
                                                <td><?= htmlspecialchars($patient['email']); ?></td>
                                                <td><?= htmlspecialchars($patient['phone']); ?></td>
                                                <td>
                                                    <?php if (!empty($patient['image_name'])): ?>
                                                        <img src="<?= htmlspecialchars($patient['image_name']); ?>" class="img-fluid" alt="Patient Image">
                                                    <?php else: ?>
                                                        <p>No Image Available</p>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($patient['Diagnosis']); ?></td>
                                                <td><?= htmlspecialchars($patient['age']); ?></td>
                                                <td><?= htmlspecialchars($patient['gender']); ?></td>
                                                <td><?= htmlspecialchars($patient['allergies']); ?></td>
                                                <td>
                                                    <a href="patient-view.php?id=<?= $patient['id']; ?>" class="btn btn-view btn-sm">View</a>
                                                    <a href="patient-edit.php?id=<?= $patient['id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                                                    <form action="code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_patient" value="<?= $patient['id']; ?>" class="btn btn-delete btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10">No Record Found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
