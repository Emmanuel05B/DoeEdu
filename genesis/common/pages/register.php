<?php
include('../partials/connect.php');

if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid invite link. No token provided.");
}

$token = $_GET['token'];

$stmt = $connect->prepare("SELECT Id, Email, IsUsed, ExpiresAt FROM invitetokens WHERE Token = ? LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) die("Invalid invite token.");

$invite = $result->fetch_assoc();

if ($invite['IsUsed']) die("This invite link has already been used.");

if (strtotime($invite['ExpiresAt']) < time()) die("This invite link has expired.");

$invitedEmail = $invite['Email'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Learner Registration | AdminLTE 2</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="../admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../admin/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../admin/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="../admin/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../admin/dist/css/skins/skin-blue.min.css">
  <link rel="shortcut icon" href="../admin/images/favicon.ico">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #e8eff1 !important;
      margin: 0; padding: 0;
    }
    .container {
      max-width: 950px;
      margin: 30px auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      font-weight: bold;
      display: block;
      margin: 15px 0 5px;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="tel"],
    input[type="time"],
    select,
    textarea {
      width: 100%;
      padding: 8px;
      font-size: 14px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    textarea {
      resize: vertical;
      min-height: 80px;
    }
    button {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 12px;
      margin-top: 20px;
      width: 100%;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    .message {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
    }
    .error {
      color: red;
    }
    .success {
      color: green;
    }
    a.back-link {
      display: block;
      text-align: center;
      margin-top: 25px;
      color: #007bff;
      text-decoration: none;
    }
    a.back-link:hover {
      text-decoration: underline;
    }
    .name-surname-row {
      display: flex;
      gap: 10px;
    }
    .name-surname-row > div {
      flex: 1;
    }
  </style>
</head>

<body>
<div class="container">
  <div class="text-center">
    <img src="../admin/images/westtt.png" style="height: 100px;" alt="Logo">
    <h2>Learner Registration</h2>
  </div>

  <form action="addlearnerh.php" method="post">
    <input type="hidden" name="invite_token" value="<?php echo htmlspecialchars($token); ?>">

    <!-- Learner Info -->
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Learner Info</h3></div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-3">
            <label>First Name</label>
            <input type="text" name="name" required>
          </div>
          <div class="col-md-3">
            <label>Surname</label>
            <input type="text" name="surname" required>
          </div>
          <div class="col-md-3">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($invitedEmail); ?>" readonly>
          </div>
          <div class="col-md-3">
            <label>Contact Number (10 digits)</label>
            <input type="tel" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
          </div>
        </div><br>
        <div class="row">
          <div class="col-md-2">
            <label>Title</label>
            <select name="learnertitle" required>
              <option value="" disabled selected>Select Title</option>
              <option value="Mr">Mr.</option>
              <option value="Mrs">Mrs.</option>
              <option value="Ms">Ms.</option>
            </select>
          </div>
          <div class="col-md-2">
            <label>Grade</label>
            <select name="grade" required>
              <option value="" disabled selected>Select Grade</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
            </select>
          </div>
          <div class="col-md-2">
            <label>Knockout Time</label>
            <input type="time" name="knockout_time" required>
          </div>
          <div class="col-md-3">
            <label>Create Password</label>
            <input type="password" name="password" required>
          </div>
          <div class="col-md-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
          </div>
        </div>
      </div>
    </div>

    <!-- Subject Selection -->
    <div class="box box-info">
      <div class="box-header with-border"><h3 class="box-title">Subjects & Duration</h3></div>
      <div class="box-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Subject</th>
              <th>Not Registered</th>
              <th>3 Months</th>
              <th>6 Months</th>
              <th>12 Months</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Mathematics</td>
              <td><input type="radio" name="maths" value="0" checked></td>
              <td><input type="radio" name="maths" value="450.00"></td>
              <td><input type="radio" name="maths" value="750.00"></td>
              <td><input type="radio" name="maths" value="1199.00"></td>
            </tr>
            <tr>
              <td>Physical Sciences</td>
              <td><input type="radio" name="physics" value="0" checked></td>
              <td><input type="radio" name="physics" value="450.00"></td>
              <td><input type="radio" name="physics" value="750.00"></td>
              <td><input type="radio" name="physics" value="1199.00"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Levels -->
    <div class="box box-warning">
      <div class="box-header with-border"><h3 class="box-title">Performance Levels</h3></div>
      <div class="box-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Subject</th>
              <th>Current Level (1–7)</th>
              <th>Target Level (3–7)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Mathematics</td>
              <td>
                <select name="math-current" required>
                  <option value="">Select</option>
                  <option value="100">none</option>
                  <?php for ($i = 1; $i <= 7; $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
              </td>
              <td>
                <select name="math-target" required>
                  <option value="">Select</option>
                  <option value="100">none</option>
                  <?php for ($i = 3; $i <= 7; $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Physical Sciences</td>
              <td>
                <select name="physics-current" required>
                  <option value="">Select</option>
                  <option value="100">none</option>
                  <?php for ($i = 1; $i <= 7; $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
              </td>
              <td>
                <select name="physics-target" required>
                  <option value="">Select</option>
                  <option value="100">none</option>
                  <?php for ($i = 3; $i <= 7; $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Parent Info -->
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">Parent Info</h3></div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-3">
            <label>First Name</label>
            <input type="text" name="parentname" required>
          </div>
          <div class="col-md-3">
            <label>Surname</label>
            <input type="text" name="parentsurname" required>
          </div>
          <div class="col-md-3">
            <label>Email</label>
            <input type="email" name="parentemail" required>
          </div>
          <div class="col-md-3">
            <label>Contact Number</label>
            <input type="tel" name="parentcontact" pattern="[0-9]{10}" maxlength="10" required>
          </div>
          <div class="col-md-3">
            <label>Title</label>
            <select name="parenttitle" required>
              <option value="" disabled selected>Select Title</option>
              <option value="Mr">Mr.</option>
              <option value="Mrs">Mrs.</option>
              <option value="Ms">Ms.</option>
              <option value="Dr">Dr.</option>
              <option value="Prof">Prof.</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button type="submit"><i class="fa fa-check-circle"></i> Submit All Info</button>
    </div>
  </form>
</div>

<!-- AdminLTE Scripts -->
<script src="../admin/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../admin/dist/js/adminlte.min.js"></script>
</body>
</html>
