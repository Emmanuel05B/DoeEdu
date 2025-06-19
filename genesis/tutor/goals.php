<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
?>

<?php include("tutorpartials/head.php"); ?>

<style>
    /* Tabs */
    .nav-pills .nav-link {
        text-align: center;
        color: #333;
    }

    .nav-pills .nav-link.parent {
        background-color: #dfe6e9;
    }

    .content {
        background-color: white;
        margin-top: 20px;
        margin-left: 100px;
        margin-right: 100px;
    }

    .pos {
        margin-top: 30px;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-group textarea {
        width: 100%;
        padding: 8px;
        height: 65px;
        resize: vertical;
    }
    .submitbtn {
        padding: 10px 20px;
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }

    .button {
        display: inline-block;
        padding: 4px 8px;
        background-color: blue; /* Blue color */
        color: #fff; /* White text color */
        text-decoration: none; /* Remove underline */
        border-radius: 4px; /* Rounded corners */
        transition: background-color 0.3s ease; /* Smooth transition */
    }

    .button:hover {
        background-color: #0056b3; /* Darker blue color on hover */
    }
    .horizontal-container {
    display: flex;
    align-items: center; /* Vertically center items */
    gap: 10px; /* Space between items */
   
    }
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php include("tutorpartials/header.php"); ?>
        <!-- Left side column. contains the logo and sidebar -->
        <?php include("tutorpartials/mainsidebar.php"); ?>
        <!-- Content Wrapper. Contains page content ##start -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content">
                <div class="container-fluid">
                    <div class="order">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                   <!-- <li class="nav-item"><a class="nav-link parent" href="#viewgoal" data-toggle="tab" style="border: 2px solid gray;">Goals set</a></li> -->
                                    <!-- <li class="nav-item"><a class="nav-link teacher" href="#OverallAverage" data-toggle="tab" style="border: 2px solid gray;">Overall Avarage</a></li> -->

                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Set Goal Tab -->
                                     
                                    <div class="tab-pane active" id="viewgoal">
                                    <div class="pos">
                                            <div class="box box-info">
                                                <div class="box-header with-border">
                                                    <h3>Goals Set</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th style="width: 10px">#</th>
                                                            <th>Goal Type</th>
                                                            <th>Progress</th>
                                                            <th style="width: 40px">Label</th>
                                                            <th>Time Left:</th>
                                                            <th>Status</th>
                                                            <th>Update Current Score (%)</th>
                                                        </tr>
                                                        <?php
                                                        include('../partials/connect.php');
                                                        $learnerId = $_GET['id'];
                                                        $sql = "SELECT * FROM goals WHERE LearnerId = $learnerId"; 
                                                        $results = $connect->query($sql);
                                                        while($final = $results->fetch_assoc()) { ?>

                                                       <?php 
                                                         $currentTime = time();

                                                         // Example data (replace with your actual data from the database)
                                                         $goalTargetTimestamp = strtotime($final['GoalTargetDate']); // Goal target date timestamp
                                                         $goalSetTimestamp = strtotime($final['GoalSetDate']);       // Goal set date timestamp
                                                         
                                                         // Calculate the remaining time between the current time and the goal target date
                                                         $timeRemaining = $goalTargetTimestamp - $currentTime;
                                                         
                                                         $inDays = floor($timeRemaining / 86400); // Number of days
                                                         $inWeeks = floor($timeRemaining / (7 * 86400)); // Number of weeks
                                                         $inMonths = floor($timeRemaining / (30 * 86400)); // Approximate number of months
                                                         
                                                         // Determine the appropriate time unit to display
                                                         if ($timeRemaining < 604800) { // Less than a week
                                                             $elapsedTime = $inDays . ' day/s';
                                                         } elseif ($timeRemaining < 2592000) { // Less than a month
                                                             $elapsedTime = $inWeeks . ' week/s';
                                                         } else {
                                                             $elapsedTime = $inMonths . ' month/s';
                                                         }
                                                          
                                                        ?>

                                                                         
                                                            <tr>
                                                                <td><?php echo $final['GoalId']; ?></td>
                                                                <td><?php echo $final['Subject']; ?></td>
                                                                <td>
                                                                    <div class="progress progress-xs">
                                                                        <div class="progress-bar progress-bar-yellow" style="width: <?php echo $final['CurrentPercentage']; ?>%"></div>
                                                                    </div>
                                                                </td>
                                                                <td><span class="badge bg-yellow"><?php echo $final['CurrentPercentage']; ?>%</span></td>
                                         
                                                                <td><span class="badge"><?php echo $elapsedTime; ?></span></td>

                                                             
                                                                <td><?php echo $final['Status']; ?></td>
                                                               
                                                                <td>
                                                                    <form action="updategoal.php" method="POST" class="horizontal-container">
                                                                        <input type="number" class="form-control2" id="currentscore" name="currentscore" min="0" max="100" required>
                                                                        <input type="hidden" name="goalid" value="<?php echo $final['GoalId']; ?>">
                                                                        <input type="hidden" name="learnerid" value="<?php echo $learnerId; ?>">
                                                                        
                                                                        <button type="submit" name="update" class="button btn btn-primary py-3 px-4">Update</button>
                                                                    </form>
                                                                </td>
                                                                
                                                            </tr>
                                                        <?php } ?>
                                                    </table> <br><br>

                                                    <a href="tracklearnerprogress.php?id=<?php echo $learnerId ?>" class="btn btn-primary btn-block">Track Overall Avarage</a>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- View Goals Tab -->
                                    <div class="tab-pane" id="OverallAverage">

                                    <div class="pos">
                                            <div class="box box-info">   
                                                <?php
                                               // $learnerId = $_GET['id'];
                                                ?>
                                               
                                            </div>
                                    </div>

                                    </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                </div>
            </section>
        </div>
        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <?php include("adminpartials/queries.php"); ?>
    <script src="dist/js/demo.js"></script>
</body>
</html>
