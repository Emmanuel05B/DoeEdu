<?php
require '../../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

// Start capturing the HTML content
ob_start();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Report</title>
    <style>

        body {
            font-family: Arial, sans-serif;
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
        .top-right-image {
            position: absolute;
            top: 0;
            right: 0;
            max-height: 130px; /* Adjust the size of the image */
            margin-top: 0px; /* Adjust the space from the top */
            margin-right: 30px; /* Adjust the space from the right */
        }
    </style>
</head>

<body>

        <div>
        <h2 style="display: block; text-align: center;"><b>The DOE weekly participation form</b></h2><hr><br>

                    <table class="table">
                        <tbody>
                           
                            <tr>
                                <td>
                                    <div>
                                    <p><strong>Registration No:</strong> 2022/735117/07</p>
                                    <p><strong>Telephone:</strong> 081 461 8178</p>
                                    <p><strong>Email:</strong> <a href="mailto:thedistributorsofedu@gmail.com">thedistributorsofedu@gmail.com</a></p>
                                    </div>                           
                                </td>

                                <td>
                                    <div>
                                        <!-- Adding the image -->
                                      <img src="images/doe.png" alt="Your Image" class="top-right-image">
                                    </div>                                 
                                </td>

                            </tr>

                        </tbody>
                    </table>

        </div><br>
        <?php
        $statusValue = intval($_GET['val']);  // Ensure it's an integer
          
          // Check the status and render different HTML for each case
          if ($statusValue == 1) {

            $sub = 'Mathematics';
            $grade = '12';

                      $sql = "SELECT lt.*, ls.* 
                      FROM learners AS lt
                      JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                      WHERE lt.Grade = 12 AND lt.Math > 0 AND ls.SubjectId = 1
                      AND ls.ContractExpiryDate > CURDATE()";    

          } else if ($statusValue == 2) {

            $sub = 'Physical Sciences';
            $grade = '12';

              $sql = "SELECT lt.*, ls.* 
              FROM learners AS lt
              JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
              WHERE lt.Grade = 12 AND lt.Physics > 0 AND ls.SubjectId = 2
              AND ls.ContractExpiryDate > CURDATE()";    

          } else if ($statusValue == 3) {

            $sub = 'Mathematics';
            $grade = '11';

              $sql = "SELECT lt.*, ls.* 
                      FROM learners AS lt
                      JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                      WHERE lt.Grade = 11 AND lt.Math > 0 AND ls.SubjectId = 3
                      AND ls.ContractExpiryDate > CURDATE()";    

          } else if ($statusValue == 4) {

            $sub = 'Physical Sciences';
            $grade = '11';

              $sql = "SELECT lt.*, ls.* 
              FROM learners AS lt
              JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
              WHERE lt.Grade = 11 AND lt.Physics > 0 AND ls.SubjectId = 4
              AND ls.ContractExpiryDate > CURDATE()";    


          } else if ($statusValue == 5) {

            $sub = 'Mathematics';
            $grade = '10';

              $sql = "SELECT lt.*, ls.* 
              FROM learners AS lt
              JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
              WHERE lt.Grade = 10 AND lt.Math > 0 AND ls.SubjectId = 5
              AND ls.ContractExpiryDate > CURDATE()";    


          } else if ($statusValue == 6) {

            $sub = 'Physical Sciences';
            $grade = '10';

              $sql = "SELECT lt.*, ls.* 
              FROM learners AS lt
              JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
              WHERE lt.Grade = 10 AND lt.Physics > 0 AND ls.SubjectId = 6
              AND ls.ContractExpiryDate > CURDATE()";    

          } else {
              // Default case if none of the statuses match
              echo '<h1>Learners - Unknown Status</h1>';
          }

          ?>

        <p style="display: block; text-align: center;"><b>Grade: </b><?php echo $grade; ?> <b>     |   Subject: </b><?php echo $sub ?><b>   |     Chapter: </b> ...............................................</p> 
        <p style="display: block; text-align: center;"><b>Total: </b>...........</p>
        <small style="display: block; text-align: center;"><b>Generated on: </b><?php echo date('Y-m-d'); ?></small><br>


     <!-- Main content table---------------------------------------------> 
     <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
 
            <div class="box-header">
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <small>Marks: any number between 0 and the total marks.</small><br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Attendance</th>
                      <th>Reason Provided</th>
                      <th>Submission</th>
                      <th>Reason Provided</th>
                      <th>Enter Mark</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php

                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                            <tr>
                             
                              <td>
                                <?php echo $final['Name'] ?>
                              </td>
                              <td>
                                <?php echo $final['Surname'] ?>
                              </td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>

                          </tr>

                    <?php } ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Attendance</th>
                      <th>Reason Provided</th>
                      <th>Submission</th>
                      <th>Reason Provided</th>
                      <th>Enter Mark</th>
                    </tr>

                  </tfoot>
                </table>

            </div><br>
            <!-- /.box-body -->
            <small>Key.</small><hr>

            <p><b>Attendance: </b><span> <b>A</b> = Absent </span> <span> <b>P</b> = Present </span> <span> <b>L</b> = Late</span>
            <p><b>Reason Provided (for not attending): </b><span> <b>O</b> = Other </span> <span> <b>DI</b> = Data Issues </span>	<span> <b>NP</b> = None Provided</span><hr>

            <p><b>Submission: </b><span> <b>N(no)</b> = Did Not Submit </span> <span> <b>Y(yes)</b> = Submitted </span>
            <p><b>Reason Provided (for not submitting): </b><br><br><span> <b>O</b> = Other   <b>DI</b> = Data Issues   <b>NW</b> = Did Not Write   <b>NP</b> = None Provided</span><hr>
            


          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

</body>
</html>

<?php
$html = ob_get_clean(); // Capture the HTML output

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// Set Paper size and Orientation
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Stream the generated PDF to the browser
$dompdf->stream("learner_report.pdf", ["Attachment" => false]); // Change to true to force download
?>
