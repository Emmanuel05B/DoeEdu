<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Report</title>
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="path/to/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .content-wrapper {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .lead {
            font-weight: bold;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include("adminpartials/head.php");
include("adminpartials/header.php");
include("adminpartials/mainsidebar.php");

include('../partials/connect.php');

$parentId = isset($_GET['pid']) ? $_GET['pid'] : null;
$learner_id = isset($_GET['lid']) ? $_GET['lid'] : null;

if ($parentId) {
    $psql = "SELECT * FROM users WHERE Id = $parentId";
    $presults = $connect->query($psql);
    $pfinal = $presults->fetch_assoc();
}

include('../teacher/shared.php');

?>

<div class="content-wrapper">
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> Report for: <?php echo $final['Name']; ?>
                    <small class="pull-right"><?php echo $dfinal['CreatedAt']; ?></small>
                </h2>
            </div>
        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <b>Learner Details:</b><br>
                <b>Name:</b> <?php echo $final['Name']; ?><br>
                <b>Surname:</b> <?php echo $final['Surname']; ?><br>
                <b>Date Of Birth:</b> <?php echo $final['DateOfBirth']; ?><br>
                <b>Gender:</b> <?php echo $final['Gender']; ?><br>
                <b>Diagnosis:</b> <?php echo $final['FunctionalLevel']; ?>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Teacher Details:</b><br>
                <b>Name:</b> <?php echo $tfinal['Name']; ?><br>
                <b>Surname:</b> <?php echo $tfinal['Surname']; ?><br>
                <b>Email:</b> <?php echo $tfinal['Email']; ?>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Parent Details:</b><br>
                <b>Name:</b> <?php echo $pfinal['Name']; ?><br>
                <b>Surname:</b> <?php echo $pfinal['Surname']; ?><br>
                <b>Email:</b> <?php echo $pfinal['Email']; ?>
            </div>
        </div>
        
        <hr><br>

        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Attendance:</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Attendance Rate:</th>
                            <td><?php echo $attendancerate; ?>%</td>
                        </tr>
                        <tr>
                            <th>Classes missed:</th>
                            <td><?php echo $numabsent; ?>/<?php echo $total; ?> classes</td>
                        </tr>
                    </table>
                </div>
            </div>
             
            <div class="col-xs-6">
                <p class="lead">Participation</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Engagement Level:</th>
                            <td><?php echo $engagementStatus; ?></td>
                        </tr>
                        <tr>
                            <th>Independence Level:</th>
                            <td><?php echo $independenceStatus; ?></td>

                        </tr>
                    </table>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Activities Scores:</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Life Skills:</th>
                            <td><?php echo $LifeSkillsAverage; ?>%</td>
                        </tr>
                        <tr>
                            <th>Sensory Integration:</th>
                            <td><?php echo $SensoryIntegrationAverage; ?>%</td>
                        </tr>
                        <tr>
                            <th style="width:50%">Story Time:</th>
                            <td><?php echo $StoryTimeAverage; ?>%</td>
                        </tr>
                        <tr>
                            <th>Outdoor Activities:</th>
                            <td><?php echo $OutdoorAverage; ?>%</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-xs-6">
                <p class="lead">Classroom Transition</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%"> Day 5:</th>
                            <td><?php echo $d5; ?></td>
                        </tr>
                        <tr>
                            <th style="width:50%">Day 4:</th>
                            <td><?php echo $d4; ?></td>
                        </tr>
                        <tr>
                            <th style="width:50%">Day 3: </th>
                            <td><?php echo $d3; ?></td>
                        </tr>
                        <tr>
                            <th style="width:50%">Day 2:</th>
                            <td><?php echo $d2; ?> </td>
                        </tr>
                        <tr>
                            <th style="width:50%">Day 1: </th>
                            <td><?php echo $d1; ?></td>
                        </tr>
                        
                    </table>
                </div>
            </div>
        </div><br>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nr</th>
                            <th>Question</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Plays alongside peers</td>
                            <td><?php echo $dfinal['PAP']; ?></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Recognizes familiar sounds</td>
                            <td><?php echo $dfinal['RFS']; ?></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Makes eye contact, listens and reacts</td>
                            <td><?php echo $dfinal['EyeContact']; ?></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Responds appropriately to basic classroom instructions</td>
                            <td><?php echo $dfinal['AppropriateResponse']; ?></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Shows appropriate emotions in a given situation</td>
                            <td><?php echo $dfinal['AppropriateEmotions']; ?></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Listens and responds to simple routine instructions</td>
                            <td><?php echo $dfinal['ListenInstructions']; ?></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Listens to speaker without interrupting</td>
                            <td><?php echo $dfinal['ListensNotInterupting']; ?></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Sometimes partakes spontaneously</td>
                            <td><?php echo $dfinal['PartakesSpontaneously']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
               
                <form action="generate_pdf.php" method="post" style="display:inline;">
                    <input type="hidden" name="parentId" value="<?php echo $parentId; ?>">
                    <input type="hidden" name="learnerId" value="<?php echo $learner_id; ?>">
                    
                </form>
            </div>
        </div>
    </section>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
</body>
</html>
