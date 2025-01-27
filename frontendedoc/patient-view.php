<?php
session_start();
require 'dbcon.php';

if (isset($_GET['id'])) {
    $patient_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM patients WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        $_SESSION['message'] = "No such patient found.";
        header("Location: welcome1.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: welcome1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>View Patient Details</h4>
                        <a href="welcome1.php" class="btn btn-danger float-end">Back</a>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?= htmlspecialchars($patient['name']); ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($patient['email']); ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($patient['phone']); ?></p>
                        <p><strong>Age:</strong> <?= htmlspecialchars($patient['age']); ?></p>
                        <p><strong>Gender:</strong> <?= htmlspecialchars($patient['gender']); ?></p>
                        <p><strong>Allergies:</strong> <?= htmlspecialchars($patient['allergies']); ?></p>
                        <p><strong>Diagnosis:</strong> <?= htmlspecialchars($patient['Diagnosis']); ?></p>
                        <p><strong>Image:</strong></p>
                        <?php if (!empty($patient['image_name'])): ?>
                            <img src="<?= htmlspecialchars($patient['image_name']); ?>" class="img-fluid" alt="Patient Image">
                        <?php else: ?>
                            <p>No Image Available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
