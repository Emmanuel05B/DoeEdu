<?php
require '../../vendor/autoload.php'; 
use Dompdf\Dompdf;

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');

$parentId = isset($_POST['parentId']) ? $_POST['parentId'] : null;
$learner_id = isset($_POST['learnerId']) ? $_POST['learnerId'] : null;

if ($parentId) {
    $psql = "SELECT * FROM parents WHERE ParentId = $parentId";
    $presults = $connect->query($psql);
    $pfinal = $presults->fetch_assoc();
}

    include('../admin/newshared.php');


ob_start(); // Start output buffering

// Include HTML for the PDF
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learner Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .two-cell { width: 100%; }

        .f-cell {
        width: 100%;
        }
        .f-cell td,
        .f-cell th {
            border: none;
            padding: 2px;
            width: 2px;
        }
    </style>
</head>
<body>
    <h2>Report for: <?php echo $final['Name']; ?></h2>
    <small><?php echo $dfinal['CreatedAt']; ?></small>

        <div class="column">
            <h3>Learner Details:</h3>
            <p>
                <b>Learner Details:</b><br>
                <b>Name:</b> <?php echo $final['Name']; ?><br>
                <b>Surname:</b> <?php echo $final['Surname']; ?><br>
                <b>Grade:</b> <?php echo $final['Grade']; ?><br>
                <b>Contact Number:</b> <?php echo $final['ContactNumber']; ?><br>
                <b>Email:</b> <?php echo $final['Email']; ?>
            </p>
        </div>
    

    <table class="f-cell" style="table-layout: fixed;">
      <tr>
            <td style="width: 50%;">
                <h3>Teacher Details:</h3>
                <table>
                    <tr>
                        <th>Name:</th>
                        <td><?php echo $tfinal['Name']; ?></td>
                    </tr>
                    <tr>
                        <th>Surname:</th>
                        <td><?php echo $tfinal['Surname']; ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo $tfinal['Email']; ?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <h3>Parent Details:</h3>
                <table>
                    <tr>
                        <th>TITLE:</th>
                        <td><?php echo $pfinal['ParentTitle']; ?></td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td><?php echo $pfinal['ParentName']; ?></td>
                    </tr>
                    <tr>
                        <th>Surname:</th>
                        <td><?php echo $pfinal['ParentSurname']; ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo $pfinal['ParentEmail']; ?></td>
                    </tr>
                </table>
            </td>
      </tr>
    </table><hr><br>


    <table class="two-cell" style="table-layout: fixed;">
      <tr>
            <td style="width: 50%;">
                <h3>Attendance:</h3>
                <table>
                    <tr>
                        <th>Attendance Rate:</th>
                        <td><?php echo $attendancerate; ?>%</td>
                        </tr>
                    <tr>
                        <th>Classes missed:</th>
                        <td><?php echo $numabsent; ?>/<?php echo $total; ?> classes</td>

                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <h3>Participation:</h3>
                <table>
                <tr>
                    <th style="width:50%">Engagement Level:</th>
                    <td><?php echo $engagementStatus; ?></td>
                </tr>
                <tr>
                    <th>Independence Level:</th>
                    <td><?php echo $independenceStatus; ?></td>

                </tr>
                </table>
            </td>
      </tr>
    </table>


<table class="two-cell" style="table-layout: fixed;">
<tr>
    
        <td style="width: 50%;">
            <h3>Classroom Transition:</h3>
            <table>
                
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
        </td>

        <td style="width: 50%;">
            <h3>Activities Scores:</h3>
            <table>
                <tr>
                    <th>Life Skills:</th>
                    <td>52%</td>
                </tr>
                <tr>
                    <th>Sensory Integration:</th>
                    <td>62%</td>
                </tr>
                <tr>
                    <th>Story Time:</th>
                    <td>22%</td>
                </tr>
                <tr>
                    <th>Outdoor Activities:</th>
                    <td>72%</td>
                </tr>
            </table>
        </td>

    </tr>
</table>

 

    <h3>Details:</h3>
    <table>
        <thead>
            <tr>
                <th>Nr</th>
                <th>Assessment Items</th>
                <th>E A I M G</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>yyyyyyyyyyyyyyy</td>
            <td><?php echo 52; ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>xxxxxxxxxxxxxxx</td>
            <td><?php echo 50; ?></td>
        </tr>
        </tbody>
    </table>
</body>
</html>
<?php

$html = ob_get_clean(); // Get buffered content
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("learner_report.pdf", ["Attachment" => true]);
?>
