<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>



<link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web\fontawesome-free-6.4.0-web\css\all.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>


<body class="hold-transition skin-blue sidebar-mini">




<?php
include('../../partials/connect.php'); 

if (isset($_POST["submit"])) {
    // Retrieve form data
    $learnerFakeids = $_POST['learnerFakeids'];
    $attendances = $_POST['attendances'];
    $transitions = $_POST['transitions'];
    $medtakens = $_POST['medtakens'];

    // Prepare the SQL statements
    $checkStmt = $connect->prepare("SELECT COUNT(*) FROM welcome WHERE LearnerId = ? AND ReportDate = CURDATE()");
    $insertStmt = $connect->prepare("INSERT INTO welcome (ReporterId, LearnerId, ReportDate, Attendance, ClassroomTransition, MedTaken) 
    VALUES (?, ?, Now(), ?, ?, ?)");

    if ($checkStmt === false || $insertStmt === false) {
        die("Prepare failed: " . $connect->error); // Handle prepare statement failure
    }

    $index = 0;
    $numEntries = count($learnerFakeids);
    $success = true;

    while ($index < $numEntries) {
        $reporterFakeid = $_SESSION['user_id'];  // for user/reporter

        $learnerFakeid = $learnerFakeids[$index];
        $attendance = $attendances[$index];
        $transitionclassroom = $transitions[$index];
        $medtaken = $medtakens[$index];

        // Check if a report already exists for this learner today
        $checkStmt->bind_param("i", $learnerFakeid);
        
        if (!$checkStmt->execute()) {
            echo 'Failed to check existing reports. Please try again later.';
            $success = false;
            break;
        }

        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->free_result(); // Free the result set

        if ($count > 0) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Report Already Exists",
                    text: "Data has already been saved for today. Click the Edit Button if you wish to change data",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../../teacher/teacherindex.php";
                    }
                });
            </script>';
            $success = false;
            break;
        }

        // Bind parameters and execute the insert statement
        $insertStmt->bind_param("iisss", $reporterFakeid, $learnerFakeid, $attendance, $transitionclassroom, $medtaken);

        if (!$insertStmt->execute()) {
            if ($connect->errno === 1062) { // Duplicate entry error code
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Duplicate Report",
                        text: "Data has already been saved for today. Click the Edit Button if you wish to change data",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../../teacher/teacherindex.php";
                        }
                    });
                </script>';
                $success = false;
                break;
            } else {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Submission Error",
                        text: "Failed to submit the report. Please try again later.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../../teacher/teacherindex.php";
                        }
                    });
                </script>';
                $success = false;
                break;
            }
        }

        $index++;
    }

    // Close the statements
    $checkStmt->close();
    $insertStmt->close();

    if ($success) {
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Successfully Reported",
                text: "Data has been saved for all Learners.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../../teacher/teacherindex.php";
                }
            });
        </script>';
    }

    // Close the database connection
    $connect->close();
    exit();
}
?>


</body>
</html>

