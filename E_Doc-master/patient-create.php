<?php
session_start();

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Create New Patient</title>
</head>
<body style="background-image: url('assets/img/doc2.jpg'); background-size: cover;">

  <div class="container" style="padding-top: 80px;">
        <div class="container mt-5" >

            <?php include('message.php'); ?>

            <div class="row">
                <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                        <h4>Add New Patient
                            <a href="welcome1.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                <div class="card-body">
        <form action="code.php" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Patient Name</label>
            <input type="text" name="name" class="form-control" required >
        </div>
        <div class="mb-3">
            <label>Patient Email</label>
            <input type="email" name="email" class="form-control" required >
        </div>
        <div class="mb-3">
            <label>Patient Phone</label>
            <input type="number  " name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Diagnosis</label>
            <input type="text" name="Diagnosis" class="form-control" required >
        </div>
        <div class="mb-3">
            <label>Patient Image</label><br>
            <input type="file" name="image_name" class="form-control mt-2">
        </div>
        <div class="mb-3">
            <button type="submit" name="save_patient" class="btn btn-primary" style="color: black;">save patient</button>
        </div>

    </form>
</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>