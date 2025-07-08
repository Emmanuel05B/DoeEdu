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
    <section class="content-header">
      <h1>
        Update Tutor Details
        <small>Manage your profile information</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Tutor</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Your Details</h3>
            </div>
            <!-- form start -->
            <form role="form" action="updatetutorhandler.php" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <!-- Name and Surname -->
                <div class="form-group">
                  <label for="firstname">First Name</label>
                  <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" required>
                </div>
                <div class="form-group">
                  <label for="surname">Surname</label>
                  <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Surname" required>
                </div>

                <!-- Email and Contact -->
                <div class="form-group">
                  <label for="email">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                </div>
                <div class="form-group">
                  <label for="contactnumber">Contact Number</label>
                  <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" placeholder="Enter 10-digit Contact Number" required>
                </div>

                <!-- Title -->
                <div class="form-group">
                  <label for="title">Title</label>
                  <select class="form-control" id="title" name="title" required>
                    <option value="">Select Title</option>
                    <option>Mr</option>
                    <option>Mrs</option>
                    <option>Ms</option>
                    <option>Dr</option>
                  </select>
                </div>

                <!-- Bio -->
                <div class="form-group">
                  <label for="bio">Bio</label>
                  <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Short bio about yourself"></textarea>
                </div>

                <!-- Qualifications -->
                <div class="form-group">
                  <label for="qualifications">Qualifications</label>
                  <textarea class="form-control" id="qualifications" name="qualifications" rows="2" placeholder="Your qualifications"></textarea>
                </div>

                <!-- Experience -->
                <div class="form-group">
                  <label for="experience_years">Years of Experience</label>
                  <input type="number" class="form-control" id="experience_years" name="experience_years" min="0" max="50" placeholder="Enter years of experience">
                </div>

                <!-- Profile Picture -->
                <div class="form-group">
                  <label for="profile_picture">Profile Picture (optional)</label>
                  <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>

                <!-- Availability -->
                <div class="form-group">
                  <label for="availability">Availability</label>
                  <input type="text" class="form-control" id="availability" name="availability" placeholder="e.g. Mon-Fri 3pm-7pm">
                </div>

                <!-- Subjects & Grades -->
                <div class="form-group">
                  <label>Subjects and Grades You Teach</label>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Subject</th>
                        <th>Grade 10</th>
                        <th>Grade 11</th>
                        <th>Grade 12</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Mathematics</td>
                        <td><input type="checkbox" name="subjects[Mathematics][]" value="10"></td>
                        <td><input type="checkbox" name="subjects[Mathematics][]" value="11"></td>
                        <td><input type="checkbox" name="subjects[Mathematics][]" value="12"></td>
                      </tr>
                      <tr>
                        <td>Physical Sciences</td>
                        <td><input type="checkbox" name="subjects[Physical Sciences][]" value="10"></td>
                        <td><input type="checkbox" name="subjects[Physical Sciences][]" value="11"></td>
                        <td><input type="checkbox" name="subjects[Physical Sciences][]" value="12"></td>
                      </tr>
                      <!-- Add more subjects if needed -->
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="box-footer text-center">
                <button type="submit" class="btn btn-primary">Update Details</button>
              </div>
            </form>
          </div> <!-- /.box -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </section>
  </div> <!-- /.content-wrapper -->

  <div class="control-sidebar-bg"></div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
