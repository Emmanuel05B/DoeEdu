<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

$tutorId = $_SESSION['user_id'];

// Validate questionId
if (!isset($_GET['questionId']) || !is_numeric($_GET['questionId'])) {
    die("Invalid question ID.");
}

$questionId = intval($_GET['questionId']);
$success = false;
$error = null;

// Check if form submitted to update question
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionText = trim($_POST['QuestionText']);
    $optionA = trim($_POST['OptionA']);
    $optionB = trim($_POST['OptionB']);
    $optionC = trim($_POST['OptionC']);
    $optionD = trim($_POST['OptionD']);
    $correctAnswer = $_POST['CorrectAnswer'];

    // Validate correct answer
    if (!in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
        $error = "Correct answer must be one of A, B, C, or D.";
    } elseif (empty($questionText) || empty($optionA) || empty($optionB) || empty($optionC) || empty($optionD)) {
        $error = "All fields are required.";
    } else {
        // Verify question belongs to tutor
        $verifyStmt = $connect->prepare("
            SELECT oa.TutorId 
            FROM onlinequestions oq
            INNER JOIN onlineactivities oa ON oq.ActivityId = oa.Id
            WHERE oq.Id = ?
        ");
        $verifyStmt->bind_param("i", $questionId);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();

        if ($verifyResult->num_rows === 0) {
            die("Question not found.");
        }

        $row = $verifyResult->fetch_assoc();
        if ($row['TutorId'] != $tutorId) {
            die("You do not have permission to edit this question.");
        }
        $verifyStmt->close();

        // Update question
        $updateStmt = $connect->prepare("
            UPDATE onlinequestions
            SET QuestionText = ?, OptionA = ?, OptionB = ?, OptionC = ?, OptionD = ?, CorrectAnswer = ?
            WHERE Id = ?
        ");
        $updateStmt->bind_param("ssssssi", $questionText, $optionA, $optionB, $optionC, $optionD, $correctAnswer, $questionId);

        if ($updateStmt->execute()) {
            $success = true;
        } else {
            $error = "Failed to update the question. Please try again.";
        }
        $updateStmt->close();
    }
}

// Fetch question data for display
$stmt = $connect->prepare("
    SELECT oq.*, oa.Id AS ActivityId
    FROM onlinequestions oq
    INNER JOIN onlineactivities oa ON oq.ActivityId = oa.Id
    WHERE oq.Id = ?
");
$stmt->bind_param("i", $questionId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Question not found.");
}

$question = $result->fetch_assoc();
$stmt->close();
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

<div class="content-wrapper">

    <section class="content-header">
       <h1>Edit Question <small>Update question details</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Details</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Question ID: <?php echo $question['Id']; ?></h3>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="ActivityId" value="<?php echo $question['ActivityId']; ?>">

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="box-body">
                    <div class="form-group">
                        <label for="QuestionText">Question Text</label>
                        <textarea name="QuestionText" id="QuestionText" class="form-control" required rows="4"><?php echo htmlspecialchars($question['QuestionText']); ?></textarea>
                    </div>

                   <div class="row">
                        <!-- Left side: Option A and B -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="OptionA">Option A</label>
                                <input type="text" name="OptionA" id="OptionA" class="form-control" required 
                                    value="<?php echo htmlspecialchars($question['OptionA']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="OptionB">Option B</label>
                                <input type="text" name="OptionB" id="OptionB" class="form-control" required 
                                    value="<?php echo htmlspecialchars($question['OptionB']); ?>">
                            </div>
                        </div>

                        <!-- Right side: Option C and D -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="OptionC">Option C</label>
                                <input type="text" name="OptionC" id="OptionC" class="form-control" required 
                                    value="<?php echo htmlspecialchars($question['OptionC']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="OptionD">Option D</label>
                                <input type="text" name="OptionD" id="OptionD" class="form-control" required 
                                    value="<?php echo htmlspecialchars($question['OptionD']); ?>">
                            </div>
                        </div> 
                    </div>

                    <div class="form-group">
                        <label for="CorrectAnswer">Correct Answer</label>
                        <select name="CorrectAnswer" id="CorrectAnswer" class="form-control" required>
                            <option value="A" <?php if($question['CorrectAnswer'] === 'A') echo 'selected'; ?>>A</option>
                            <option value="B" <?php if($question['CorrectAnswer'] === 'B') echo 'selected'; ?>>B</option>
                            <option value="C" <?php if($question['CorrectAnswer'] === 'C') echo 'selected'; ?>>C</option>
                            <option value="D" <?php if($question['CorrectAnswer'] === 'D') echo 'selected'; ?>>D</option>
                        </select>
                    </div>
                </div>

                <div class="box-footer text-right">
                    <a href="viewactivity.php?activityId=<?php echo $question['ActivityId']; ?>" class="btn btn-default">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Question</button>
                </div>
            </form>
        </div>
    </section>
</div>

<div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>



<?php if ($success): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Question was updated successfully.',
        confirmButtonText: 'OK'
    }).then(() => {
        // Redirect back to the activity view page after success
        window.location.href = 'viewactivity.php?activityId=<?php echo $question['ActivityId']; ?>';
    });
</script>
<?php elseif (isset($error)): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '<?php echo addslashes($error); ?>',
        confirmButtonText: 'OK'
    });
</script>
<?php endif; ?>

</body>
</html>
