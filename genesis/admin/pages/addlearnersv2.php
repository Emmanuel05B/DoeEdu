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
   <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>
  
  <div class="content-wrapper">

        <section class="content-header">
          <h1>
            Learner Registration
            <small>Form</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Administration</li>
          </ol>
        </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <h3 class="box-title" style="text-align: center;"></h3>
           
            <div class="box-body">
              <form action="addlearnerhv2.php" method="post">

                <!-- Learner Info Block -->
                <fieldset class="tab">
                  <legend>Learner Info</legend>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="name">First Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name" required>
                    </div>
                    <div class="col-md-3">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your surname" required>
                    </div>
                    <div class="col-md-3">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="col-md-3">
                      <label for="contactnumber">Contact Number (10 digits)</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                      <input type="hidden" id="password" name="password" value="12345">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-2">
                      <label for="learnertitle">Title</label>
                      <select class="form-control" id="learnertitle" name="learnertitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="schoolname">School Name</label>
                      <select class="form-control" id="schoolname" name="schoolname" required>
                        <option value="">Select School Name</option>
                        <option value="School v1">School v1</option>
                        <option value="School v2">School v2</option>
                        <option value="School v3">School v3</option>
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
                  <legend>Register Subjects and Levels(Goals)</legend>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Subject</th>
                        <th>Mark (<i class="fa fa-check-square"></i>)</th>
                        <th>Current Level (1 - 7)</th>
                        <th>Target Level (3 - 7)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Mathematics</td>
                        <td><input type="checkbox" name="maths" value="450.00" ></td>
                        <td>
                          <select name="math-current" class="form-control">
                            <option value="1">Select Level</option>
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
                          <select name="math-target" class="form-control">
                            <option value="7">Select Target</option>
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
                        <td><input type="checkbox" name="physics" value="450.00"></td>
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
                        <tr>
                        <td>History</td>
                        <td><input type="checkbox" name="history" value="450.00"></td>
                        <td>
                          <select name="history-current" class="form-control" required>
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
                          <select name="history-target" class="form-control" required>
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
                      <tr>
                        <td>English</td>
                        <td><input type="checkbox" name="english" value="450.00"></td>
                        <td>
                          <select name="history-current" class="form-control" required>
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
                          <select name="history-target" class="form-control" required>
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
                    <div class="col-md-2">
                      <label for="parentname">First Name</label>
                      <input type="text" class="form-control" id="parentname" name="parentname" placeholder="Parent first name" required>
                    </div>
                    <div class="col-md-3">
                      <label for="parentsurname">Surname</label>
                      <input type="text" class="form-control" id="parentsurname" name="parentsurname" placeholder="Enter parent surname" required>
                    </div>
                  
                    <div class="col-md-3">
                      <label for="parentemail">Email</label>
                      <input type="email" class="form-control" id="parentemail" name="parentemail" placeholder="Enter parent email" required>
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                      <label for="parentcontact">Contact Number</label>
                      <input type="tel" class="form-control" id="parentcontact" name="parentcontact" pattern="[0-9]{10}" maxlength="10" required>
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
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>

