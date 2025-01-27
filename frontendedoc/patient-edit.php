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
    <title>Edit Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Patient</h4>
                        <a href="welcome1.php" class="btn btn-danger float-end">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="patient_id" value="<?= $patient['id']; ?>">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($patient['name']); ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($patient['email']); ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($patient['phone']); ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="age">Age</label>
                                <input type="number" name="age" value="<?= htmlspecialchars($patient['age']); ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender">Gender</label><br>
                                <input type="radio" name="gender" value="Male" <?= $patient['gender'] == 'Male' ? 'checked' : ''; ?>> Male
                                <input type="radio" name="gender" value="Female" <?= $patient['gender'] == 'Female' ? 'checked' : ''; ?>> Female
                            </div>
                            <div class="mb-3">
                                <label for="allergies">Allergies</label><br>
                                <?php
                                $allergy_list = explode(", ", $patient['allergies']);
                                ?>
                                <input type="checkbox" name="allergies[]" value="Pollen" <?= in_array('Pollen', $allergy_list) ? 'checked' : ''; ?>> Pollen
                                <input type="checkbox" name="allergies[]" value="Dust" <?= in_array('Dust', $allergy_list) ? 'checked' : ''; ?>> Dust
                                <input type="checkbox" name="allergies[]" value="Nuts" <?= in_array('Nuts', $allergy_list) ? 'checked' : ''; ?>> Nuts
                                <input type="checkbox" name="allergies[]" value="Shellfish" <?= in_array('Shellfish', $allergy_list) ? 'checked' : ''; ?>> Shellfish
                                <input type="checkbox" name="allergies[]" value="Other" <?= in_array('Other', $allergy_list) ? 'checked' : ''; ?>> Other
                            </div>
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="form-control">
                                <?php if (!empty($patient['image_name'])): ?>
                                    <img src="<?= htmlspecialchars($patient['image_name']); ?>" class="img-fluid mt-2" alt="Current Image">
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="Diagnosis">Diagnosis</label>
                                <textarea name="Diagnosis" class="form-control" required><?= htmlspecialchars($patient['Diagnosis']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="update_patient" class="btn btn-primary">Update Patient</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
