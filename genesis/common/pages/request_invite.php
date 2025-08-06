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
    if (!$agreed) $errors[] = "You must agree to the Rules and Payment Info before submitting.";

    if (empty($errors)) {
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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
      overflow: hidden;
    }

    .tabs {
      display: flex;
      border-bottom: 1px solid #ccc;
    }

    .tabs button {
      flex: 1;
      padding: 12px;
      background: #f4f4f4;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }

    .tabs button.active {
      background: white;
      border-bottom: 3px solid #007bff;
      color: #007bff;
    }

    .tab-content {
      padding: 20px;
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .error { color: red; }
    .success { color: green; }

    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }

    input, textarea {
      width: 100%;
      padding: 8px;
      font-size: 14px;
      margin-bottom: 10px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    textarea {
      resize: vertical;
    }

    button[type="submit"] {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #0056b3;
    }

    .name-surname-row {
      display: flex;
      gap: 10px;
    }

    .name-surname-row > div {
      flex: 1;
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      margin: 15px 0;
      gap: 10px;
      font-weight: normal;
    }

    .checkbox-wrapper input[type="checkbox"] {
      display: none;
    }

    .custom-checkbox {
      width: 18px;
      height: 18px;
      border-radius: 4px;
      display: inline-block;
      border: 2px solid red;
      background-color: #fdd;
      position: relative;
      transition: all 0.3s ease;
    }

    .checkbox-enabled .custom-checkbox {
      border-color: #007bff;
      background-color: white;
      cursor: pointer;
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
      background-color: #007bff;
      color: white;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="tabs">
      <button class="tab-link" data-tab="rules">📘 Rules</button>
      <button class="tab-link" data-tab="pricing">💰 Pricing</button>
      <button class="tab-link active" data-tab="form">📝 Request Form</button>
    </div>

    <div id="rules" class="tab-content">
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

    <div id="pricing" class="tab-content">
      <h3>Pricing & Payment Information</h3>
      <table>
        <thead>
          <tr>
            <th>Duration</th>
            <th>Mathematics</th>
            <th>Physical Sciences</th>
            <th>Both Subjects</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>3 Months</td>
            <td>R450.00</td>
            <td>R450.00</td>
            <td>R850.00</td>
          </tr>
          <tr>
            <td>6 Months</td>
            <td>R750.00</td>
            <td>R750.00</td>
            <td>R1250.00</td>
          </tr>
          <tr>
            <td>12 Months</td>
            <td>R1199.00</td>
            <td>R1199.00</td>
            <td>R1950.00</td>
          </tr>
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
    </div>

    <div id="form" class="tab-content active">
      <h3>Request an Invite</h3>

      <?php if ($errors): ?>
        <div class="message error"><?php echo implode('<br>', $errors); ?></div>
      <?php elseif ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
      <?php endif; ?>

      <form action="request_invite.php" method="post" novalidate>
        <div class="name-surname-row">
          <div>
            <label for="name">Name *</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
          </div>
          <div>
            <label for="surname">Surname *</label>
            <input type="text" name="surname" id="surname" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
          </div>
        </div>

        <label for="email">Email *</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

        <label for="message">Message (optional)</label>
        <textarea name="message" id="message"><?php echo htmlspecialchars($message ?? ''); ?></textarea>

        <label class="checkbox-wrapper" id="checkboxWrapper">
          <input type="checkbox" name="agree" id="agreeCheckbox" disabled>
          <span class="custom-checkbox"></span>
          I have read and agree to the Rules and Payment Info.
        </label>

        <button type="submit">Submit Request</button>
      </form>
        <a href="login.php" class="back-link">Back to Login</a>
    </div>
  </div>

  <script>
    const tabs = document.querySelectorAll(".tab-link");
    const contents = document.querySelectorAll(".tab-content");
    const agreeCheckbox = document.getElementById("agreeCheckbox");
    const checkboxWrapper = document.getElementById("checkboxWrapper");

    let rulesViewed = false;
    let pricingViewed = false;

    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        tabs.forEach(t => t.classList.remove("active"));
        contents.forEach(c => c.classList.remove("active"));

        tab.classList.add("active");
        document.getElementById(tab.dataset.tab).classList.add("active");

        if (tab.dataset.tab === "rules") rulesViewed = true;
        if (tab.dataset.tab === "pricing") pricingViewed = true;

        if (rulesViewed && pricingViewed) {
          agreeCheckbox.disabled = false;
          checkboxWrapper.classList.add("checkbox-enabled");
        }
      });
    });
  </script>

</body>
</html>