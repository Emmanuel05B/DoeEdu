<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("adminpartials/head.php"); ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <h3 class="box-title" style="text-align: center;">Learner Registration Form</h3>
           
            <div class="box-body">
              <form action="addlearnerh.php" method="post">

                <!-- Learner Info Block -->
                <fieldset class="tab">
                  <legend>Learner Info</legend>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="name">First Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name" required>
                    </div>
                    <div class="col-md-6">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your surname" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="col-md-3">
                      <label for="contactnumber">Contact Number (10 digits)</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                      <input type="hidden" id="password" name="password" value="12345">

                    </div>
                    <div class="col-md-2">
                      <label for="learnertitle">Title</label>
                      <select class="form-control" id="learnertitle" name="learnertitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                       
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="grade">Grade</label>
                      <select id="grade" name="grade" class="form-control" required>
                        <option value="" disabled selected>Select Grade</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="knockout_time">Knockout Time</label>
                      <input type="time" class="form-control" id="knockout_time" name="knockout_time" required>
                    </div>
                  </div>
                
                </fieldset><br>

                <!-- Subject Selection Block -->
                <fieldset class="tab">
                  <legend>Select Subjects and Duration</legend>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Subject</th>
                        <th>Not Registered</th>
                        <th>3 Months</th>
                        <th>6 Months</th>
                        <th>12 Months</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Mathematics</td>
                        <td><input type="radio" name="maths" value="0" checked></td>
                        <td><input type="radio" name="maths" value="450.00"></td>
                        <td><input type="radio" name="maths" value="750.00"></td>
                        <td><input type="radio" name="maths" value="1199.00"></td>
                      </tr>
                      <tr>
                        <td>Physical Sciences</td>
                        <td><input type="radio" name="physics" value="0" checked></td>
                        <td><input type="radio" name="physics" value="450.00"></td>
                        <td><input type="radio" name="physics" value="750.00"></td>
                        <td><input type="radio" name="physics" value="1199.00"></td>
                      </tr>
                    </tbody>
                  </table>
                </fieldset><br>

                <!-- Current and Target Levels Block -->
                <fieldset class="tab">
                  <legend>Current and Target Levels</legend>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Subject</th>
                        <th>Current Level (1 - 7)</th>
                        <th>Target Level (3 - 7)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Mathematics</td>
                        <td>
                          <select name="math-current" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="100">none</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                          </select>
                        </td>
                        <td>
                          <select name="math-target" class="form-control" required>
                            <option value="">Select Target</option>
                            <option value="100">none</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Physical Sciences</td>
                        <td>
                          <select name="physics-current" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="100">none</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                          </select>
                        </td>
                        <td>
                          <select name="physics-target" class="form-control" required>
                            <option value="">Select Target</option>
                            <option value="100">none</option>
                            <option value="1">1</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                          </select>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </fieldset><br>

                <!-- Parent Info Block -->
                <fieldset class="tab">
                  <legend>Parent Info</legend>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="parentname">First Name</label>
                      <input type="text" class="form-control" id="parentname" name="parentname" placeholder="Enter parent first name" required>
                    </div>
                    <div class="col-md-6">
                      <label for="parentsurname">Surname</label>
                      <input type="text" class="form-control" id="parentsurname" name="parentsurname" placeholder="Enter parent surname" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="parentemail">Email</label>
                      <input type="email" class="form-control" id="parentemail" name="parentemail" placeholder="Enter parent email" required>
                    </div>
                    <div class="col-md-3">
                      <label for="parentcontact">Contact Number</label>
                      <input type="tel" class="form-control" id="parentcontact" name="parentcontact" pattern="[0-9]{10}" maxlength="10" required>
                    </div>
                    <div class="col-md-3">
                      <label for="parenttitle">Title</label>
                      <select class="form-control" id="parenttitle" name="parenttitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Dr">Dr.</option>
                        <option value="Prof">Prof.</option>
                      </select>
                    </div>
                  </div>
                </fieldset>

                <!-- Submit Button -->
                <div class="text-center" style="margin-top: 20px;">
                  <button type="submit" class="btn btn-primary">Submit All Info</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
</body>
</html>

