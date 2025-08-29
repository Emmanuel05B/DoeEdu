<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/head.php"); 
include(__DIR__ . "/../../partials/connect.php");


include(__DIR__ . "/../../common/partials/head.php"); ?>
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

    <!-- PERSONAL INFO -->
    <form>
        <div class="box box-info" style="border-top:3px solid #00c0ef;">
            <div class="box-header with-border" style="background-color:#e0f7ff;">
                <h3 class="box-title" style="color:#00c0ef;">Personal Information</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="John" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Surname</label>
                        <input type="text" class="form-control" value="Doe" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="john.doe@example.com" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Contact</label>
                        <input type="tel" class="form-control" value="+27 123 456 789" required>
                    </div>
                   
                </div>
            </div>
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-info">Update Personal Info</button>
            </div>
        </div>
    </form>

    <!-- PROFESSIONAL INFO (readonly) -->
    <div class="box box-warning" style="border-top:3px solid #f39c12;">
        <div class="box-header with-border" style="background-color:#fff3e0;">
            <h3 class="box-title" style="color:#f39c12;">Professional Info (Read-Only)</h3>
        </div>
        <div class="box-body row">
            <div class="col-md-5">
                <label>Bio</label>
                <textarea class="form-control" rows="5" readonly style="background:#f5f5f5; cursor:not-allowed;">Experienced tutor in Maths and Science.</textarea>
            </div>
            <div class="col-md-5">
                <label>Qualifications</label>
                <textarea class="form-control" rows="5" readonly style="background:#f5f5f5; cursor:not-allowed;">BSc in Computer Science</textarea>
            </div>
            <div class="col-md-2">
                <label>Experience (Years)</label>
                <input class="form-control form-control-plaintext" value="5" readonly style="background:#f5f5f5;">
                <label>Availability</label>
                <input class="form-control form-control-plaintext" value="Weekdays 10am-4pm" readonly style="background:#f5f5f5;">
            </div>
        </div>
    </div>

    <!-- SUBJECTS + CLASSES GRID -->
<div class="row" style="margin-top:20px;">

    <!-- Manage Subjects -->
    <div class="col-md-6">
        <form>
            <div class="box box-primary" style="border-top:3px solid #3c8dbc;">
                <div class="box-header with-border" style="background-color:#f0f8ff;">
                    <h3 class="box-title" style="color:#3c8dbc;">Manage Subjects</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label><input type="checkbox" checked> Mathematics - Grade 10</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label><input type="checkbox"> Science - Grade 10</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label><input type="checkbox"> Physical Sciences - Grade 10</label>
                            </div>
                        </div>
                        <!-- Add more subjects here if needed, they will flow into next row automatically -->
                         <div class="col-md-4">
                            <div class="checkbox">
                                <label><input type="checkbox"> Science - Grade 11</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label><input type="checkbox"> English - Grade 11</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-primary">Update Subjects</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Assigned Classes & Groups -->
    <div class="col-md-6">
        <div class="box box-info" style="border-top:3px solid #605ca8;">
            <div class="box-header with-border" style="background-color:#e8e5f7;">
                <h3 class="box-title" style="color:#605ca8;">Assigned Classes & Groups</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Grade</th>
                            <th>Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Group A</td>
                            <td>Grade 10</td>
                            <td>Mathematics</td>
                        </tr>
                        <tr>
                            <td>Group B</td>
                            <td>Grade 10</td>
                            <td>Science</td>
                        </tr>
                        <tr>
                            <td>Group C</td>
                            <td>Grade 10</td>
                            <td>English</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>



    <!-- FINANCE -->
    <div class="box box-success" style="border-top:3px solid #00a65a; margin-top:20px;">
        <div class="box-header with-border" style="background-color:#e6ffed;">
            <h3 class="box-title" style="color:#00a65a;">Finance</h3>
        </div>
        <div class="box-body">
            <p><strong>Total Paid:</strong> R 5000.00</p>
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Notes</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-3 text-right" style="margin-top:25px;">
                        <button type="submit" class="btn btn-success">Add Payment</button>
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
                    <tr>
                        <td>2025-08-29</td>
                        <td>2000.00</td>
                        <td>August Payment</td>
                    </tr>
                    <tr>
                        <td>2025-08-15</td>
                        <td>3000.00</td>
                        <td>July Payment</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    </section>
</div>
<div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
