<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../common/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schoolName = trim($_POST['schoolName'] ?? '');
    $schoolEmail = trim($_POST['schoolEmail'] ?? '');
    $schoolContact = trim($_POST['schoolContact'] ?? '');
    $schoolAddress = trim($_POST['schoolAddress'] ?? '');
    $subjects = $_POST['subjects'] ?? [];
    $grades = $_POST['grades'] ?? [];


    if ($schoolName === '') {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Missing School Name',
                text: 'Please enter a school name.',
            }).then(() => { window.history.back(); });
        </script>";
        exit();
    }

    if (count($subjects) === 0 || count($subjects) !== count($grades)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Data',
                text: 'Please provide valid subject-grade pairs.',
            }).then(() => { window.history.back(); });
        </script>";
        exit();
    }

    $connect->begin_transaction();
    try {


        $stmt = $connect->prepare("INSERT INTO schools (SchoolName, Address, ContactNumber, Email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $schoolName, $schoolAddress, $schoolContact, $schoolEmail);

        if ($stmt->execute()) {
            $schoolId = $stmt->insert_id;
            // Success: you can now use $schoolId if needed
        } else {
            // Handle DB error
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        	
        $stmt2 = $connect->prepare("INSERT INTO schoolsubjects (SchoolId, SubjectId, Grade) VALUES (?, ?, ?)");
        for ($i = 0; $i < count($subjects); $i++) {
            $subject = trim($subjects[$i]);
            $grade = trim($grades[$i]);

            if ($subject === '' || !in_array($grade, ['8', '9', '10', '11', '12'])) {
                throw new Exception("Invalid subject or grade at pair " . ($i + 1));
            }

            $stmt2->bind_param("iii", $schoolId, $subject, $grade);
            $stmt2->execute();
        }
        $stmt2->close();
        $connect->commit();

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'School Saved',
                text: 'School and subjects were added successfully.',
            }).then(() => { window.location = 'addschool.php'; });
        </script>";

    } catch (Exception $e) {
        $connect->rollback();
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: '" . addslashes($e->getMessage()) . "',
            }).then(() => { window.history.back(); });
        </script>";
        exit();
    }

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
    <h1>Add School <small>Form</small></h1>
    <ol class="breadcrumb">
      <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Add School</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-body">
        <form action="addschool.php" method="post">

          <fieldset class="tab">
            <legend>School Info</legend>
            <div class="form-group row">
              <div class="col-md-3">
                <label>School Name <span style="color:red">*</span></label>
                <input type="text" class="form-control" name="schoolName" required>
              </div>
              <div class="col-md-3">
                <label>Email</label>
                <input type="email" class="form-control" name="schoolEmail">
              </div>
              <div class="col-md-3">
                <label>Contact Number</label>
                <input type="tel" class="form-control" name="schoolContact">
              </div>
              <div class="col-md-3">
                <label>Address</label>
                <textarea class="form-control" name="schoolAddress" rows="1"></textarea>
              </div>
            </div>
          </fieldset><br>

          <fieldset class="tab">
            <legend>Grades and Subjects</legend>
            <div id="subjectGradePairs">
              <div class="form-group row pair">
                <div class="col-md-6">
                  <label>Subject</label>
                  <select class="form-control" name="subjects[]" required>
                    <option value="">Select Subject</option>
                    <?php
                      $subjectsList = [
                        "Accounting", "Afrikaans First Additional Language", "Agricultural Sciences", "Business Studies",
                        "Computer Applications Technology", "Consumer Studies", "Creative Arts", "Dramatic Arts",
                        "Economic and Management Sciences", "Economics", "Engineering Graphics and Design", 
                        "English First Additional Language", "English Home Language", "Geography", "History",
                        "Information Technology", "IsiXhosa First Additional Language", "IsiZulu First Additional Language",
                        "Life Orientation", "Life Sciences", "Mathematical Literacy", "Mathematics", "Music",
                        "Natural Sciences", "Physical Sciences", "Religious Studies", "Sesotho First Additional Language",
                        "Social Sciences", "Technology", "Tourism", "Visual Arts"
                      ];
                      sort($subjectsList);
                      foreach ($subjectsList as $subject) {
                        echo "<option value=\"$subject\">$subject</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Grade</label>
                  <select class="form-control" name="grades[]" required>
                    <option value="">Select Grade</option>
                    <option value="8">Grade 8</option>
                    <option value="9">Grade 9</option>
                    <option value="10">Grade 10</option>
                    <option value="11">Grade 11</option>
                    <option value="12">Grade 12</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label style="visibility:hidden;">Remove</label>
                  <button type="button" class="btn btn-danger btn-block removePair"><i class="fa fa-trash"></i></button>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-default" id="addPair"><i class="fa fa-plus"></i> Add Another</button>
          </fieldset><br>

          <div class="text-center">
            <button type="submit" class="btn btn-primary">Save School</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script>
  document.getElementById('addPair').addEventListener('click', () => {
    const container = document.getElementById('subjectGradePairs');
    const firstPair = document.querySelector('.pair');
    const newPair = firstPair.cloneNode(true);
    newPair.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
    container.appendChild(newPair);
  });

  document.addEventListener('click', e => {
    if (e.target.closest('.removePair')) {
      const allPairs = document.querySelectorAll('.pair');
      if (allPairs.length > 1) {
        e.target.closest('.pair').remove();
      } else {
        alert('At least one subject-grade pair is required.');
      }
    }
  });
</script>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</div>
</body>
</html>
