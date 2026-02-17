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
      <h1>Profile Settings <small>Manage your personal information and preferences</small></h1>
      
      <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Profile Settings</li>
        </ol>

        <!-- VIEW PROFILE BUTTON -->
      <div style="margin-top: 10px;">
          <a href="view_profile.php" class="btn btn-primary btn-xs">
              <i></i> View My Profile
          </a>
      </div>
    </section>


    <section class="content">

      <div class="row">

        <!-- FORM 1: Learning Profile -->
        <div class="col-md-6" id="learning_profile">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Learning Profile</h3>
            </div>
            <div class="box-body">
              <form action="save_learner_profile.php" method="POST">
                <input type="hidden" name="form_type" value="learning_profile">
                  
                <div class="form-group">
                  <label>Tell us a bit about yourself (2–4 lines)</label>
                  <textarea name="about_learner" class="form-control" rows="3" maxlength="500" required
                    placeholder="e.g. I am a Grade 10 learner who enjoys Maths and Science. I prefer step-by-step explanations and learn best in quiet environments."></textarea>
                </div>

                <!-- Learning Style -->
                <div class="form-group">
                  <label>Preferred Learning Style</label>
                  <select name="learning_style" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="step_by_step">Step-by-step explanations</option>
                    <option value="visual_examples">Visual examples</option>
                    <option value="practice_exercises">Practice exercises</option>
                    <option value="interactive">Interactive / Hands-on</option>
                  </select>
                </div>

                <!-- Study Challenges -->
                
                <div class="form-group">
                  <label>Study Challenges at Home (select all that apply)</label><br>
                
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="noise"> Noise / distractions (siblings, TV, etc.)
                  </label></div>
                
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="internet"> Slow or unreliable internet
                  </label></div>
                
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="device_limitations"> Device limitations (small screen, no laptop)
                  </label></div>
                
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="motivation"> Lack of motivation / distractions online
                  </label></div>
                
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="time_management"> Time management / chores
                  </label></div>
                  
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="learning_materials"> Difficulty understanding online materials
                  </label></div>
                
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="communication"> Difficulty asking questions / interacting with teacher
                  </label></div>
                
                  <div class="checkbox"><label>
                    <input type="checkbox" name="study_challenges[]" value="technical_skills"> Low technical skills (using Zoom, Teams, or LMS)
                  </label></div>
                </div>


                <!-- Concentration Span -->
                <div class="form-group">
                  <label>Concentration Span</label>
                  <select name="concentration_span" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="under_30">Under 30 minutes</option>
                    <option value="30_45">30–45 minutes</option>
                    <option value="45_60">45–60 minutes</option>
                    <option value="over_60">Over 60 minutes</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-info btn-sm">Save</button>
              </form>
            </div>
          </div>
        </div>

        <!-- FORM 2: Availability -->
        <div class="col-md-6" id="availability">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Availability</h3>
            </div>
            <div class="box-body">
              <form action="save_learner_profile.php" method="POST">
                <input type="hidden" name="form_type" value="availability">

                <!-- Preferred Day -->
                <div class="form-group">
                  <label>Preferred Day for Sessions</label>
                  <select name="preferred_day" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                    <option value="sunday">Sunday</option>
                  </select>
                </div>

                <!-- Preferred Time -->
                <div class="form-group">
                  <label>Preferred Time of Day</label><br>
                  <div class="radio"><label><input type="radio" name="preferred_time" value="morning" required> Morning</label></div>
                  <div class="radio"><label><input type="radio" name="preferred_time" value="afternoon"> Afternoon</label></div>
                  <div class="radio"><label><input type="radio" name="preferred_time" value="evening"> Evening</label></div>
                  <div class="radio"><label><input type="radio" name="preferred_time" value="flexible"> Flexible</label></div>
                </div>

                <!-- Session Length -->
                <div class="form-group">
                  <label>Preferred Session Length</label>
                  <select name="session_length" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="30">30 minutes</option>
                    <option value="60">60 minutes</option>
                    <option value="90">90 minutes</option>
                  </select>
                </div>

                <!-- Other Classes -->
                <div class="form-group">
                  <label>Other Classes Attending</label>
                  <select name="other_classes" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="saturday_school">Saturday school</option>
                    <option value="after_school_program">After-school program</option>
                    <option value="church_classes">Church classes</option>
                    <option value="none">None</option>
                  </select>
                </div>

                <!-- Home Chores -->
                <div class="form-group">
                  <label>Chores at Home</label>
                  <select name="chores_home" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="cooking">Cooking</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="babysitting">Babysitting</option>
                    <option value="help_siblings_homework">Helping siblings with homework</option>
                    <option value="none">None</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-success btn-sm">Save</button>
              </form>
            </div>
          </div>
        </div>

      </div>

      <div class="row">

        <!-- FORM 3: Session Style -->
        <div class="col-md-6" id="session_style">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Session Style</h3>
            </div>
            <div class="box-body">
              <form action="save_learner_profile.php" method="POST">
                <input type="hidden" name="form_type" value="session_style">

                <!-- Session Format -->
                <div class="form-group">
                  <label>Preferred Session Format</label><br>
                  <div class="radio"><label><input type="radio" name="session_format" value="one_on_one" required> One-on-One</label></div>
                  <div class="radio"><label><input type="radio" name="session_format" value="group"> Group</label></div>
                  <div class="radio"><label><input type="radio" name="session_format" value="hybrid"> Hybrid</label></div>
                </div>

                <!-- Break Preferences -->
                <div class="form-group">
                  <label>Break Preferences</label>
                  <select name="break_preferences" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="5_per_30">5-min break every 30 min</option>
                    <option value="10_per_60">10-min break every 60 min</option>
                    <option value="no_break">No break</option>
                  </select>
                </div>

                <!-- Motivations & Goals -->
                <div class="form-group">
                  <label>Motivations & Goals</label>
                  <select name="motivations_goals" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="improve_grades">Improve grades</option>
                    <option value="exam_prep">Exam preparation</option>
                    <option value="master_topic">Master a topic</option>
                    <option value="other">Other</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-warning btn-sm">Save</button>
              </form>
            </div>
          </div>
        </div>

        <!-- FORM 4: Technical Setup -->
        <div class="col-md-6" id="technical_setup">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Technical Setup</h3>
            </div>
            <div class="box-body">
              <form action="save_learner_profile.php" method="POST">
                <input type="hidden" name="form_type" value="technical_setup">

                <!-- Devices -->
                <div class="form-group">
                  <label>Devices Available</label>
                  <select name="devices" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="laptop">Laptop</option>
                    <option value="tablet">Tablet</option>
                    <option value="smartphone">Smartphone</option>
                    <option value="none">None</option>
                  </select>
                </div>

                <!-- Internet -->
                <div class="form-group">
                  <label>Internet Reliability</label><br>
                  <div class="radio"><label><input type="radio" name="internet_reliability" value="always_stable" required> Always stable</label></div>
                  <div class="radio"><label><input type="radio" name="internet_reliability" value="sometimes_slow"> Sometimes slow</label></div>
                  <div class="radio"><label><input type="radio" name="internet_reliability" value="unreliable"> Unreliable</label></div>
                </div>

                <!-- Strengths 
                <div class="form-group">
                  <label>Top Strength / Skill</label>
                  <select name="strengths_skills" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="problem_solving">Problem-solving</option>
                    <option value="reading_comprehension">Reading comprehension</option>
                    <option value="maths">Mathematics</option>
                    <option value="science">Science</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                -->


                <button type="submit" class="btn btn-danger btn-sm">Save</button>
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
<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Your profile information has been updated.'
            }).then(() => {
                // Remove query string from URL after alert
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        } else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not save your information. Please try again.'
            }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            });
        }
    });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
      const hash = window.location.hash;
      if(hash) {
          setTimeout(() => {
              const target = document.querySelector(hash);
              if(target) {
                  const headerOffset = 100; // adjust this if you have a fixed header
                  const elementPosition = target.getBoundingClientRect().top;
                  const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                  window.scrollTo({
                      top: offsetPosition,
                      behavior: "smooth"
                  });
              }
          }, 100); // wait 100ms for layout
      }
  });

</script>




</html>
