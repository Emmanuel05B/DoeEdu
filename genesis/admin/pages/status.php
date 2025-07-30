<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}    
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content ---> 
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content table---------------------------------------------> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <div class="table-responsive"> <!-- the magic!!!! -->
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>StNo</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Grade</th>
                    <th>Math</th>
                    <th>Physics</th>
                    <th>Total Fees</th>
                    <th>Total Paid</th>
                    <th>Total Owe</th>

                  </tr>
                </thead> 

                <tbody>
                <?php
                include('../../partials/connect.php');
      
                $statusValue = intval($_GET['val']);  // Ensure it's an integer
          
                // Check the status and render different HTML for each case
                if ($statusValue == 1) {
                    // Status 1: On Contract and Owing Learners
                    echo '<h3>On Contract and Owing Learners</h3><br>';

                    // SQL query for learners owing money and with unexpired contracts
                    $sql = "SELECT 
                                lt.*, 
                                ls.*, 
                                u.Name, 
                                u.Surname
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            JOIN users AS u ON u.Id = lt.LearnerId
                            WHERE lt.TotalOwe > 0
                              AND ls.ContractExpiryDate = (
                                  SELECT MAX(ls2.ContractExpiryDate)
                                  FROM learnersubject AS ls2
                                  WHERE ls2.LearnerId = ls.LearnerId
                              )
                              AND ls.ContractExpiryDate > CURDATE()";

                            
                } else if ($statusValue == 2) {
                    // Status 2: On Contract and Not Owing Learners
                    echo '<h3>On Contract and Not Owing Learners</h3><br>';

                    // SQL query for learners not owing money and with unexpired contracts
                    $sql = "SELECT 
                                lt.*, 
                                ls.*, 
                                u.Name, 
                                u.Surname
                            FROM learners AS lt
                            JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                            JOIN users AS u ON u.Id = lt.LearnerId
                            WHERE lt.TotalOwe <= 0
                              AND ls.ContractExpiryDate = (
                                  SELECT MAX(ls2.ContractExpiryDate)
                                  FROM learnersubject AS ls2
                                  WHERE ls2.LearnerId = ls.LearnerId
                              )
                              AND ls.ContractExpiryDate > CURDATE()";
                } else if ($statusValue == 3) {
                    // Status 3: Expired Contract and Not Owing Learners
                    echo '<h3>Expired Contract and Not Owing Learners</h3><br>';

                    // SQL query for learners not owing money and with expired contracts
                    $sql = "SELECT 
                        lt.*, 
                        ls.*, 
                        u.Name, 
                        u.Surname
                    FROM learners AS lt
                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                    JOIN users AS u ON u.Id = lt.LearnerId
                    WHERE lt.TotalOwe <= 0
                      AND ls.ContractExpiryDate = (
                          SELECT MAX(ls2.ContractExpiryDate)
                          FROM learnersubject AS ls2
                          WHERE ls2.LearnerId = ls.LearnerId
                      )
                      AND ls.ContractExpiryDate <= CURDATE()";
                } else if ($statusValue == 4) {
                    // Status 4: Expired Contract and Owing Learners
                    echo '<h3>Expired Contract and Owing Learners</h3><br>';

                    // SQL query for learners owing money and with expired contracts
                    $sql = "SELECT 
                        lt.*, 
                        ls.*, 
                        u.Name, 
                        u.Surname
                    FROM learners AS lt
                    JOIN learnersubject AS ls ON lt.LearnerId = ls.LearnerId
                    JOIN users AS u ON u.Id = lt.LearnerId
                    WHERE lt.TotalOwe > 0
                      AND ls.ContractExpiryDate = (
                          SELECT MAX(ls2.ContractExpiryDate)
                          FROM learnersubject AS ls2
                          WHERE ls2.LearnerId = ls.LearnerId
                      )
                      AND ls.ContractExpiryDate <= CURDATE()";

                } else {
                    // Default case if none of the statuses match
                    echo '<h1>Learners - Unknown Status</h1>';
                }
                    
                $results = $connect->query($sql);
                while($final = $results->fetch_assoc()) { ?> 
                <tr>
                    <td><?php echo $final['LearnerId'] ?></td>
                    <td><?php echo $final['Name'] ?></td>
                    <td><?php echo $final['Surname'] ?></td>
                    <td><?php echo $final['Grade'] ?></td>
                    <td><?php echo $final['Math'] ?></td>
                    <td><?php echo $final['Physics'] ?></td>
                    <td><?php echo $final['TotalFees'] ?></td>
                    <td><?php echo $final['TotalPaid'] ?></td>
                    <td> <?php echo $final['TotalOwe'] ?></td>

                </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>StNo</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Grade</th>
                    <th>Math</th>
                    <th>Physics</th>
                    <th>Total Fees</th>
                    <th>Total Paid</th>
                    <th>Total Owe</th>

                  </tr>
                </tfoot>
              </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  $(function () {
    $('#example1').DataTable();
  });
</script>

</body>
</html>  
