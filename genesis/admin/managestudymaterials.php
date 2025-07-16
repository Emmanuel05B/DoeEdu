<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include('../partials/connect.php');
include("adminpartials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php"); ?>
  <?php include("adminpartials/mainsidebar.php"); ?>

  <div class="content-wrapper" style="background-color: #f7f9fc;">
    <section class="content-header">
      <h1 style="color:#4a6fa5;">
        Study Resources
        <small>Upload and manage learning materials</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="tutor_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Resources</li>
      </ol>
    </section>

    <section class="content">
      <!-- Upload Resource -->
      <div class="box box-success">
        <div class="box-header with-border" style="background-color:#b2d8b2; color:#355e35;">
          <h3 class="box-title">Upload New Resource</h3>
        </div>
        <div class="box-body" style="background-color:#eaf4e4;">
          <form action="upload_resource.php" method="POST" enctype="multipart/form-data">
            <div class="row">
              <?php
              $userId = $_SESSION['user_id'];
              $stmt = $connect->prepare("SELECT DISTINCT SubjectId FROM directorsubjects WHERE DirectorId = ?");
              $stmt->bind_param("i", $userId);
              $stmt->execute();
              $result = $stmt->get_result();

              $subjectGradePairs = [];
              function getSubjectDetails($subjectId) {
                  $grade = null;
                  $subjectName = null;
                  if ($subjectId == 1 || $subjectId == 4) $grade = 10;
                  elseif ($subjectId == 2 || $subjectId == 5) $grade = 11;
                  elseif ($subjectId == 3 || $subjectId == 6) $grade = 12;

                  if (in_array($subjectId, [1, 2, 3])) {
                      $subjectName = "Mathematics";
                  } elseif (in_array($subjectId, [4, 5, 6])) {
                      $subjectName = "Physical Sciences";
                  }

                  return ['grade' => $grade, 'subjectName' => $subjectName];
              }

              while ($row = $result->fetch_assoc()) {
                  $subjectId = $row['SubjectId'];
                  $details = getSubjectDetails($subjectId);
                  $subjectGradePairs[] = [
                      'subjectId' => $subjectId,
                      'subjectName' => $details['subjectName'],
                      'grade' => $details['grade']
                  ];
              }

              $uniqueSubjects = [];
              $uniqueGrades = [];

              foreach ($subjectGradePairs as $pair) {
                  $uniqueSubjects[$pair['subjectName']] = true;
                  $uniqueGrades["Grade " . $pair['grade']] = true;
              }
              ?>

              <div class="col-md-4 form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="E.g. Newton’s Laws Summary" required>
              </div>

              <div class="col-md-4 form-group">
                <label>Subject</label>
                <select name="subject" class="form-control" required>
                  <option value="">Select Subject</option>
                  <?php foreach (array_keys($uniqueSubjects) as $subject): ?>
                    <option value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-4 form-group">
                <label>Grade</label>
                <select name="grade" class="form-control" required>
                  <option value="">Select Grade</option>
                  <?php foreach (array_keys($uniqueGrades) as $grade): ?>
                    <option value="<?php echo $grade; ?>"><?php echo $grade; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-4 form-group">
                <label>Type of Resource</label>
                <select name="resource_type" class="form-control" required>
                  <option value="">Select Type</option>
                  <option value="PDF">PDF Document</option>
                  <option value="Image">Image</option>
                  <option value="Slides">Slides (e.g. PPT)</option>
                  <option value="Video">Video</option>
                </select>
              </div>

              <div class="col-md-8 form-group">
                <label>Choose File</label>
                <input type="file" name="resource_file" class="form-control" required>
              </div>

              <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload Resource</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Uploaded Resources -->
      <div class="box box-info">
        <div class="box-header with-border" style="background-color:#9f86d1; color:#fff;">
          <h3 class="box-title">Your Uploaded Resources</h3>
        </div>
        <div class="box-body" style="background-color:#f3edff;">
          <table class="table table-hover table-bordered">
            <thead style="background-color:#e0d4fc;">
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Uploaded At</th>
                <th style="width:120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stmt = $connect->prepare("SELECT * FROM resources WHERE UploadedBy = ? ORDER BY UploadedAt DESC");
              $stmt->bind_param("i", $userId);
              $stmt->execute();
              $result = $stmt->get_result();
              while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row['Title']); ?></td>
                <td><?php echo htmlspecialchars($row['Type']); ?></td>
                <td><?php echo htmlspecialchars($row['Subject']); ?></td>
                <td><?php echo htmlspecialchars($row['Grade']); ?></td>
                <td><?php echo htmlspecialchars($row['UploadedAt']); ?></td>
                <td>
                  <a href="../uploads/resources/<?php echo urlencode($row['FilePath']); ?>" class="btn btn-xs btn-primary" title="Download" download>
                    <i class="fa fa-download"></i>
                  </a>
                  <a href="delete_resource.php?id=<?php echo $row['Id']; ?>" class="btn btn-xs btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this resource?')">
                    <i class="fa fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

</div>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../dist/js/app.min.js"></script>
</body>
</html>
