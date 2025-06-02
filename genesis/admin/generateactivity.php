<!DOCTYPE html>
<html>
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
  <?php include("adminpartials/header.php") ?>
  <?php include("adminpartials/mainsidebar.php") ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1 class="text-center">Generate Activity</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Activities</a></li>
        <li class="active">Generate</li>
      </ol>
    </section>

     <?php
     // might be unnecessary.
    include('../partials/connect.php');
        $grade = $_GET['gra'];
       // $subject = $_GET['sub'];
        $chaptername = $_GET['cha'];

        $SubjectId = intval($_GET['sub']); // Get the subject value, ensure it's an integer

// Set the subject name based on SubjectId e
$SubjectName = '';
switch ($SubjectId) {
    case 1:
        $SubjectName = 'Mathematics';
        break;
    case 2:
        $SubjectName = 'Physical Sciences';
        break;
    case 3:
        $SubjectName = 'Mathematics';
        break;
    case 4:
        $SubjectName = 'Physical Sciences';
        break;
    case 5:
        $SubjectName = 'Mathematics';
        break;
    case 6:
        $SubjectName = 'Physical Sciences';
        break;
    default:
        echo '<h1>Learners - Unknown Subject</h1>';
        exit();
}
    ?>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border text-center">
          <h3 class="box-title">Activity Details</h3>
        </div>

        <form action="save_activity.php" method="POST">
          <div class="box-body">
            <div class="row text-center activity-info">
              <div class="col-sm-4">
                <strong>Grade:</strong> <?php echo $_GET['gra']; ?>
                <input type="hidden" name="grade" value="<?php echo $_GET['gra']; ?>">
              </div>
              <div class="col-sm-4">
                <strong>Subject:</strong> <?php echo $SubjectName; ?>
                <input type="hidden" name="subject" value="<?php echo $_GET['sub']; ?>">
              </div>
              <div class="col-sm-4">
                <strong>Chapter:</strong> <?php echo $_GET['cha']; ?>
                <input type="hidden" name="chapter" value="<?php echo $_GET['cha']; ?>">
              </div>
            </div>

            <hr>

            <div class="form-group">
              <label for="activity_title">Activity Title</label>
              <input type="text" class="form-control input-sm" id="activity_title" name="activity_title" placeholder="Enter activity title" required>
            </div>

            <div id="questions_container">
              <div class="question-block">
                <hr>
                <h4>Question 1</h4>
                <div class="form-group">
                  <label>Question</label>
                  <textarea name="questions[0][text]" class="form-control input-sm" required></textarea>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Option A</label>
                      <input type="text" name="questions[0][options][A]" class="form-control input-sm" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Option B</label>
                      <input type="text" name="questions[0][options][B]" class="form-control input-sm" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Option C</label>
                      <input type="text" name="questions[0][options][C]" class="form-control input-sm" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Option D</label>
                      <input type="text" name="questions[0][options][D]" class="form-control input-sm" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Correct Answer</label>
                  <select name="questions[0][correct]" class="form-control input-sm" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                  </select>
                </div>
              </div>
            </div>

            <button type="button" class="btn btn-default btn-sm" id="add_question_btn"><i class="fa fa-plus"></i> Add Another Question</button>
          </div>

          <div class="box-footer text-center">
            <button type="submit" class="btn btn-primary btn-sm">Generate Activity</button>
          </div>
        </form>
      </div>
    </section>
  </div>

<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>  
  <div class="control-sidebar-bg"></div>
</div>




<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
  let questionIndex = 1;
  document.getElementById('add_question_btn').addEventListener('click', function () {
    const container = document.getElementById('questions_container');
    const newBlock = document.createElement('div');
    newBlock.classList.add('question-block');
    newBlock.innerHTML = `
      <hr>
      <h4>Question ${questionIndex + 1}</h4>
      <div class="form-group">
        <label>Question</label>
        <textarea name="questions[${questionIndex}][text]" class="form-control input-sm" required></textarea>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label>Option A</label>
            <input type="text" name="questions[${questionIndex}][options][A]" class="form-control input-sm" required>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Option B</label>
            <input type="text" name="questions[${questionIndex}][options][B]" class="form-control input-sm" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label>Option C</label>
            <input type="text" name="questions[${questionIndex}][options][C]" class="form-control input-sm" required>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Option D</label>
            <input type="text" name="questions[${questionIndex}][options][D]" class="form-control input-sm" required>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Correct Answer</label>
        <select name="questions[${questionIndex}][correct]" class="form-control input-sm" required>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>
      </div>
    `;
    container.appendChild(newBlock);
    questionIndex++;
  });
</script>

<style>
  .form-control {
    max-width: 100%;
  }
  .question-block {
    margin-bottom: 20px;
  }
  .box-header.text-center h3 {
    text-align: center;
    margin: 0 auto;
    font-weight: 600;
  }
  .activity-info {
    margin-top: 15px;
    margin-bottom: 15px;
    font-size: 16px;
  }
  .activity-info strong {
    display: block;
    margin-bottom: 5px;
  }
  @media (max-width: 768px) {
    .form-control {
      font-size: 14px;
    }
    h4 {
      font-size: 16px;
    }
    .btn {
      font-size: 14px;
      padding: 6px 10px;
    }
    .activity-info {
      font-size: 14px;
    }
  }
</style>
</body>
</html>