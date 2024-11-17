<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset</title>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Images/Favi.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Images/Favi.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web/fontawesome-free-6.4.0-web/css/all.css">
    <link rel="stylesheet" type="text/css" href="Partials/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <div class="container d-flex flex-column">
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-12 col-md-8 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-4 text-center ">
                            <h5>Forgot Password?</h5>
                        </div>
                        <?php
                        if (isset($_SESSION['error_message'])) {
                            echo '<div class="alert alert-danger rounded-pill text-center" style="border-color: #FF5733; height: 70px;">';
                            echo '<p style="">' . $_SESSION['error_message'] . '</p>';
                            echo '</div>';
                            unset($_SESSION['error_message']);
                        }
                        ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter your email address and we will send you a code to
                                    reset your password.</label>
                            </div>
                           
                            <div class="mb-3 pt-3">

                                <input type="email" id="email" class="form-control" name="email"
                                    placeholder="Enter Your Email" required="">
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <input type="submit" name="Submit" value="Reset Password" class="btn"
                                    style="background-color: blue; color:white;">
                                    
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <?php
 
 include '../partials/Connect.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);   //come back to set them to zero
    error_reporting(E_ALL);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require '../../vendor/autoload.php';  
   
    session_start();

    if (isset($_POST['Submit'])) {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        // Check if the user exists and is verified
        $stmt = $connect->prepare("SELECT Id, Email, Surname FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $email, $surname);
        $stmt->fetch();
        $stmt->close();

        if ($id ) {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $codeLength = 6;
            $randomCode = '';
            for ($i = 0; $i < $codeLength; $i++) {
                $randomIndex = rand(0, strlen($characters) - 1);
                $randomCode .= $characters[$randomIndex];
            }
            $hashedResetCode = password_hash($randomCode, PASSWORD_BCRYPT);
            $timestamp = date("Y-m-d H:i:s");
            $updateQuery = "UPDATE users SET ResetCode = '$hashedResetCode', ResetTimestamp = '$timestamp' WHERE Email = '$email'";

            if (mysqli_query($connect, $updateQuery)) {
                // Send the reset code to the user via email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'vilakazinurse128@gmail.com';
                    $mail->Password = 'mvjmvkiowhpohtlk';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    // Recipients
                    $mail->setFrom('vilakazinurse128@gmail.com', 'AUTI_CARE CONNECT');
                    $mail->addAddress($email, $surname);
                    $mail->addReplyTo('vilakazinurse128@gmail.com', 'AUTI_CARE CONNECT');

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'PASSWORD RESET CODE';
                    $mail->Body = '
                    <p>Dear Mr/Ms ' . $surname . ',</p>
                    
                    <p>We have received a request to reset your password at Auti-Care Connect.</p>
                    
                    <p>Your password reset code is:</p>
                    
                    <p><strong>' . $randomCode . '</strong></p>
                    
                    <p>Please use this code to reset your password. If you did not request this password reset, please disregard this email.</p>
                    
                    <p>If you have any questions or need assistance with the password reset process, please do not hesitate to contact our support team  by phone at (123) 456-7890.</p>
                    
                    <p>Best regards,</p>
                    <p>Auti-Care Connect</p>
                    <p>Email: support@example.com</p>
                    <p>Phone: (123) 456-7890</p>
                     ';

                    $mail->send();
                    $_SESSION['reset_message'] = "A reset code has been sent to your email address. Please check your email and use the code to reset your password.";
                    header('Location: reset.php');
                    exit;
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
                }
            } else {
                echo "Error updating record: " . mysqli_error($connect);
            }
        } else {
            $_SESSION['error_message'] = "User not found or not verified.";
            header('Location: forgotpassword.php');
            exit;

        }
    }

    mysqli_close($connect);
    ?>
</body>

</html>