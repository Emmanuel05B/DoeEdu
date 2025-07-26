
<?php
session_start();
include('../partials/connect.php'); // adjust path to your DB connection

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($surname)) {
        $errors[] = "Surname is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid Email is required.";
    }
    if (empty($_POST['agree'])) {
        $errors[] = "You must agree to the Rules and Payment Terms before submitting.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO inviterequests (name, surname, email, message) VALUES (?, ?, ?, ?)";
        if ($stmt = $connect->prepare($sql)) {
            $stmt->bind_param("ssss", $name, $surname, $email, $message);
            if ($stmt->execute()) {
                $success = "Your invite request has been submitted. We will contact you soon!";
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
<title>Request an Invite</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #e8eff1;
    margin: 0; padding: 0;
  }
  .container {
    max-width: 450px;
    margin: 50px auto;
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
    padding: 10px;
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

  .info-section {
    margin-top: 20px;
    font-size: 14px;
  }
  .info-section p {
    margin: 0 0 10px;
  }
  .info-section a {
    color: #007bff;
    text-decoration: none;
  }
  .info-section a:hover {
    text-decoration: underline;
  }
  .checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    font-weight: normal;
  }
</style>
</head>
<body>

<div class="container">
  <h2>Request an Invite</h2>

  <?php if ($errors): ?>
    <div class="message error">
      <?php echo implode('<br>', $errors); ?>
    </div>
  <?php elseif ($success): ?>
    <div class="message success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form action="request_invite.php" method="post" novalidate>
    <div class="name-surname-row">
      <div>
        <label for="name">Name *</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required />
      </div>
      <div>
        <label for="surname">Surname *</label>
        <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required />
      </div>
    </div>

    <label for="email">Email *</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required />

    <label for="message">Message (optional)</label>
    <textarea id="message" name="message"><?php echo htmlspecialchars($message ?? ''); ?></textarea>

    <div class="info-section">
      <p>
        📘 Please read our 
        <a href="rules.php" target="_blank">Rules & T&Cs</a> and 
        <a href="pricing.php" target="_blank">Fees & Payment Info</a> before requesting an invite.
      </p>
      <label class="checkbox-label">
        <input type="checkbox" name="agree" required />
        I have read and agree to the Rules, T&Cs, and Payment Policy.
      </label>
    </div>

    <button type="submit">Submit Request</button>
  </form>

  <a href="login.php" class="back-link">Back to Login</a>
</div>

</body>
</html>
