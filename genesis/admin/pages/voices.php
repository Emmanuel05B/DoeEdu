<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");

// Dummy sample data for voices
$studentVoices = [
    ["name" => "John Doe", "date" => "2025-08-10", "message" => "I struggle with algebra concepts."],
    ["name" => "Mary Smith", "date" => "2025-08-09", "message" => "Can we have more practice questions on chemical reactions?"],
    ["name" => "David Lee", "date" => "2025-08-08", "message" => "Sometimes I don’t understand the lessons in class."],
];
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Student Voices <small>Read learners' concerns and feedback</small></h1>
            <ol class="breadcrumb">
                <li><a href="tutorindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Student Voices</li>
            </ol>
        </section>

        <section class="content">
          <div class="row">
            <!-- Left side: Student voices -->
            <div class="col-md-8">
  <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
    <div class="box-header with-border" style="background-color:#f0f8ff;">
      <h3 class="box-title" style="color:#3c8dbc;">
        <i class="fa fa-comments"></i> Learner Concerns
      </h3>
    </div>
    <div class="box-body" style="background-color:#ffffff; max-height: 600px; overflow-y: auto;">
      <ul class="list-group">
        <li class="list-group-item">
          <strong>John Doe</strong> 
          <em style="color: gray; font-size: 12px;">2025-08-11</em>
          <p>I am struggling with the math assignments.</p>
          <button type="button" class="btn btn-xs btn-success">
            <i class="fa fa-check"></i> Mark as Read
          </button>
        </li>
        <li class="list-group-item">
          <strong>Jane Smith</strong> 
          <em style="color: gray; font-size: 12px;">2025-08-10</em>
          <p>Need extra help with Physical Science topics.</p>
          <button type="button" class="btn btn-xs btn-success">
            <i class="fa fa-check"></i> Mark as Read
          </button>
        </li>
        <!-- Add more items here -->
      </ul>
    </div>
  </div>
</div>


            <!-- Right side: Additional info -->
            <div class="col-md-4">
              <div class="box box-info" style="border-top: 3px solid #00c0ef;">
                <div class="box-header with-border" style="background-color:#d9f0fb;">
                  <h3 class="box-title" style="color:#0073b7;">
                    <i class="fa fa-info-circle"></i> Additional Info
                  </h3>
                </div>
                <div class="box-body" style="background-color:#ffffff; min-height: 200px;">
                  <h4>How to Support Learners</h4>
                  <ul>
                    <li>Schedule one-on-one sessions.</li>
                    <li>Encourage group discussions.</li>
                    <li>Provide extra practice resources.</li>
                    <li>Refer to counselors if needed.</li>
                  </ul>
                  
                </div>
              </div>
            </div>
          </div>
        </section>
    </div>

    <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
