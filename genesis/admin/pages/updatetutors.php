<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../common/partials/head.php"); 
include(__DIR__ . "/../../partials/connect.php");

$tutorId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch personal info
$stmt = $connect->prepare("SELECT Name, Surname, Email, Contact FROM users WHERE Id = ?");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
$tutor = $result->fetch_assoc();
$stmt->close();

// Fetch professional info
$stmt = $connect->prepare("SELECT Bio, Qualifications, ExperienceYears, Availability FROM tutors WHERE TutorId = ?");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
$prof = $result->fetch_assoc();
$stmt->close();

// Fetch tutor's subjects
$tutorSubjects = [];
$stmt = $connect->prepare("SELECT SubjectId FROM tutorsubject WHERE TutorId = ?");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $tutorSubjects[] = $row['SubjectId'];
}
$stmt->close();

// Fetch payments
$payments = [];
$stmt = $connect->prepare("SELECT Amount, PaymentDate, Notes FROM tutorpayments WHERE TutorId = ? ORDER BY PaymentDate DESC");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $payments[] = $row;
}
$stmt->close();
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include(__DIR__ . "/../partials/header.php"); ?>
<?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Update Tutor <small>Manage Tutor Profile</small></h1>
        <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Tutor</li>
        </ol>
    </section>

    <section class="content">

    <div class="row" style="margin-top:20px;">
        <!-- Personal Info (left) -->
        <div class="col-md-6">
            <form method="POST" action="update_tutor_handler.php">
                <div class="box box-info" style="border-top:3px solid #00c0ef;">
                    <div class="box-header with-border" style="background-color:#e0f7ff;">
                        <h3 class="box-title" style="color:#00c0ef;">Personal Information</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="firstname" value="<?= htmlspecialchars($tutor['Name']) ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Surname</label>
                                <input type="text" class="form-control" name="surname" value="<?= htmlspecialchars($tutor['Surname']) ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($tutor['Email']) ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Contact</label>
                                <input type="tel" class="form-control" name="contactnumber" value="<?= htmlspecialchars($tutor['Contact']) ?>" required>
                            </div>
                            <input type="hidden" name="tutor_id" value="<?= $tutorId ?>">
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-info" name="update_personal">Update Personal Info</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Professional Info (right) -->
        <div class="col-md-6">
            <div class="box box-warning" style="border-top:3px solid #f39c12;">
                <div class="box-header with-border" style="background-color:#fff3e0;">
                    <h3 class="box-title" style="color:#f39c12;">Professional Info (Read-Only)</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Bio</label>
                            <textarea class="form-control" rows="4" readonly style="background:#f5f5f5; cursor:not-allowed;"><?= htmlspecialchars($prof['Bio'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Qualifications</label>
                            <textarea class="form-control" rows="4" readonly style="background:#f5f5f5; cursor:not-allowed;"><?= htmlspecialchars($prof['Qualifications'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Experience (Years)</label>
                            <input class="form-control form-control-plaintext" value="<?= htmlspecialchars($prof['ExperienceYears'] ?? '') ?>" readonly style="background:#f5f5f5;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Availability</label>
                            <input class="form-control form-control-plaintext" value="<?= htmlspecialchars($prof['Availability'] ?? '') ?>" readonly style="background:#f5f5f5;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SUBJECTS -->
    <div class="row" style="margin-top:20px;">
        <div class="col-md-12">
            <form method="POST" action="update_tutor_handler.php">
                <div class="box box-primary" style="border-top:3px solid #3c8dbc;">
                    <div class="box-header with-border" style="background-color:#f0f8ff;">
                        <h3 class="box-title" style="color:#3c8dbc;">Manage(Add/Rem) Subjects</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                        <?php
                        $allSubjects = $connect->query("
                            SELECT s.SubjectId, s.SubjectName, g.GradeName
                            FROM subjects s
                            JOIN grades g ON s.GradeId = g.GradeId
                            ORDER BY g.GradeName, s.SubjectName
                        ");
                        while($sub = $allSubjects->fetch_assoc()){
                            $checked = in_array($sub['SubjectId'], $tutorSubjects) ? 'checked' : '';
                            echo "
                            <div class='col-md-4'>
                                <div class='checkbox'>
                                    <label>
                                        <input type='checkbox' name='subject_ids[]' value='{$sub['SubjectId']}' $checked>
                                        {$sub['SubjectName']} - {$sub['GradeName']}
                                    </label>
                                </div>
                            </div>";
                        }
                        ?>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <input type="hidden" name="tutor_id" value="<?= $tutorId ?>">
                        <button type="submit" class="btn btn-primary" name="update_subjects">Update Subjects</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- FINANCE -->
    <!-- FINANCE + CLASSES GRID -->
    <div class="row" style="margin-top:20px;">
        <!-- FINANCE -->
        <div class="col-md-6">
            <div class="box box-success" style="border-top:3px solid #00a65a;">
                <div class="box-header with-border" style="background-color:#e6ffed;">
                    <h3 class="box-title" style="color:#00a65a;">Finance</h3>
                </div>
                <div class="box-body">
                    <?php 
                    $totalPaid = 0;
                    foreach($payments as $p){ $totalPaid += $p['Amount']; }
                    ?>
                    <p><strong>Total Paid:</strong> R <?= number_format($totalPaid, 2) ?></p>

                    <form method="POST" action="update_tutor_handler.php">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Amount</label>
                                <input type="number" step="0.01" class="form-control" name="amount" required>
                            </div>
                            <div class="col-md-6">
                                <label>Notes</label>
                                <input type="text" class="form-control" name="notes">
                            </div>
                            <div class="col-md-3 text-right" style="margin-top:25px;">
                                <input type="hidden" name="tutor_id" value="<?= $tutorId ?>">
                                <button type="submit" class="btn btn-success" name="update_finance">Add Payment</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped" style="margin-top:15px;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount (R)</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($payments as $p): ?>
                            <tr>
                                <td><?= $p['PaymentDate'] ?></td>
                                <td><?= number_format($p['Amount'], 2) ?></td>
                                <td><?= htmlspecialchars($p['Notes']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Assigned Classes & Groups -->
        <div class="col-md-6">
            <div class="box box-info" style="border-top:3px solid #605ca8;">
                <div class="box-header with-border" style="background-color:#e8e5f7;">
                    <h3 class="box-title" style="color:#605ca8;">Assigned Classes/Groups</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Grade</th>
                                <th>Subject</th>
                                <th>Group Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Fetch assigned classes for this tutor
                        $assignedClasses = [];
                        $stmt = $connect->prepare("
                            SELECT c.Grade, c.GroupName, s.SubjectName
                            FROM classes c
                            JOIN subjects s ON c.SubjectID = s.SubjectId
                            WHERE c.TutorID = ?
                            ORDER BY c.Grade, c.GroupName
                        ");
                        $stmt->bind_param("i", $tutorId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()){
                            $assignedClasses[] = $row;
                        }
                        $stmt->close();

                        foreach($assignedClasses as $class): ?>
                            <tr>
                                <td><?= htmlspecialchars($class['Grade']) ?></td>
                                <td><?= htmlspecialchars($class['SubjectName']) ?></td>
                                <td><?= htmlspecialchars($class['GroupName']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    </section>
</div>
<div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('updated_personal')) {
        Swal.fire({ icon: 'success', title: 'Success', text: 'Personal info updated successfully!' });
    }
    if (urlParams.has('updated_subjects')) {
        Swal.fire({ icon: 'success', title: 'Success', text: 'Subjects updated successfully!' });
    }
    if (urlParams.has('updated_finance')) {
        Swal.fire({ icon: 'success', title: 'Success', text: 'Payment added successfully!' });
    }
    if (urlParams.has('error')) {
        Swal.fire({ icon: 'error', title: 'Error', text: decodeURIComponent(urlParams.get('error')) });
    }
});
</script>

</body>
</html>
