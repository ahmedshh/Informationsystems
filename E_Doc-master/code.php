<?php
session_start();
require 'dbcon.php';

ob_start(); // Start output buffering

function sanitizeFileName($fileName) {
    // Remove spaces and special characters from the file name
    $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
    return $fileName;
}

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0; // initialize user_id

if (isset($_GET['user_id']) && $_GET['user_id'] != $user_id) {
    // Redirect or display an error message
    header("Location: error_page.php"); // or any error handling page
    exit;
}

function displayErrorMessage($message) {
    // Store error messages in session to display later
    $_SESSION['error_message'] = $message;
}

function handleFileUpload($file, $uploadDirectory) {
    $originalFileName = $file['name'];
    $image_tmp_name = $file['tmp_name'];
    $image_ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

    // Ensure the file name is unique
    $image_name = $uploadDirectory . sanitizeFileName($originalFileName);
    $counter = 1;
    while (file_exists($image_name)) {
        $image_name = $uploadDirectory . $counter . '_' . sanitizeFileName($originalFileName);
        $counter++;
    }

    if ($image_ext == 'jpg' || $image_ext == 'jpeg' || $image_ext == 'png') {
        if (move_uploaded_file($image_tmp_name, $image_name)) {
            // Image uploaded successfully
            return $image_name;
        } else {
            // Error in uploading image
            displayErrorMessage("Unable to move the uploaded file.");
            return false;
        }
    } else {
        displayErrorMessage("Invalid file type. Only JPG, JPEG, and PNG files are allowed.");
        return false;
    }
}

// Handle delete operation
if (isset($_POST['delete_patient'])) {
    $patient_id = $_POST['delete_patient'];

    $query = "DELETE FROM patients WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Patient Deleted Successfully";
    } else {
        $_SESSION['message'] = "Patient Not Deleted";
    }

    header("Location: welcome1.php");
    exit(0);
}

// Handle update operation
if (isset($_POST['update_patient'])) {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $diagnosis = $_POST['Diagnosis'];
    $current_image = isset($_POST['current_image']) ? $_POST['current_image'] : ''; // Ensure current_image is set

    $image_name = $current_image; // Default to current image

    if (isset($_FILES['image_name']['name']) && $_FILES['image_name']['name'] != '') {
        $image_name = handleFileUpload($_FILES['image_name'], 'uploaded_images/');
        if (!$image_name) {
            // Handle file upload error
            $_SESSION['message'] = "Error: Unable to upload image.";
            header("Location: welcome1.php");
            exit(0);
        }
    }

    $query = "UPDATE patients SET name = ?, email = ?, phone = ?, Diagnosis = ?, image_name = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $name, $email, $phone, $diagnosis, $image_name, $patient_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Patient Updated Successfully";
    } else {
        $_SESSION['message'] = "Patient Not Updated";
    }

    header("Location: welcome1.php");
    exit(0);
}

$query = "SELECT patients.*, users.Username as user_name FROM patients INNER JOIN users ON patients.user_id = users.Id WHERE patients.user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle save operation
if (isset($_POST['save_patient'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $diagnosis = $_POST['Diagnosis'];

    $image_name = '';

    if (isset($_FILES['image_name']['name']) && $_FILES['image_name']['name'] != '') {
        $image_name = handleFileUpload($_FILES['image_name'], 'uploaded_images/');
        if (!$image_name) {
            // Handle file upload error
            $_SESSION['message'] = "Error: Unable to upload image.";
            header("Location: patient-create.php");
            exit(0);
        }
    }

    // Ensure image_name is not null
    $image_name = $image_name ?: 'default_image.jpg'; // Use a default image if no image is uploaded

    $query = "INSERT INTO patients (name, email, phone, Diagnosis, image_name, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $name, $email, $phone, $diagnosis, $image_name, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Patient Created Successfully";
    } else {
        $_SESSION['message'] = "Patient Not Created";
    }

    header("Location: welcome1.php");
    exit(0);
}

ob_end_flush(); // Flush the output buffer
?>