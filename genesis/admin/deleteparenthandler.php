<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
?>

<?php include('../partials/connect.php'); ?>

<?php include("adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<body class="hold-transition skin-blue sidebar-mini">

<?php
$errors = [];

// Get learner ID to delete
$learnerId = $_GET['learnerId']; // Learner ID passed from form or URL

// Prepare statement to fetch ParentId
$sql = "SELECT ParentId FROM parentlearner WHERE LearnerId = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $learnerId);
$stmt->execute();
$results = $stmt->get_result();
$final = $results->fetch_assoc();  

// Check if ParentId exists
if ($final && isset($final['ParentId'])) {
    $parentId = $final['ParentId'];

    // Start transaction
    $connect->begin_transaction();

    try {
        // First, delete all messages from `messages` table
        $stmt = $connect->prepare("DELETE FROM messages WHERE ParentId = ?");
        $stmt->bind_param("i", $parentId);

        if ($stmt->execute()) {
            // Next, delete all replies from `parentreply` table
            $stmt2 = $connect->prepare("DELETE FROM parentreply WHERE ReporterId = ?");
            $stmt2->bind_param("i", $parentId);

            if ($stmt2->execute()) {
                // Delete from `parentlearner` table
                $stmt3 = $connect->prepare("DELETE FROM parentlearner WHERE LearnerId = ?");
                $stmt3->bind_param("i", $learnerId);

                if ($stmt3->execute()) {
                    // Delete from `users` table
                    $stmt4 = $connect->prepare("DELETE FROM users WHERE Id = ?");
                    $stmt4->bind_param("i", $parentId);

                    if ($stmt4->execute()) {
                        // Commit transaction
                        $connect->commit();
                        echo '<script>
                            Swal.fire({
                                icon: "success",
                                title: "Parent Successfully Deleted",
                                text: "The parent record has been deleted across all related tables.",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "deleteparent.php";
                                }
                            });
                        </script>'; 
                    } else {
                        // Rollback transaction if deletion from `users` fails
                        $connect->rollback();
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete parent from the users table.',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'deleteparent.php'; 
                            });
                        </script>";
                    }
                } else {
                    // Rollback transaction if deletion from `parentlearner` fails
                    $connect->rollback();
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete parent from the parentlearner table.',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'deleteparent.php'; 
                        });
                    </script>";
                }
            } else {
                // Rollback transaction if deletion from `parentreply` fails
                $connect->rollback();
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete replies. Transaction rolled back.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location = 'deleteparent.php'; 
                    });
                </script>";
            }
        } else {
            // Rollback transaction if deletion from `messages` fails
            $connect->rollback();
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete messages. Transaction rolled back.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location = 'deleteparent.php'; 
                });
            </script>";
        }
    } catch (Exception $e) {
        // Rollback transaction if any exception occurs
        $connect->rollback();
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred during the deletion process.',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location = 'deleteparent.php'; 
            });
        </script>";
    }
} else {
    // ParentId does not exist
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Parent already deleted or was never registered.',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location = 'deleteparent.php'; 
        });
    </script>";
}

$connect->close();
?>

<div class="wrapper">
    <!-- You can place your form or buttons here -->
</div>

</body>
</html>
