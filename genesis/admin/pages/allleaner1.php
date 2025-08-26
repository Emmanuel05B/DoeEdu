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

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Left side column. contains the logo and sidebar -->
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
       <h1>Class List <small>Learners</small></h1>
        <ol class="breadcrumb">
          <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Class List</li>
        </ol>
    <?php include('../../partials/connect.php'); ?> 
    </section>


    <!-- Main content table---------------------------------------------> 
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">

            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive"> <!-- the magic!!!! -->
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th>Group/Class</th>
                      <th>Progress</th>
                      <th>More</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $subjectId = intval($_GET['subject']);  // Subject ID
                      $grade = intval($_GET['grade']);        // Grade
                      $group = $_GET['group'];                // GroupName (e.g., A, B, C)

                      $sql = "";
                      $header = "";

                      if ($subjectId == 1) {
                          $header = "Grade 10 Mathematics,  Group-{$group} Learners";
                          $sql = "
                              SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                              FROM learners lt
                              JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                              JOIN users u ON lt.LearnerId = u.Id
                              JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                              JOIN classes c ON lc.ClassID = c.ClassID
                              WHERE lt.Grade = $grade
                                AND lt.Math > 0
                                AND ls.SubjectId = 1
                                AND ls.ContractExpiryDate > CURDATE()
                                AND c.GroupName = '$group'
                          ";
                      } else if ($subjectId == 2) {
                          $header = "Grade 11 Mathematics, Group-{$group} Learners";
                          $sql = "
                              SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                              FROM learners lt
                              JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                              JOIN users u ON lt.LearnerId = u.Id
                              JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                              JOIN classes c ON lc.ClassID = c.ClassID
                              WHERE lt.Grade = $grade
                                AND lt.Math > 0
                                AND ls.SubjectId = 2
                                AND ls.ContractExpiryDate > CURDATE()
                                AND c.GroupName = '$group'
                          ";
                      } else if ($subjectId == 3) {
                          $header = "Grade 12 Mathematics, Group-{$group} Learners";
                          $sql = "
                              SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                              FROM learners lt
                              JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                              JOIN users u ON lt.LearnerId = u.Id
                              JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                              JOIN classes c ON lc.ClassID = c.ClassID
                              WHERE lt.Grade = $grade
                                AND lt.Math > 0
                                AND ls.SubjectId = 3
                                AND ls.ContractExpiryDate > CURDATE()
                                AND c.GroupName = '$group'
                          ";
                      } else if ($subjectId == 4) {
                          $header = "Grade 10 Physical Sciences, Group-{$group} Learners";
                          $sql = "
                              SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                              FROM learners lt
                              JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                              JOIN users u ON lt.LearnerId = u.Id
                              JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                              JOIN classes c ON lc.ClassID = c.ClassID
                              WHERE lt.Grade = $grade
                                AND lt.Physics > 0
                                AND ls.SubjectId = 4
                                AND ls.ContractExpiryDate > CURDATE()
                                AND c.GroupName = '$group'
                          ";
                      } else if ($subjectId == 5) {
                          $header = "Grade 11 Physical Sciences, Group-{$group} Learners";
                          $sql = "
                              SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                              FROM learners lt
                              JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                              JOIN users u ON lt.LearnerId = u.Id
                              JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                              JOIN classes c ON lc.ClassID = c.ClassID
                              WHERE lt.Grade = $grade
                                AND lt.Physics > 0
                                AND ls.SubjectId = 5
                                AND ls.ContractExpiryDate > CURDATE()
                                AND c.GroupName = '$group'
                          ";
                      } else if ($subjectId == 6) {
                          $header = "Grade 12 Physical Sciences, Group-{$group} Learners";
                          $sql = "
                              SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
                              FROM learners lt
                              JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
                              JOIN users u ON lt.LearnerId = u.Id
                              JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
                              JOIN classes c ON lc.ClassID = c.ClassID
                              WHERE lt.Grade = $grade
                                AND lt.Physics > 0
                                AND ls.SubjectId = 6
                                AND ls.ContractExpiryDate > CURDATE()
                                AND c.GroupName = '$group'
                          ";
                      } else {
                          $header = "Learners - Unknown Status";
                      }

                      if (!empty($header)) {
                          echo "<h4>$header</h4><br>";
                      }

                      $results = $connect->query($sql);

                      while($final = $results->fetch_assoc()) { ?>
                          <tr>
                            <td><?php echo $final['LearnerId']; ?></td>
                            <td><?php echo $final['Name']; ?></td>
                            <td><?php echo $final['Surname']; ?></td>
                            <td><?php echo $final['Grade']; ?></td>
                            <td><?php echo $final['GroupName']; ?></td>
                            <td>
                              <p>
                                <a href="tracklearnerprogress.php?id=<?php echo $final['LearnerId']; ?>&val=<?php echo $subjectId; ?>" class="btn btn-xs btn-primary">
                                  Track Progress
                                </a>
                              </p>
                            </td>
                            <td>
                              <p>
                                <a href="learnerprofile.php?id=<?php echo $final['LearnerId']; ?>&val=<?php echo $subjectId; ?>" class="btn btn-xs btn-primary">
                                  Open Profile
                                </a>
                              </p>
                            </td>
                          </tr>
                      <?php } ?>

                  </tbody>


                  <tfoot>
                    <tr>
                      <th>StNo.</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Grade</th>
                      <th>Group/Class</th>
                      <th>Progress</th>
                      <th>More</th>
                    </tr>

                  </tfoot>
                </table>
                </div>
                <p><a href="classform.php?val=<?php echo $_GET['subject'] ?>" class="btn btn-block btn-primary">Create Class Form</a></p>
                <p><a href="expiredclasslist.php?val=<?php echo $_GET['subject'] ?>" class="btn btn-block btn-primary">Expired contract List</a></p>


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

  </div>

</div>

<!-- jQuery 3 -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


<script>
  $(function () {
    $('#example1').DataTable()
  })
</script>

</body>
</html>
