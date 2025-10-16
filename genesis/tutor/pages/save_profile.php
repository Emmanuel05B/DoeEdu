<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../partials/connect.php");

$userId = $_SESSION['user_id'];

// Retrieve form inputs
$name = $_POST['name'];
$surname = $_POST['surname'];
$title = $_POST['title'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$availability = $_POST['availability'];
$bio = $_POST['bio'];
$qualifications = $_POST['qualifications'];
$experience_years = $_POST['experience_years'];

// Handle optional profile picture upload
$imagePath = null;
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
    $uploadsDir = "../uploads/";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    $filename = time() . '_' . basename($_FILES['profile_pic']['name']);
    $filepath = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filepath)) {
        $imagePath = $filepath;
    } else {
        showAlert("error", "Upload Failed", "Failed to upload image.", "profile.php");
        exit();
    }
}

try {
    $connect->begin_transaction();

    // Update users table
    $updateUser = $connect->prepare("UPDATE users SET Name = ?, Surname = ?, Email = ?, Contact = ? WHERE Id = ?");
    $updateUser->bind_param("ssssi", $name, $surname, $email, $phone, $userId);
    $updateUser->execute();

    // Update tutors table
    if ($imagePath) {
        $updateTutor = $connect->prepare("UPDATE tutors SET Bio = ?, Qualifications = ?, ExperienceYears = ?, ProfilePicture = ?, Availability = ? WHERE TutorId = ?");
        $updateTutor->bind_param("ssissi", $bio, $qualifications, $experience_years, $imagePath, $availability, $userId);
    } else {
        $updateTutor = $connect->prepare("UPDATE tutors SET Bio = ?, Qualifications = ?, ExperienceYears = ?, Availability = ? WHERE TutorId = ?");
        $updateTutor->bind_param("ssisi", $bio, $qualifications, $experience_years, $availability, $userId);
    }
    $updateTutor->execute();

    $connect->commit();

    showAlert("success", "Profile Updated!", "Your profile changes have been saved.", "profilemanagement.php");
} catch (Exception $e) {
    $connect->rollback();
    showAlert("error", "Update Failed", "Something went wrong. Please try again later.", "profilemanagement.php");
}

// Function to show SweetAlert inside HTML page
function showAlert($icon, $title, $message, $redirect)
{
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Notification</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$message',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '$redirect';
        });
    </script>
    </body>
    </html>";
}
?>
