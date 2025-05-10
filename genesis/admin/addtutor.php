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
    
    <?php include("adminpartials/header.php"); ?>
    <?php include("adminpartials/mainsidebar.php"); ?>

    <div class="content-wrapper">
      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

      <section class="content">
        <div class="row">
          <div class="col-xs-12">

            <div class="box">
            <div class="box-title" style="text-align: center;">
              <h3 class="box-title" >Register Tutor</h3>
            </div><br>


              <div class="box-body">

                <?php
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\SMTP;
                use PHPMailer\PHPMailer\Exception;

                require '../../vendor/autoload.php';
                include '../partials/Connect.php';

                $errors = [];
                $name = $email = $password = '';

                if (isset($_SESSION['Name'])) {
                  $name = $_SESSION['Name'];
                }

                if (isset($_SESSION['Email'])) {
                  $Email = $_SESSION['Email'];
                }

                if (isset($_POST['reg'])) {
                  $password = $_POST['password'];
                  $name = trim($_POST['name']);
                  $surname = trim($_POST['surname']);
                  $contactnumber = $_POST['contactnumber'];
                  $email = $_POST['email'];
                  $specialisation = $_POST['specialization'];

                  if (empty($name) || empty($email) || empty($password) || empty($surname) || empty($identityNo) || empty($contactnumber)) {
                    $errors[] = "All fields are required.";
                  }
                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Invalid email address.";
                  }

                  if (count($errors) === 0) {
                    $_SESSION['Name'] = $name;
                    $_SESSION['Email'] = $email;

                    $stmt = $connect->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR UserId = ?");
                    $stmt->bind_param("ss", $email, $identityNo);
                    $stmt->execute();
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();

                    if ($count > 0) {
                      $_SESSION['userexists'] = true;
                    } else {
                      $verificationToken = bin2hex(random_bytes(32));
                      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                      $connect->begin_transaction();
                      try {
                        $stmt = $connect->prepare("INSERT INTO users (Surname, Name, UserPassword, Contact, Email, IsVerified, VerificationToken, RegistrationDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, Now())");
                        $stmt->bind_param("sssiss", $surname, $name, $hashedPassword,$contactnumber, $email, $verificationToken);

                        if ($stmt->execute() === TRUE) {
                          $userFakeid = $connect->insert_id;

                          $stmt2 = $connect->prepare("INSERT INTO employee(Id, StartDate, employeeType ,Specialisation) VALUES(?, Now(), 1, ?)");
                          $stmt2->bind_param("is", $userFakeid, $specialisation);

                          if ($stmt2->execute() === TRUE) {
                            $connect->commit();

                            $mail = new PHPMailer(true);
                            try {
                              $mail->isSMTP();
                              $mail->Host = 'smtp.gmail.com';
                              $mail->SMTPAuth = true;
                              $mail->Username = 'vilakazinurse128@gmail.com';
                              $mail->Password = 'mvjmvkiowhpohtlk';
                              $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                              $mail->Port = 465;

                              $mail->setFrom('vilakazinurse128@gmail.com', 'Auticare_Connect');
                              $mail->addAddress($email, $name);
                              $mail->addReplyTo('vilakazinurse128@gmail.com', 'Auticare_Connect');

                              $mail->isHTML(true);
                              $mail->Subject = 'EMAIL VERIFICATION';
                              $mail->Body = '
                              <p>Dear ' . $name . ',</p>
                              <p>Welcome to Auticare_Connect!</p>
                              <p>To complete your registration and activate your account, please verify your email by clicking the link below:</p>
                              <a href="http://localhost/code-masters/team02-main/auticare/admin/verification.php?token=' . $verificationToken . '" style="background-color: #008CBA; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Verify Email</a>
                              <p>We have created an account for you as one of the Teachers at Tamarisk Primary School.</p>
                              <p>Warm regards,<br>Auticare_Connect Team</p>';

                              $mail->send();
                              $_SESSION['Success'] = true;
                            } catch (Exception $e) {
                              $errors[] = "Error while sending email: " . $mail->ErrorInfo;
                            }
                          } else {
                            $connect->rollback();
                            $_SESSION['Error'] = true;
                          }
                        } else {
                          $connect->rollback();
                          $_SESSION['Error'] = true;
                        }
                      } catch (Exception $e) {
                        $connect->rollback();
                        $_SESSION['Error'] = true;
                      }
                      $connect->close();
                    }
                  }
                }
                ?>

                <form action="addteacher.php" method="POST">
                  <div class="pos">
                    <?php
                    if (isset($_SESSION['Success']) && $_SESSION['Success']) {
                      echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Teacher Successfully Registered",
                            text: "An email has been sent to the Teacher to verify.",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            window.location.href = "addteacher.php";
                        });
                      </script>';
                      $_SESSION['Success'] = false;
                    }

                    if (isset($_SESSION['Error']) && $_SESSION['Error']) {
                      echo "<script>
                          Swal.fire({
                              icon: 'error',
                              title: 'Teacher Unsuccessfully Registered',
                              text: 'No email has been sent to the Teacher to verify.',
                              confirmButtonText: 'OK'
                          }).then(function() {
                              window.location = 'addteacher.php'; 
                          });
                        </script>";
                      $_SESSION['Error'] = false;
                    }

                    if (isset($_SESSION['userexists']) && $_SESSION['userexists']) {
                      echo "<script>
                          Swal.fire({
                              icon: 'error',
                              title: 'Teacher Already Exists',
                              text: 'No email has been sent to the Teacher to verify.',
                              confirmButtonText: 'OK'
                          }).then(function() {
                              window.location = 'addteacher.php'; 
                          });
                        </script>";
                      $_SESSION['userexists'] = false;
                    }
                    ?>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="name">First Names</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Names" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="surname">Surname</label>
                      <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-3">
                      <label for="specialization">Grade & Subject</label>
                      <select id="specialization" name="specialization" class="form-control">
                        <option value="m10">Mathematics 10</option>
                        <option value="m11">Mathematics 11</option>
                        <option value="m12">Mathematics 12</option>
                        <option value="ps10">Physical Sciences 10</option>
                        <option value="ps11">Physical Sciences 11</option>
                        <option value="ps12">Physical Sciences 12</option>
                      </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="gender">Title</label>
                      <select id="gender" name="gender" class="form-control">
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Ms">Ms</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-3">
                      <label for="contactnumber">Contact Number (10 digits)</label>
                      <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                  </div>

                  <input type="hidden" id="password" name="password" value="12345">

                  <div class="text-center" style="margin-top: 20px;">
                  <button type="submit" class="btn btn-primary" name="reg">Register The Tutor</button>
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

  <!-- Scripts -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <script src="bower_components/fastclick/lib/fastclick.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script src="dist/js/demo.js"></script>

  <script>
    $(function () {
      $('#example1').DataTable()
    })
  </script>

</body>
</html>
