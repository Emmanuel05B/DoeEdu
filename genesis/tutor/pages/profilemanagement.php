<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}

include(__DIR__ . "/../../common/partials/head.php");  //
include(__DIR__ . "/../../partials/connect.php");

$tutorId = $_SESSION['user_id'];
$tutor = [];

$stmt = $connect->prepare("
  SELECT 
    u.Name, u.Surname, u.Email, u.Contact, u.Gender,
    t.Bio, t.Qualifications, t.ExperienceYears, t.ProfilePicture, t.Availability
  FROM users u
  LEFT JOIN tutors t ON u.Id = t.TutorId
  WHERE u.Id = ?
  LIMIT 1
");

$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $tutor = $result->fetch_assoc();
} else {
  $tutor = [
    'Name' => '', 'Surname' => '', 'Email' => '', 'Contact' => '', 'Gender' => '',
    'Bio' => '', 'Qualifications' => '', 'ExperienceYears' => '',
    'ProfilePicture' => 'profile_pics/default_avatar.png', 'Availability' => ''
  ];
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">


  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="background-color: #f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Profile Management
        <small>Update your information and availability</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile Management</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border" style="background-color:#a3bffa; color:#fff;">
          <h3 class="box-title"><i class="fa fa-user-circle"></i> Edit Profile</h3>
        </div>

        <div class="box-body">
          <form action="save_profile.php" method="POST" enctype="multipart/form-data">
            <div class="row">

              <!-- Profile Image -->
              <div class="col-md-3 text-center">
                <img src="<?php echo $tutor['ProfilePicture'] ?? 'profile_pics/default_avatar.png'; ?>" alt="Profile Picture" class="img-circle" style="width: 120px; height: 120px; margin-top:10px;">
                <input type="file" name="profile_pic" accept="image/*" class="form-control" style="margin-top:15px;">
              </div>

              <!-- Profile Info -->
              <div class="col-md-9">
                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-user"></i> Name</label>
                      <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($tutor['Name']); ?>" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-user"></i> Surname</label>
                      <input type="text" name="surname" class="form-control" value="<?php echo htmlspecialchars($tutor['Surname']); ?>" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-venus-mars"></i> Title</label>
                      <select class="form-control" id="title" name="title">
                        <option value="">Select Title</option>
                        <option <?php if ($tutor['Gender'] == 'Male') echo 'selected'; ?>>Mr</option>
                        <option <?php if ($tutor['Gender'] == 'Female') echo 'selected'; ?>>Ms</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-envelope"></i> Email</label>
                      <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($tutor['Email']); ?>" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-phone"></i> Phone Number</label>
                      <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($tutor['Contact']); ?>">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-clock-o"></i> Availability</label>
                      <select name="availability" class="form-control" required>
                        <option value="">Select Availability</option>
                        <option value="fulltime" <?php if ($tutor['Availability'] == 'fulltime') echo 'selected'; ?>>Full Time</option>
                        <option value="parttime" <?php if ($tutor['Availability'] == 'parttime') echo 'selected'; ?>>Part Time</option>
                        <option value="weekends" <?php if ($tutor['Availability'] == 'weekends') echo 'selected'; ?>>Weekends Only</option>
                        <option value="evenings" <?php if ($tutor['Availability'] == 'evenings') echo 'selected'; ?>>Evenings Only</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-info-circle"></i> Bio</label>
                      <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Short bio about yourself"><?php echo htmlspecialchars($tutor['Bio']); ?></textarea>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-graduation-cap"></i> Qualifications</label>
                      <textarea class="form-control" id="qualifications" name="qualifications" rows="3" placeholder="Your qualifications"><?php echo htmlspecialchars($tutor['Qualifications']); ?></textarea>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label><i class="fa fa-briefcase"></i> Years of Experience</label>
                      <input type="number" class="form-control" id="experience_years" name="experience_years" min="0" max="50" value="<?php echo htmlspecialchars($tutor['ExperienceYears']); ?>">
                    </div>
                  </div>

                </div> <!-- inner row -->

                <div class="form-group text-right" style="margin-top: 15px;">
                  <a href="changepassword.php" class="btn btn-default"><i class="fa fa-key"></i> Change Password</a>
                </div>

                <div class="form-group text-right">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                </div>

              </div> <!-- col-md-9 -->
            </div> <!-- row -->
          </form>
        </div> <!-- box-body -->
      </div> <!-- box -->
    </section>
  </div> <!-- content-wrapper -->

</div> <!-- wrapper -->

<!-- Scripts -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
