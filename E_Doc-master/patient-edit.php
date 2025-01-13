<?php
session_start();
require_once 'dbcon.php';

if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    $stmt = $con->prepare("SELECT * FROM patients WHERE id=?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "<h4>No Such Id Found</h4>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Patient Details</title>
</head>
<body style="background-image: url('assets/img/doc2.jpg'); background-size: cover;">

<div class="container mt-5" style="padding-top: 80px;">

    <?php include('message.php'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Patient
                        <a href="welcome1.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($patient)) : ?>
                        <form action="code.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="patient_id" value="<?= $patient['id']; ?>">

                            <div class="mb-3">
                                <label>Patient Name</label>
                                <input type="text" name="name" value="<?= $patient['name']; ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Patient Email</label>
                                <input type="email" name="email" value="<?= $patient['email']; ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Patient Phone</label>
                                <input type="number" name="phone" value="<?= $patient['phone']; ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Diagnosis</label>
                                <input type="text" name="Diagnosis" value="<?= $patient['Diagnosis']; ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image_name" class="form-control">
                                <?php if (!empty($patient['image_name'])) : ?>
                                    <img src="<?= $patient['image_name']; ?>" class="img-fluid" alt="Radiology Image">
                                <?php else : ?>
                                    <p class="form-control">No Image Available</p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <button type="submit" name="update_patient" class="btn btn-primary">
                                    Update Patient
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>