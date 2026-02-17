<?php
include_once(__DIR__ . "/../../partials/paths.php"); 

include_once(BASE_PATH . "/partials/session_init.php"); 

include_once(BASE_PATH . "/partials/connect.php"); 

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $agreed = isset($_POST['agree']);

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($surname)) $errors[] = "Surname is required.";
    if (!empty($contact) && !preg_match('/^[0-9+\-\s]+$/', $contact)) {
        $errors[] = "Contact can only contain numbers, spaces, + or -.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid Email is required.";
    if (!$agreed) $errors[] = "You must agree to the Rules & Pricing before submitting.";
    
    if (empty($errors)) {
    // Check if email already exists
    $checkSql = "SELECT 1 FROM inviterequests WHERE email = ? LIMIT 1";
    if ($checkStmt = $connect->prepare($checkSql)) {
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = "This email has already been used to request an invite.";
        } else {
            // Safe to insert new request
            $sql = "INSERT INTO inviterequests (name, surname, email, contact, message) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $connect->prepare($sql)) {
                $stmt->bind_param("sssss", $name, $surname, $email, $contact, $message);
                if ($stmt->execute()) {
                    $success = "Your invite request has been submitted. Please keep an eye on your email — we’ll be in touch shortly.";
                    $name = $surname = $email = $contact = $message = '';
                } else {
                    $errors[] = "Database error. Please try again later.";
                }
                $stmt->close();
            } else {
                $errors[] = "Database error. Please try again later.";
            }
        }
        $checkStmt->close();
    } else {
        $errors[] = "Database error. Please try again later.";
    }
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Request Invite</title>
  <style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background-color: #e8eff1;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        
        margin: 50px auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        padding: 30px;
    }

    img {
        display: block;
        margin: 0 auto 20px auto;
        max-width: 200px;
        height: auto;
    }

    h3 {
        text-align: center;
        margin-bottom: 20px;
    }

    .message {
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .error { color: red; }
    .success { color: green; }

    input, textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
        min-height: 60px;
    }

    button[type="submit"] {
        width: 100%;
        padding: 8px;
        background-color: #007bff;
        color: white;
        font-size: 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    .name-surname-row {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .name-surname-row > div { flex: 1; }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        margin-bottom: 20px;
        cursor: pointer;
        padding: 5px 8px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .checkbox-wrapper input[type="checkbox"] { display: none; }

    .checkbox-wrapper .custom-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid #ccc;
        background-color: #f9f9f9;
        position: relative;
    }

    .checkbox-wrapper input:checked + .custom-checkbox::after {
        content: '';
        position: absolute;
        width: 5px;
        height: 10px;
        border: solid #007bff;
        border-width: 0 2px 2px 0;
        top: 2px;
        left: 6px;
        transform: rotate(45deg);
    }

    .checkbox-enabled {
        background-color: #e6f0ff;
        border: 1px solid #007bff;
    }

    .checkbox-wrapper a { color: #007bff; text-decoration: none; }
    .checkbox-wrapper a:hover { text-decoration: underline; }

    #rules-pricing {
        background-color: #f4f4f4;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        font-size: 14px;
    }

    #rules-pricing p {
        margin-bottom: 10px;
        line-height: 1.5;
        font-size: 14px;
    }

    .back-link:hover { text-decoration: underline; }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
      margin-bottom: 8px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 4px;
      text-align: center;
      font-size: 14px;
    }

    th {
      background-color: white;
      color: black;
    }
    
    button[type="submit"]:disabled {
        background-color: #b5b5b5 !important;
        color: #666 !important;
        border-color: #999 !important;
        cursor: not-allowed !important;
        opacity: 1 !important;
    }
    
    button[type="submit"]:disabled:hover {
        background-color: #b5b5b5 !important;
    }

  </style>
</head>
<body>

<div class="container">
    <!-- Image above form -->
    <img src="../../uploads/ProfilePictures/doep.png" alt="Request Invite">
    <h3>Request an Invite</h3>
    <p>Only for learner registration.</p>

    <?php if ($errors): ?>
        <div class="message error"><?php echo implode('<br>', $errors); ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="request_invite.php" method="post">
        <div class="name-surname-row">
            <div>
                <input type="text" name="name" maxlength="150" placeholder="Name *" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            </div>
            <div>
                <input type="text" name="surname" maxlength="150" placeholder="Surname *" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
            </div>
        </div>
        
        <div class="name-surname-row">
            <div>
              <input type="email" name="email" maxlength="100" placeholder="Email *" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            <div>
              <input type="text" name="contact" maxlength="20" placeholder="Contact Number *" value="<?php echo htmlspecialchars($contact ?? ''); ?>" required>
            </div>
        </div>


        <textarea name="message" maxlength="500" placeholder="Why you would like to join us. (Optional)"><?php echo htmlspecialchars($message ?? ''); ?></textarea>


        
        <label class="checkbox-wrapper">
            <input type="checkbox" id="agreeCheckbox" name="agree">
            <span class="custom-checkbox"></span>
            I agree to the <a href="#rules-pricing">Pricing & Consent</a>
        </label>


        <button type="submit" id="submitBtn" class="btn btn-primary btn-block" disabled>Submit Request</button>
        
        <p id="agreeWarning" style="text-align:center; color:red; font-size:13px; margin-top:8px; display:block;">
            Please agree to the pricing & consent before submitting.
        </p>
        
        

        <div style="text-align: center; margin-top: 10px;">
          <label style="font-size: 13px;">
            Back to  
            <a href="login.php" class="back-link">Login</a>
          </label>
        </div>
    </form>

    <div id="rules-pricing">
    <h4>Payment Information</h4>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>3 Months</th>
                <th>6 Months</th>
                <th>12 Months</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Mathematics</td>
                <td>R540.00</td>
                <td>R1000.00</td>
                <td>R1800.00</td>
            </tr>
            <tr>
                <td>Physical Sciences</td>
                <td>R540.00</td>
                <td>R1000.00</td>
                <td>R1800.00</td>
            </tr>
        </tbody>
    </table>

    <h4>Payment Options</h4>
    <ul>
        <li>✔ Monthly instalments from <strong>R180/month</strong>.</li>
        <li>✔ Pay once-off or split into monthly payments.</li>
        <li>✔ Custom plans available upon request.</li>
    </ul>

    <p>
        <strong>Contact:</strong><br>
        📧 <a href="mailto:info@doetutoring.com">info@doetutoring.com</a><br>
    </p>

    <hr style="margin: 20px 0;">

    <h4>Privacy & Personal Information Consent</h4>
    <p>
        When requesting an invite, you provide us with the following information:
    </p>

    <ul>
        <li>Your Name</li>
        <li>Your Surname</li>
        <li>Your Email Address</li>
        <li>Your Message (optional)</li>
    </ul>

    <p>
        This information is collected for the following purposes:
    </p>

    <ul>
        <li>To contact you regarding your invite request</li>
        <li>To verify and manage your application</li>
        <li>To keep a record of invite submissions</li>
    </ul>

    <p>
        We do <strong>not</strong> sell, share, or give your personal information to any external parties.
        Your information is stored securely and is used only for internal admin and communication.
    </p>

    <p>
        By ticking the checkbox and submitting this form, you confirm that you:
    </p>

    <ul>
        <li>✔ Agree to the pricing and payment information listed above</li>
        <li>✔ Understand why your personal information is being collected</li>
        <li>✔ Give consent for us to store and use your information for invite processing</li>
    </ul>

    <p>If you have any questions about data privacy, contact us at:
        <a href="mailto:info@doetutoring.com">info@doetutoring.com</a>
    </p>
</div>


</div>


<script>
    const checkbox = document.getElementById('agreeCheckbox');
    const submitBtn = document.getElementById('submitBtn');
    const agreeWarning = document.getElementById('agreeWarning');

    checkbox.addEventListener('change', function () {
        submitBtn.disabled = !this.checked;
        agreeWarning.style.display = this.checked ? 'none' : 'block';
    });
</script>

</body>
</html>
