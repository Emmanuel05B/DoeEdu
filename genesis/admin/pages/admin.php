<!DOCTYPE html>
<html>
<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(COMMON_PATH . "/../partials/head.php");  
include_once(BASE_PATH . "/partials/connect.php");


// Fetch all tutors 
$tutors = [];
$sqlTutors = "
    SELECT 
        t.TutorId, u.Name, u.Surname
    FROM tutors t
    JOIN users u ON t.TutorId = u.Id
    ORDER BY u.Surname ASC, u.Name ASC
";
$resultTutors = $connect->query($sqlTutors);
if ($resultTutors) {
    while ($row = $resultTutors->fetch_assoc()) {
        $tutors[] = $row;
    }
} else {
    die("Failed to fetch tutors: " . $connect->error);
}

// Fetch all subjects
$subjects = [];
$sqlSubjects = "
    SELECT SubjectId, SubjectName 
    FROM subjects
    ORDER BY SubjectName ASC
";
$resultSubjects = $connect->query($sqlSubjects);
if ($resultSubjects) {
    while ($row = $resultSubjects->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    die("Failed to fetch subjects: " . $connect->error);
}
?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

  <?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
  <?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>


    <div class="content-wrapper">
   
        <section class="content-header">
          <h1>
            Administration
            <small>Admin</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Administration</li>
          </ol>
        </section>
        <section class="content">
          <div class="row">
            <div class="col-md-12"> 
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#add" data-toggle="tab">Add Users</a></li>
                  <li><a href="#update" data-toggle="tab">Update Users</a></li>
                  <li><a href="#delete" data-toggle="tab">Delete Users</a></li>
                  <li><a href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
                <div class="tab-content">

                  <!-- Add Users -->
                  <div class="active tab-pane" id="add">
                    <div class="profile-personal-info">
                      <h3 class="text-primary mb-4">Register</h3>
                      
                      <div class="row">

                        <!-- Add Learners -->
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                          <div class="small-box" style="background-color:#d9d2f5; color:#4b2e83;">
                            <div class="inner text-center">
                              <h5>Add Learners</h5>
                            </div>
                            <a href="addlearners.php" class="small-box-footer" style="color:#4b2e83;">Go <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <!-- Add Tutors -->
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                          <div class="small-box" style="background-color:#e0d4f7; color:#3a1f70;">
                            <div class="inner text-center">
                              <h5>Add Tutors</h5>
                            </div>
                            <a href="addtutor.php" class="small-box-footer" style="color:#3a1f70;">Go <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <!-- Add School -->
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                          <div class="small-box" style="background-color:#f0e6fa; color:#6c4a97;">
                            <div class="inner text-center">
                              <h5>Add School</h5>
                            </div>
                            <a href="addschool.php" class="small-box-footer" style="color:#6c4a97;">Go <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <!-- Manage Requests -->
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                          <div class="small-box" style="background-color:#e8e0f8; color:#4b2e83;">
                            <div class="inner text-center">
                              <h5>Manage Requests</h5>
                            </div>
                            <a href="manage_inviterequests.php" class="small-box-footer" style="color:#4b2e83;">Go <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <!-- Manage Verifications -->
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                          <div class="small-box" style="background-color:#e8e0f8; color:#4b2e83;">
                            <div class="inner text-center">
                              <h5>Verifications</h5>
                            </div>
                            <a href="pendingverifications.php" class="small-box-footer" style="color:#4b2e83;">Go <i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>


                      </div>

                    </div>
                  </div>

                  <!-- Update Users -->
                  <div class="tab-pane" id="update">
                    <div class="profile-personal-info">
                      <h3 class="text-primary mb-4">Update</h3>
                    
                        <div class="row">

                          <!-- Learners List -->
                          <div class="col-md-6">
                            <div class="box">
                              <div class="box-header with-border">
                                <?php
                                  $stmt = $connect->prepare("
                                      SELECT lt.*, u.Name, u.Surname
                                      FROM learners lt
                                      JOIN users u ON lt.LearnerId = u.Id
                                  ");
                                  $stmt->execute();
                                  $results = $stmt->get_result();

                                  echo "<h3 class='box-title'>Learners List</h3>";
                                ?>
                              </div>

                              <div class="box-body">
                                <div class="table-responsive">
                                  <table id="learnersTable" class="table table-bordered table-hover">
                                    <thead style="background-color: #d1d9ff;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Grade</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php if ($results && $results->num_rows > 0): ?>
                                        <?php while($final = $results->fetch_assoc()): ?>
                                          <tr>
                                            <td><?php echo htmlspecialchars($final['Name']) ?></td>
                                            <td><?php echo htmlspecialchars($final['Surname']) ?></td>
                                            <td><?php echo htmlspecialchars($final['Grade']) ?></td>
                                            <td class="text-center">
                                              <a href="updatelearner.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-xs btn-warning">Update</a>
                                              <a href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-xs btn-primary">Profile</a>
                                            </td>
                                          </tr>
                                        <?php endwhile; ?>
                                      <?php else: ?>
                                        <tr>
                                          <td colspan="4" class="text-center">No learners found.</td>
                                        </tr>
                                      <?php endif; ?>
                                    </tbody>
                                    <tfoot style="background-color: #f9f9f9;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Grade</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Tutors List -->
                          <div class="col-md-6">
                            <div class="box">
                              <div class="box-header with-border">
                                <?php
                                  $stmt = $connect->prepare("
                                      SELECT lt.*, u.Name, u.Surname
                                      FROM tutors lt
                                      JOIN users u ON lt.TutorId = u.Id
                                  ");
                                  $stmt->execute();
                                  $results = $stmt->get_result();

                                  echo "<h3 class='box-title'>Tutors List</h3>";
                                ?>
                              </div>

                              <div class="box-body">
                                <div class="table-responsive">
                                  <table id="tutorsTable" class="table table-bordered table-hover">
                                    <thead style="background-color: #d1d9ff;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php if ($results && $results->num_rows > 0): ?>
                                        <?php while($final = $results->fetch_assoc()): ?>
                                          <tr>
                                            <td><?php echo htmlspecialchars($final['Name']) ?></td>
                                            <td><?php echo htmlspecialchars($final['Surname']) ?></td>
                                            <td class="text-center">
                                              <a href="updatetutors.php?id=<?php echo $final['TutorId'] ?>" class="btn btn-xs btn-warning">Update</a>
                                            </td>
                                          </tr>
                                        <?php endwhile; ?>
                                      <?php else: ?>
                                        <tr>
                                          <td colspan="5" class="text-center">No tutors found.</td>
                                        </tr>
                                      <?php endif; ?>
                                    </tbody>
                                    <tfoot style="background-color: #f9f9f9;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                     
                    </div>
                  </div>

                  <!-- Delete Users -->
                  <div class="tab-pane" id="delete">
                    <div class="profile-personal-info">
                      <h3 class="text-danger mb-4">Delete </h3>
                    
                        <div class="row">

                          <!-- Learners List -->
                          <div class="col-md-6">
                            <div class="box">
                              <div class="box-header with-border">
                                <?php
                                  $stmt = $connect->prepare("
                                      SELECT lt.*, u.Name, u.Surname
                                      FROM learners lt
                                      JOIN users u ON lt.LearnerId = u.Id
                                  ");
                                  $stmt->execute();
                                  $results = $stmt->get_result();

                                  echo "<h3 class='box-title'>Learners List</h3>";
                                ?>
                              </div>

                              <div class="box-body">
                                <div class="table-responsive">
                                  <table id="learnersTabledelete" class="table table-bordered table-hover">
                                    <thead style="background-color: #f06f6fff;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Grade</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php if ($results && $results->num_rows > 0): ?>
                                        <?php while($final = $results->fetch_assoc()): ?>
                                          <tr>
                                            <td><?php echo htmlspecialchars($final['Name']) ?></td>
                                            <td><?php echo htmlspecialchars($final['Surname']) ?></td>
                                            <td><?php echo htmlspecialchars($final['Grade']) ?></td>
                                            <td class="text-center">
                                              <a href="deletelearner.php?id=<?php echo $final['LearnerId'] ?>" class="btn btn-xs btn-danger">Delete</a>
                                            </td>
                                          </tr>
                                        <?php endwhile; ?>
                                      <?php else: ?>
                                        <tr>
                                          <td colspan="4" class="text-center">No learners found.</td>
                                        </tr>
                                      <?php endif; ?>
                                    </tbody>
                                    <tfoot style="background-color: #f9f9f9;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Grade</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Tutors List -->
                          <div class="col-md-6">
                            <div class="box">
                              <div class="box-header with-border">
                                <?php
                                  $stmt = $connect->prepare("
                                      SELECT lt.*, u.Name, u.Surname
                                      FROM tutors lt
                                      JOIN users u ON lt.TutorId = u.Id
                                  ");
                                  $stmt->execute();
                                  $results = $stmt->get_result();

                                  echo "<h3 class='box-title'>Tutors List</h3>";
                                ?>
                              </div>

                              <div class="box-body">
                                <div class="table-responsive">
                                  <table id="tutorsTabledelete" class="table table-bordered table-hover">
                                    <thead style="background-color: #f06f6fff;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php if ($results && $results->num_rows > 0): ?>
                                        <?php while($final = $results->fetch_assoc()): ?>
                                          <tr>
                                            <td><?php echo htmlspecialchars($final['Name']) ?></td>
                                            <td><?php echo htmlspecialchars($final['Surname']) ?></td>
                                            <td class="text-center">
                                              <a href="deletetutors.php?id=<?php echo $final['TutorId'] ?>" class="btn btn-xs btn-danger">Delete</a>
                                            </td>
                                          </tr>
                                        <?php endwhile; ?>
                                      <?php else: ?>
                                        <tr>
                                          <td colspan="5" class="text-center">No tutors found.</td>
                                        </tr>
                                      <?php endif; ?>
                                    </tbody>
                                    <tfoot style="background-color: #f9f9f9;">
                                      <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th class="text-center">Actions</th>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                  
                    </div>
                  </div>

                  <!-- System Settings -->
                  <div class="tab-pane" id="settings">
                    <div class="profile-personal-info">
                      <h3 class="text-primary mb-4">System Settings</h3>
                      
                      

                      <!-- Main Content -->
                      <section class="content">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="box box-primary">
                              <div class="box-header with-border">
                                <h3 class="box-title">Subject Pricing</h3>
                              </div>
                              <div class="box-body">

                                <table class="table table-bordered text-center">
                                  <thead>
                                    <tr>
                                      <th>Subject</th>
                                      <th>3 Months</th>
                                      <th>6 Months</th>
                                      <th>12 Months</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>Mathematics</td>
                                      <td><input type="text" class="form-control text-center" value="R450.00"></td>
                                      <td><input type="text" class="form-control text-center" value="R750.00"></td>
                                      <td><input type="text" class="form-control text-center" value="R1199.00"></td>
                                    </tr>
                                    <tr>
                                      <td>Physical Sciences</td>
                                      <td><input type="text" class="form-control text-center" value="R450.00"></td>
                                      <td><input type="text" class="form-control text-center" value="R750.00"></td>
                                      <td><input type="text" class="form-control text-center" value="R1199.00"></td>
                                    </tr>
                                    
                                    <!-- Add more subjects as new rows -->
                                  </tbody>
                                </table>

                              </div>
                              <div class="box-footer text-right">
                                <button type="button" class="btn btn-primary">Save Pricing</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>


                      <!-- Main content -->
                      <section class="content">
                        <div class="row">

                          <!-- Default Values -->
                          <div class="col-md-4">
                            <div class="box box-warning">
                              <div class="box-header with-border">
                                <h3 class="box-title">Class Defaults</h3>
                              </div>
                              <div class="box-body">
                                <div class="default-values-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:15px;">
                                  <div class="form-group">
                                    <label>Default Tutor ID</label>
                                    <input type="number" class="form-control" placeholder="Enter Tutor ID">
                                  </div>
                                 
                                  <div class="form-group">
                                    <label>Default Maximum Learners per Class</label>
                                    <input type="number" class="form-control" placeholder="e.g. 30">
                                  </div>
                                
                                </div>
                              </div>
                              <div class="box-footer text-right">
                                <button type="button" class="btn btn-warning">Save Defaults</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="box box-primary">
                              <div class="box-header with-border">
                                <h3 class="box-title">Quizzes Defaults</h3>
                              </div>
                              <div class="box-body">
                                <div class="default-values-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:15px;">
                                  
                                  <div class="form-group">
                                    <label>Default Class Duration (minutes)</label>
                                    <input type="number" class="form-control" placeholder="e.g. 60">
                                  </div>
                                  <div class="form-group">
                                    <label>Default Pass Mark (%)</label>
                                    <input type="number" class="form-control" placeholder="e.g. 70">
                                  </div>
                                  
                                </div>
                              </div>
                              <div class="box-footer text-right">
                                <button type="button" class="btn btn-primary">Save Defaults</button>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="box box-success">
                              <div class="box-header with-border">
                                <h3 class="box-title">Resources Defaults</h3>
                              </div>
                              <div class="box-body">
                                <div class="default-values-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:15px;">
                                  
                                  <div class="form-group">
                                    <label>Default Resource Storage Limit (MB)</label>
                                    <input type="number" class="form-control" placeholder="e.g. 500">
                                  </div>
                                  
                                  <!-- Add more default values here if needed -->
                                </div>
                              </div>
                              <div class="box-footer text-right">
                                <button type="button" class="btn btn-success">Save Defaults</button>
                              </div>
                            </div>
                          </div>


                        </div>
                      </section>



                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </section>



    </div>

    <div class="control-sidebar-bg"></div>
</div>


<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
<script>
  $(function () {
    // Initialize Learners Table
    $('#learnersTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });

    // Initialize Tutors Table
    $('#tutorsTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });

    $('#learnersTabledelete').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });

    // Initialize Tutors Table
    $('#tutorsTabledelete').DataTable({
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

<!-- Deregister Learner Modal -->
<div class="modal fade" id="deregisterLearnerModal" tabindex="-1" role="dialog" aria-labelledby="deregisterLearnerLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-red">
        <h4 class="modal-title" id="deregisterLearnerLabel">Deregister Learner from Program</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="process_deregistration.php" method="POST">
        <div class="modal-body">
          <p>Are you sure you want to deregister this learner from the program completely?</p>
          
          <!-- Learner selection -->
          <div class="form-group">
            <label for="learnerSelect">Select Learner</label>
            <select name="LearnerId" id="learnerSelect" class="form-control" required>
              <option value="">-- Choose Learner --</option>
              <?php
              $learnersQuery = "
                SELECT l.LearnerId, u.Name, u.Surname
                FROM learners l
                JOIN users u ON l.LearnerId = u.Id
                ORDER BY u.Surname, u.Name
              ";
              $res = $connect->query($learnersQuery);
              while ($row = $res->fetch_assoc()) {
                  echo "<option value='{$row['LearnerId']}'>{$row['Surname']}, {$row['Name']}</option>";
              }
              ?>
            </select>
          </div>

          <!-- Reason -->
          <div class="form-group">
            <label for="reason">Reason for Deregistration</label>
            <textarea name="Reason" id="reason" class="form-control" rows="3" placeholder="Enter reason..." required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Deregister</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
