<?php
session_start();
require 'dbcon.php';

function sanitizeFileName($fileName) {
    // Remove spaces and special characters from the file name
    $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
    return $fileName;
}

$query = "SELECT patients.*, users.Username as user_name FROM patients INNER JOIN users ON patients.user_id = users.Id WHERE patients.user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$user_id = $_SESSION['id'];
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .intro {
            text-align: center;
            color:#2e7d32;
        }

        h1 {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border: none;
            padding: 0.5em 1em;
            margin: 0;
            font-weight: bold;
            font-size: 2em;
            animation: typing 5s steps(22) infinite forwards;
        }

        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }
        body {
            background-color: #FAF3F0;
            color: #FAF3F0;
        }

        .nav {
            background-color: #2e7d32;
            color: white;
        }

        .intro {
            text-align: center;
            padding: 30px 0;
        }

        .btn-xl {
            padding: 10px;
            background-color: #28a745;
            color: #fff;
        }
        

        .card-header h4 {
            margin: 5px 2px;
        }

        .navbar-nav .nav-link {
            color: white;
        }

        .navbar-nav .nav-link:hover {
            color: #28a745;
        }

        .card {
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            text-align: center;
        }

        /* Button Colors */
        .btn-view {
            background-color: #28a745;
            color: #fff;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">E Doc</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php
                    include("php/config.php");
                    if (!isset($_SESSION['valid'])) {
                        header("Location: index.php");
                    }

                    $id = $_SESSION['id'];
                    $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

                    while ($result = mysqli_fetch_assoc($query)) {
                        $res_Uname = $result['Username'];
                        $res_Email = $result['Email'];
                        $res_Age = $result['Age'];
                        $res_id = $result['Id'];
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href='edit.php? Id=<?= $res_id ?>'>Change Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href='patient-create.php? Id=<?= $res_id ?>'>Add Patient</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="php/logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="intro">
        <h1 class="intro"><b>Welcome To Your E Doc!</b></h1>
    </div>

    <main class="container mt-4">
        <?php include('message.php'); ?>

        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-info">Patient Details</h4>
                        <a href="patient-create.php" class="btn btn-primary float-end" style="padding: 10px; color: white;">Add Patient</a>

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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
include('dbcon.php');
$query = "SELECT * FROM patients";
$query_run = mysqli_query($con, $query);

if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $patient) {
        if ($patient['user_id'] == $_SESSION['id']) {
            $image_name = $patient['image_name'];
?>
            <tr>
                <td><?= $patient['id']; ?></td>
                <td><?= $patient['name']; ?></td>
                <td><?= $patient['email']; ?></td>
                <td><?= $patient['phone']; ?></td>
                <td>
                    <?php if (!empty($patient['image_name'])) : ?>
                        <img src="<?= $patient['image_name']; ?>" class="img-fluid" alt="Radiology Image">
                    <?php else : ?>
                        <p class="form-control">No Image Available</p>
                    <?php endif; ?>
                </td>
                <td><?= $patient['Diagnosis']; ?></td>
                <td>
                    <a href="patient-view.php?id=<?= $patient['id']; ?>&user_id=<?= $patient['user_id']; ?>" class="btn btn-view btn-sm">View</a>
                    <a href="patient-edit.php?id=<?= $patient['id']; ?>&user_id=<?= $patient['user_id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                    <form action="code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_patient" value="<?= $patient['id']; ?>&user_id=<?= $patient['user_id']; ?>" class="btn btn-delete btn-sm">Delete</button>
                    </form>
                   
                </td>
            </tr>
<?php
        }
    }
} else {
    echo "<tr><td colspan='7'>No Record Found</td></tr>";
}
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
