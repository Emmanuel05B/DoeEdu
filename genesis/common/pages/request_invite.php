<?php
session_start();
include('../../partials/connect.php');

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $agreed = isset($_POST['agree']);

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($surname)) $errors[] = "Surname is required.";
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
            $sql = "INSERT INTO inviterequests (name, surname, email, message) VALUES (?, ?, ?, ?)";
            if ($stmt = $connect->prepare($sql)) {
                $stmt->bind_param("ssss", $name, $surname, $email, $message);
                if ($stmt->execute()) {
                    $success = "Your invite request has been submitted. Please keep an eye on your email — we’ll be in touch shortly.";
                    $name = $surname = $email = $message = '';
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
      margin-top: 15px;
      margin-bottom: 15px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #5e88afff;
      color: white;
    }

  </style>
</head>
<body>

<div class="container">
    <!-- Image above form -->
    <img src="../../admin/images/westtt.png" alt="Request Invite">
    <h3>Request an Invite</h3>

    <?php if ($errors): ?>
        <div class="message error"><?php echo implode('<br>', $errors); ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="request_invite.php" method="post" novalidate>
        <div class="name-surname-row">
            <div>
                <input type="text" name="name" maxlength="150" placeholder="Name *" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            </div>
            <div>
                <input type="text" name="surname" maxlength="150" placeholder="Surname *" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
            </div>
        </div>

        <input type="email" name="email" maxlength="100" placeholder="Email *" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        <textarea name="message" maxlength="500" placeholder="Why you would like to join us. (Optional)"><?php echo htmlspecialchars($message ?? ''); ?></textarea>

        <label class="checkbox-wrapper" id="checkboxWrapper">
            <input type="checkbox" name="agree" id="agreeCheckbox" disabled>
            <span class="custom-checkbox"></span>
            I have read and agree to the <a href="#rules-pricing" id="rulesPricingLink">Rules & Pricing</a>
        </label>

        <button type="submit">Submit Request</button>

        <div style="text-align: center; margin-top: 10px;">
          <label style="font-size: 13px;">
            Back to  
            <a href="login.php" class="back-link">Login</a>
          </label>
        </div>
    </form>

    

    <div id="rules-pricing">
      <h4>Rules & Payment Information</h4>
      <h4>Pricing & Payment Information</h4>
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
            <td>R450.00</td>
            <td>R750.00</td>
            <td>R1199.00</td>
          </tr>
          <tr>
            <td>Physical Sciences</td>
            <td>R450.00</td>
            <td>R750.00</td>
            <td>R1199.00</td>
          </tr>
          <!-- Add more subjects here -->
        </tbody>
      </table>

      <h4>Payment Options</h4>
      <ul>
        <li>✅ Monthly instalments from <strong>R150/month</strong>.</li>
        <li>✅ Pay once-off or split into monthly payments.</li>
        <li>✅ Custom plans available upon request.</li>
      </ul>
      <p>
        <strong>Contact:</strong><br>
        📧 <a href="mailto:thedistributorsofedu@gmail.com">thedistributorsofedu@gmail.com</a><br>
        📞 <a href="tel:+27814618178">+27 81 461 8178</a>
      </p>

      
        <h3>Rules, Duties & Responsibilities</h3>
      <strong>Duties and Responsibilities of a Learner:</strong>
      <ol>
        <li><b>Civil Behaviour:</b> Treat tutors and peers with respect...</li>
        <li><b>Using Videos:</b> Do not share videos...</li>
        <li><b>Resource Use:</b> Use all resources but complement them with additional research.</li>
        <li><b>Seeking Clarity:</b> Contact your tutor as soon as anything is unclear.</li>
        <li><b>Personal Effort:</b> Complete all tasks independently...</li>
        <li><b>Accountability:</b> You are responsible for your academic outcomes...</li>
        <li><b>Payment Requirements:</b> Stick to your selected payment plan...</li>
        <li><b>Requirements for Access:</b> Ensure you have a smartphone and a data plan.</li>
        <li><b>Attendance:</b> Regular attendance is required...</li>
        <li><b>Service Schedule:</b> The service follows the school calendar...</li>
      </ol>

      <strong>Duties and Responsibilities of a Tutor:</strong>
      <ol>
        <li><b>Equitable Treatment:</b> Tutors must treat all learners fairly...</li>
        <li><b>Professional Boundaries:</b> Tutors must maintain academic-only relationships...</li>
        <li><b>Time Devotion:</b> Tutors are expected to spend at least 3 hours/week...</li>
        <li><b>Thorough Evaluation:</b> All work must be carefully reviewed...</li>
        <li><b>Prompt Assistance:</b> Educational videos must be provided on time.</li>
        <li><b>Guardian Communication:</b> Parents should be notified if homework isn't submitted.</li>
        <li><b>Quarterly Reports:</b> Tutors must send parents a progress report every term.</li>
      </ol>

      <strong>Every learner should report if:</strong>
      <ul>
        <li>The tutor appears incompetent or misses sessions.</li>
        <li>Exercises are not marked or feedback is lacking.</li>
        <li>Discriminatory behavior occurs.</li>
        <li>Tutors contact learners inappropriately outside hours.</li>
      </ul>
      <p>Email concerns to: <a href="mailto:thedistributorsofedu@gmail.com">thedistributorsofedu@gmail.com</a></p>

      <strong>Termination Clause:</strong>
      <p>Violation of the above rules may result in suspension or removal. No refunds for services already rendered.</p>

    </div>
</div>

<script>
const rulesPricingLink = document.getElementById('rulesPricingLink');
const agreeCheckbox = document.getElementById('agreeCheckbox');

rulesPricingLink.addEventListener('click', (e) => {
    e.preventDefault();
    agreeCheckbox.disabled = false;
    agreeCheckbox.parentElement.classList.add('checkbox-enabled');

    const rulesSection = document.getElementById('rules-pricing');
    rulesSection.scrollIntoView({ behavior: 'smooth' });
});
</script>

</body>
</html>
