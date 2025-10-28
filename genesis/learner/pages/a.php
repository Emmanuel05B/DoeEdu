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
      <h1>Announcements & News</h1>
      <p>Stay up to date with important updates, tips, and events from your Tutors and Directors.</p>
      <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Announcements</li>
        </ol>
      <a href="noticepage.php" class="btn btn-info" style="margin-top: 10px;">
        📢 View General Announcements
      </a>
    </section>

    <section class="content">
      <!-- Scrolling Banner -->
      <div class="callout callout-info" style="background-color: #3c8dbc; color: white;">
        <marquee behavior="scroll" direction="left" scrollamount="5">
          🗓️ Exam prep workshop this Saturday at 10AM • 📢 Mid-year exams start on July 1st • 📚 New study materials uploaded under Resources!
        </marquee>
      </div>

      <!-- Detailed Announcements -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Latest Announcements</h3>
        </div>
        <div class="box-body">

        <?php
              // For demo, hardcoding learner grade
              $learnerGrade = '10';

              $sql = "SELECT sn.Title, sn.Content, sn.SubjectName, sn.CreatedAt, u.Name, u.Surname 
                      FROM subjectnotices sn
                      LEFT JOIN users u ON sn.CreatedBy = u.Id
                      WHERE sn.Grade = ?
                      ORDER BY sn.CreatedAt DESC
                      LIMIT 10";

              if ($stmt = $connect->prepare($sql)) {
                  $stmt->bind_param("s", $learnerGrade);
                  $stmt->execute();
                  $result = $stmt->get_result();

                  if ($result->num_rows > 0) {
                      while ($notice = $result->fetch_assoc()) {
                          $posterName = htmlspecialchars($notice['Name'] . ' ' . $notice['Surname']);
                          $title = htmlspecialchars($notice['Title']);
                          $content = nl2br(htmlspecialchars($notice['Content']));
                          $subjectName = htmlspecialchars($notice['SubjectName']);
                          $createdAt = date('Y-m-d', strtotime($notice['CreatedAt']));

                          echo <<<HTML
                          <div class="post">
                            <h4><strong>🔔 $title</strong> <small style="font-weight: normal; color: #555;">[$subjectName]</small></h4>
                            <p>Posted by $posterName · $createdAt</p>
                            <p>$content</p>
                          </div>
                          <hr>
                          HTML;
                      }
                  } else {
                      echo "<p>No announcements available at the moment.</p>";
                  }
                  $stmt->close();
              } else {
                  echo "<p>Error fetching announcements.</p>";
              }
          ?>


        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
  
</body>
</html>
