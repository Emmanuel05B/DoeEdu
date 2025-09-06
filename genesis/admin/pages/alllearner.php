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
<?php include('../../partials/connect.php'); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Class List <small>Learners</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Class List</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">

              <?php
              // Input parameters
              $subjectId = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
              $grade     = isset($_GET['grade']) ? $_GET['grade'] : '';
              $group     = isset($_GET['group']) ? $_GET['group'] : '';

              // Get subject name
              $subjectName = '';
              if ($subjectId > 0) {
                  $stmtSub = $connect->prepare("SELECT SubjectName FROM subjects WHERE SubjectId = ?");
                  $stmtSub->bind_param("i", $subjectId);
                  $stmtSub->execute();
                  $resSub = $stmtSub->get_result();
                  if ($resSub && $resSub->num_rows > 0) {
                      $subRow = $resSub->fetch_assoc();
                      $subjectName = $subRow['SubjectName'];
                  }
                  $stmtSub->close();
              }

              echo "<h3 class='box-title'>$grade - $subjectName, Group $group</h3>";
              ?>

            </div>

            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-hover">
                  <thead style="background-color: #d1d9ff;">
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th>Group</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Working query: only active learners (contract not expired) ... for this grade, subject and group

                    $sql = "
                        SELECT 
                            lt.LearnerId,
                            lt.Grade,
                            u.Name,
                            u.Surname,
                            c.GroupName
                        FROM learners lt
                        INNER JOIN users u ON lt.LearnerId = u.Id
                        INNER JOIN learnersubject ls 
                            ON lt.LearnerId = ls.LearnerId 
                            AND ls.ContractExpiryDate > CURDATE()
                        INNER JOIN learnerclasses lc 
                            ON lt.LearnerId = lc.LearnerID
                        INNER JOIN classes c 
                            ON lc.ClassID = c.ClassID
                            AND c.SubjectID = ls.SubjectID
                        WHERE ls.SubjectId = ? AND lt.Grade = ? AND c.GroupName = ?
                    ";

                    $stmt = $connect->prepare($sql);
                    $stmt->bind_param("iss", $subjectId, $grade, $group);
                    $stmt->execute();
                    $results = $stmt->get_result();

                    if ($results && $results->num_rows > 0):
                        while ($final = $results->fetch_assoc()):
                    ?>

                    <tr>
                      <td><?php echo htmlspecialchars($final['LearnerId']); ?></td>
                      <td><?php echo htmlspecialchars($final['Name']); ?></td>
                      <td><?php echo htmlspecialchars($final['Surname']); ?></td>
                      <td><?php echo htmlspecialchars($final['Grade']); ?></td>
                      <td><?php echo htmlspecialchars($final['GroupName']); ?></td>
                      <td class="text-center">
                        <a href="tracklearnerprogress.php?id=<?php echo $final['LearnerId']; ?>&val=<?php echo $subjectId; ?>" class="btn btn-xs btn-primary">Track Progress</a>
                        <a href="learnerprofile.php?id=<?php echo $final['LearnerId']; ?>&val=<?php echo $subjectId; ?>" class="btn btn-xs btn-success">Open Profile</a>
                      </td>
                    </tr>
                    <?php
                      endwhile;
                    else:
                    ?>
                      <tr>
                        <td colspan="6" class="text-center">No learners found for this class.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                  <tfoot style="background-color: #f9f9f9;">
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th>Group</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <!-- Buttons row -->
              <div class="row" style="margin-top:15px;">
                  <div class="col-xs-6">
                      <a href="classform.php?subject=<?php echo $_GET['subject']; ?>" class="btn btn-primary btn-block">
                          Create Class Form
                      </a>
                  </div>
                  <div class="col-xs-6">
                      <!-- Button to open modal -->
                      <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#expiredModal">
                          Expired Contract List
                      </button>
                  </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Expired Learners Modal -->
<div class="modal fade" id="expiredModal" tabindex="-1" role="dialog" aria-labelledby="expiredModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#f44336; color:#fff;">
        <h5 class="modal-title" id="expiredModalLabel">Expired Contract Learners</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead style="background-color: #fdd;">
              <tr>
                <th>StNo.</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Grade</th>
                <th>Group</th>
                <th>Contract Expiry</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Query expired learners
              $sqlExpired = "
                  SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName, ls.ContractExpiryDate
                  FROM learners lt
                  INNER JOIN users u ON lt.LearnerId = u.Id
                  LEFT JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                  LEFT JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                  LEFT JOIN classes c ON lc.ClassID = c.ClassID
                  WHERE 1
              ";

              if ($subjectId > 0) {
                  $sqlExpired .= " AND ls.SubjectId = ?";
              }
              if ($grade !== '') {
                  $sqlExpired .= " AND lt.Grade = ?";
              }
              if ($group !== '') {
                  $sqlExpired .= " AND c.GroupName = ?";
              }

              // Expired contracts
              $sqlExpired .= " AND ls.ContractExpiryDate <= CURDATE()";

              $stmtExp = $connect->prepare($sqlExpired);

              // Bind params dynamically
              $bindTypes = '';
              $bindValues = [];
              if ($subjectId > 0) { $bindTypes .= 'i'; $bindValues[] = $subjectId; }
              if ($grade !== '') { $bindTypes .= 's'; $bindValues[] = $grade; }
              if ($group !== '') { $bindTypes .= 's'; $bindValues[] = $group; }

              if (!empty($bindValues)) {
                  $stmtExp->bind_param($bindTypes, ...$bindValues);
              }

              $stmtExp->execute();
              $expiredResults = $stmtExp->get_result();

              if ($expiredResults && $expiredResults->num_rows > 0):
                  while ($row = $expiredResults->fetch_assoc()):
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row['LearnerId']); ?></td>
                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                <td><?php echo htmlspecialchars($row['Surname']); ?></td>
                <td><?php echo htmlspecialchars($row['Grade']); ?></td>
                <td><?php echo htmlspecialchars($row['GroupName']); ?></td>
                <td><?php echo htmlspecialchars($row['ContractExpiryDate']); ?></td>
              </tr>
              <?php
                  endwhile;
              else:
              ?>
              <tr>
                <td colspan="6" class="text-center">No expired contracts found.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  $(function () {
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });
  });
</script>

</body>
</html>
