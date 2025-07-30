<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    .example-modal .modal {
        position: relative;
        top: auto;
        bottom: auto;
        right: auto;
        left: auto;
        display: block;
        z-index: 1;
    }

    .example-modal .modal {
        background: transparent !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid black;
        text-align: center;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    td {
        height: 40px;
    }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content. -->
  <div class="content-wrapper">
    
    <!-- Main content -->
    <section class="content">

    <?php
      include(__DIR__ . "/../../partials/connect.php");

    ?>
            


     <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                
                <h4 class="modal-title"><p><strong>Old Class List</strong></p></h4><br>

              </div>
              <div class="modal-body">
            

              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Registered On</th>
                      <th>Expired On</th>
                      <th>More</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                       // $sql = "SELECT * FROM learners WHERE Grade = 10 AND Math > 0";


                        $statusValue = intval($_GET['val']);  // Ensure it's an integer
          
                        // Check the status and render different HTML for each case
                        if ($statusValue == 1) {
                          
                            echo '<h4>Grade 12 Mathematics Learners With Expired Contracts</h4><br>';

                                    $sql = "SELECT lt.*, ls.* 
                                    FROM learners AS lt
                                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                                    WHERE lt.Grade = 12 AND lt.Math > 0 AND ls.SubjectId = 1
                                    AND ls.ContractExpiryDate < CURDATE()";    

                        } else if ($statusValue == 2) {

                            echo '<h4>Grade 12 Physical Sciences Learners With Expired Contracts</h4><br>';
        
                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 12 AND lt.Physics > 0 AND ls.SubjectId = 2
                            AND ls.ContractExpiryDate < CURDATE()";    

                        } else if ($statusValue == 3) {
                            echo '<h4>Grade 11 Mathematics Learners With Expired Contracts</h4><br>';
        
                            $sql = "SELECT lt.*, ls.* 
                                    FROM learners AS lt
                                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                                    WHERE lt.Grade = 11 AND lt.Math > 0 AND ls.SubjectId = 3
                                    AND ls.ContractExpiryDate < CURDATE()";    

                        } else if ($statusValue == 4) {
                            echo '<h4>Grade 11 Physical Sciences Learners With Expired Contracts</h4><br>';

                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 11 AND lt.Physics > 0 AND ls.SubjectId = 4
                            AND ls.ContractExpiryDate < CURDATE()";    


                        } else if ($statusValue == 5) {
                            echo '<h4>Grade 10 Mathematics Learners With Expired Contracts</h4><br>';

                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 10 AND lt.Math > 0 AND ls.SubjectId = 5
                            AND ls.ContractExpiryDate < CURDATE()";    

         
                        } else if ($statusValue == 6) {
                            echo '<h4>Grade 10 Physical Sciences Learners With Expired Contracts</h4><br>';
        
                            $sql = "SELECT lt.*, ls.* 
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            WHERE lt.Grade = 10 AND lt.Physics > 0 AND ls.SubjectId = 6
                            AND ls.ContractExpiryDate < CURDATE()";    

                        } else {
                            // Default case if none of the statuses match
                            echo '<h1>Learners - Unknown Status</h1>';
                        }

                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                            <tr>
                              <td>
                                <?php echo $final['Name'] ?>
                              </td>
                              <td>
                                <?php echo $final['Surname'] ?>
                              </td>

                              <td>
                                <?php echo $final['RegistrationDate'] ?>
                              </td>

                              <td> 
                              <?php echo $final['ContractExpiryDate'] ?>
                              </td>
                          
                              <td>
                                <p><a href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>&val=<?php echo $_GET['val'] ?>" class="btn btn-block btn-primary">Profile</a></p>
                              </td>

                          </tr>

                    <?php } ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Registered On</th>
                      <th>Expired On</th>
                      <th>More</th>
                    </tr>

                  </tfoot>
                </table>
                    <br>
                                   
         
             
              </div>
              <div class="modal-footer">
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

    </section>
    <!-- /.content -->
  </div>
  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


<script>
  $(document).ready(function() {
      $('#modal-default').modal('show'); // Show the modal immediately on page load

      // Close the modal and redirect when clicking on the backdrop
      $('.modal').on('click', function (e) {
          // Check if the click target is the modal backdrop
          if ($(e.target).is('.modal')) {
              window.location.href = 'alllearner.php?val=<?php echo $_GET['val'] ?>'; 
          }
      });
  });
</script>
</body>
</html>
