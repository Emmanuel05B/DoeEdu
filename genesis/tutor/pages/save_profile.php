<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$userId = $_SESSION['user_id'];

// Retrieve form inputs
$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$title = $_POST['title'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$availability = $_POST['availability'] ?? '';
$bio = $_POST['bio'] ?? '';
$qualifications = $_POST['qualifications'] ?? '';
$experience_years = $_POST['experience_years'] ?? '';

$imagePath = null;

// Handle optional profile picture upload
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
    $uploadsDir = "../../uploads/";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    $filename = time() . '_' . basename($_FILES['profile_pic']['name']);
    $filepath = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filepath)) {
        $imagePath = $filepath;
    } else {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Upload Failed',
            'message' => 'Failed to upload image.'
        ];
        header("Location: profilemanagement.php");
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

    // Set SweetAlert session
    $_SESSION['alert'] = [
        'icon' => 'success',
        'title' => 'Profile Updated!',
        'message' => 'Your profile changes have been saved.'
    ];

    header("Location: profilemanagement.php");
    exit();

} catch (Exception $e) {
    $connect->rollback();
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Update Failed',
        'message' => 'Something went wrong. Please try again later.'
    ];
    header("Location: profilemanagement.php");
    exit();
}
?>
