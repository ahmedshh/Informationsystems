<?php
require 'dbcon.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Patient View</title>
</head>

<body style="background-image: url('assets/img/doc2.jpg'); background-size: cover;">

    <div class="container mt-5" style="padding-top: 80px;">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>View Patient Details
                            <a href="welcome1.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if (isset($_GET['id'])) {
                            $patient_id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM patients WHERE id='$patient_id'";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                $patient = mysqli_fetch_array($query_run);
                                ?>

                                <div class="mb-3">
                                    <label>Patient Name</label>
                                    <p class="form-control"><?= $patient['name']; ?></p>
                                </div>
                                <div class="mb-3">
                                    <label>Patient Email</label>
                                    <p class="form-control"><?= $patient['email']; ?></p>
                                </div>
                                <div class="mb-3">
                                    <label>Patient Phone</label>
                                    <p class="form-control"><?= $patient['phone']; ?></p>
                                </div>
                                <div class="mb-3">
                                    <label>Diagnosis</label>
                                    <p class="form-control"><?= $patient['Diagnosis']; ?></p>
                                </div>
                                <div class="mb-3">
                                    <label>Radiology Image</label>
                                    <?php if (!empty($patient['image_name'])) : ?>
                                        <img src="<?= $patient['image_name']; ?>" class="img-fluid" alt="Radiology Image">
                                    <?php else : ?>
                                        <p class="form-control">No Image Available</p>
                                    <?php endif; ?>
                                </div>

                        <?php
                            } else {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
