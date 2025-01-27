<?php
session_start();
require 'dbcon.php';

if (isset($_POST['save_patient'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $age = intval($_POST['age']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $allergies = isset($_POST['allergies']) ? implode(", ", $_POST['allergies']) : '';
    $diagnosis = mysqli_real_escape_string($con, $_POST['diagnosis']);
    $doctor_id = $_SESSION['id'];

    // Image upload handling
    $image_name = null;
    if (!empty($_FILES['image']['name'])) {
        $image_name = "uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_name);
    }

    $query = "INSERT INTO patients (name, email, phone, age, gender, allergies, diagnosis, image_name, doctor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssissssi", $name, $email, $phone, $age, $gender, $allergies, $diagnosis, $image_name, $doctor_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Patient added successfully!";
        header("Location: welcome1.php");
        exit();
    } else {
        $_SESSION['message'] = "Patient creation failed: " . $stmt->error;
        header("Location: patient-create.php");
        exit();
    }
}

if (isset($_POST['update_patient'])) {
    $patient_id = intval($_POST['patient_id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $age = intval($_POST['age']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $allergies = isset($_POST['allergies']) ? implode(", ", $_POST['allergies']) : '';
    $diagnosis = mysqli_real_escape_string($con, $_POST['diagnosis']);

    // Image upload handling
    $image_name = null;
    if (!empty($_FILES['image']['name'])) {
        $image_name = "uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_name);
    }

    $query = "UPDATE patients SET name = ?, email = ?, phone = ?, age = ?, gender = ?, allergies = ?, diagnosis = ?, image_name = IFNULL(?, image_name) WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssissssi", $name, $email, $phone, $age, $gender, $allergies, $diagnosis, $image_name, $patient_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Patient updated successfully!";
        header("Location: welcome1.php");
        exit();
    } else {
        $_SESSION['message'] = "Patient update failed: " . $stmt->error;
        header("Location: patient-edit.php?id=$patient_id");
        exit();
    }
}

if (isset($_POST['delete_patient'])) {
    $patient_id = intval($_POST['delete_patient']);
    $query = "DELETE FROM patients WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $patient_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Patient deleted successfully!";
        header("Location: welcome1.php");
        exit();
    } else {
        $_SESSION['message'] = "Patient deletion failed: " . $stmt->error;
        header("Location: welcome1.php");
        exit();
    }
}

header("Location: welcome1.php");
exit();
