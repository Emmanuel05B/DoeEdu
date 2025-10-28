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

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  

?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
<?php include_once(LEARNER_PATH . "/../partials/header.php"); ?>
<?php include_once(LEARNER_PATH . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
    <section class="content-header">
      <h1>Profile Settings</h1>
      <small>Manage your personal information and preferences</small>
      <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">

        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Personal Information</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST" autocomplete="off">
                <div class="form-group">
                  <label for="fullName">Full Name</label>
                  <input type="text" class="form-control input-sm" id="fullName" name="fullName" placeholder="Your full name" required>
                </div>
                <div class="form-group">
                  <label for="email">Email Address (readonly)</label>
                  <input type="email" class="form-control input-sm" id="email" name="email" readonly value="learner@example.com">
                </div>
                <div class="form-group">
                  <label for="phone">Phone Number</label>
                  <input type="tel" class="form-control input-sm" id="phone" name="phone" placeholder="e.g. +27 123 456 7890">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Update Info</button>
              </form>
            </div>
          </div>
        </div>
        <!-- Personal Information -->
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Personal Information</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST" autocomplete="off">
                <div class="form-group">
                  <label for="fullName">Full Name</label>
                  <input type="text" class="form-control input-sm" id="fullName" name="fullName" placeholder="Your full name" required>
                </div>
                <div class="form-group">
                  <label for="email">Email Address (readonly)</label>
                  <input type="email" class="form-control input-sm" id="email" name="email" readonly value="learner@example.com">
                </div>
                <div class="form-group">
                  <label for="phone">Phone Number</label>
                  <input type="tel" class="form-control input-sm" id="phone" name="phone" placeholder="e.g. +27 123 456 7890">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Update Info</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Password Change -->
        <div class="col-md-4">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST" autocomplete="off">
                <div class="form-group">
                  <label for="currentPassword">Current Password</label>
                  <input type="password" class="form-control input-sm" id="currentPassword" name="currentPassword" placeholder="Current password" required>
                </div>
                <div class="form-group">
                  <label for="newPassword">New Password</label>
                  <input type="password" class="form-control input-sm" id="newPassword" name="newPassword" placeholder="New password" required>
                </div>
                <div class="form-group">
                  <label for="confirmPassword">Confirm New Password</label>
                  <input type="password" class="form-control input-sm" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-danger btn-sm">Change Password</button>
              </form>
            </div>
          </div>
        </div>

      </div>


      <!-- Learner Preferences Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Learner Preferences</h3>
            </div>
            <div class="box-body">
              <form action="#" method="POST">

                <div class="row">
                  <!-- Learning Preferences -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Learning Preferences</label>
                      <textarea name="learning_preferences" class="form-control" rows="3" placeholder="e.g. Step-by-step explanations with examples, diagrams for Science..."></textarea>
                    </div>
                  </div>

                  <!-- Study Challenges -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Study Challenges</label>
                      <textarea name="study_challenges" class="form-control" rows="3" placeholder="e.g. I struggle in noisy environments, need more time for complex concepts..."></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Preferred Days -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Preferred Days for Sessions</label>
                      <textarea name="preferred_days" class="form-control" rows="2" placeholder="e.g. Mondays, Wednesdays, Saturdays"></textarea>
                    </div>
                  </div>

                  <!-- Preferred Time -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Preferred Time of Day</label>
                      <textarea name="preferred_time" class="form-control" rows="2" placeholder="e.g. Afternoons 3-6 PM, evenings after 7 PM"></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Concentration Span -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Concentration Span</label>
                      <textarea name="concentration_span" class="form-control" rows="2" placeholder="e.g. I can focus for 30-45 minutes before a short break"></textarea>
                    </div>
                  </div>

                  <!-- Session Length -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Preferred Session Length</label>
                      <textarea name="session_length" class="form-control" rows="2" placeholder="e.g. 1 hour, or 90 minutes with a 10-min break"></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Session Format -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Session Format</label>
                      <textarea name="session_format" class="form-control" rows="2" placeholder="e.g. One-on-one, group discussions, camera on/off"></textarea>
                    </div>
                  </div>

                  <!-- Break Preferences -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Break Preferences</label>
                      <textarea name="break_preferences" class="form-control" rows="2" placeholder="e.g. 5-minute break every 30 minutes"></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Device Access -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Devices & Access</label>
                      <textarea name="device_access" class="form-control" rows="2" placeholder="e.g. Laptop with Wi-Fi, sometimes phone if power cuts"></textarea>
                    </div>
                  </div>

                  <!-- Motivations & Goals -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Motivations & Goals</label>
                      <textarea name="motivations_goals" class="form-control" rows="2" placeholder="e.g. Improve Maths & Science, aim for Engineering at university"></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Strengths -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Strengths & Skills</label>
                      <textarea name="strengths_skills" class="form-control" rows="2" placeholder="e.g. Good at problem-solving, teamwork, organizing study time"></textarea>
                    </div>
                  </div>

                  <!-- Stress Triggers -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Stress Triggers</label>
                      <textarea name="stress_triggers" class="form-control" rows="2" placeholder="e.g. When lessons move too fast, tight deadlines, internet drops"></textarea>
                    </div>
                  </div>
                </div>

                <!-- Save Button -->
                <div class="row">
                  <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-info btn-sm">Save Preferences</button>
                  </div>
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

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?></body>
</html>
