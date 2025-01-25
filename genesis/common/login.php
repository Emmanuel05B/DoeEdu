<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e8eff1;
            margin: 0;
            padding: 0;
        }

        /* Container for the whole login section */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 70%;
            max-width: 1000px;
            display: flex;
            overflow: hidden;
        }

        /* Left column with the image */
        .image-column {
            flex: 1;
            background: #f0f4f8;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-column img {
            max-width: 80%;
            border-radius: 0px;
            object-fit: cover;
        }

        /* Right column with the form */
        .form-column {
            flex: 1.5;
            padding: 30px;
        }

        .form-column h2 {
            text-align: center;
            font-size: 30px;
            color: #333;
            margin-bottom: 20px;
        }

        .container {
            padding: 16px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 0; /* Remove margin to make them align perfectly */
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
            box-sizing: border-box; /* Include padding and border in width calculation */
            transition: border-color 0.3s ease-in-out;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .loginbtn {
            width: 100%; /* Ensures the button takes up the full width */
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-sizing: border-box; /* Ensure button padding and border are included */
        }

        .loginbtn:hover {
            background-color: #0056b3;
        }

        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            background-color: #e2e2e2;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .cancelbtn:hover {
            background-color: #c4c4c4;
        }

        .psw {
            float: right;
            font-size: 14px;
        }

        /* Error message styling */
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .login-box {
                flex-direction: column;
                width: 90%;
            }

            .image-column {
                display: none;
            }

            .form-column {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<?php
session_start();

if(isset($_POST['login'])){
    include('../partials/connect.php');
      
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $userType = $_POST['Radio'];

    $errors = []; 

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '<span class="error-message"> Invalid Email Address.</span>';
    }
    if (empty($password)) {
        $errors[] = '<span class="error-message">Password is required.</span>';
    }

    $sql = "SELECT users.Id, users.Email, users.UserPassword, employee.employeeType  
    FROM users  
    JOIN employee ON users.Id = employee.Id 
    WHERE users.Email = ?";

    if ($stmt = $connect->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $final = $result->fetch_assoc();
        $stmt->close();

        if (!empty($final)) {
            if (password_verify($password, $final['UserPassword'])) {
                $_SESSION['user_id'] = $final['Id'];
                $_SESSION['EmployeeType'] = $final['employeeType'];
                $_SESSION['email'] = $final['Email'];

                switch ($final['employeeType']) {
                    case '1': 
                        header('Location: ../teacher/modal.php');
                        break;
                    case '0': 
                        header('Location: ../admin/adminindex.php');
                        break;
                    default:
                        $_SESSION['error_message'] = '<span class="error-message">User Not Registered.</span>';
                        header('Location: login.php');
                        break;
                }
                exit;
            } else {
                $_SESSION['error_message'] = '<span class="error-message">Invalid password.</span>';
                header('Location: login.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = '<span class="error-message">Email does not exist.</span>';
            header('Location: login.php?');
            exit;
        }
    } else {
        $_SESSION['error_message'] = '<span class="error-message">System Offline.</span>';
        header('Location: login.php');
        exit;
    }
}
?>

<!-- Login Container -->
<div class="login-container">
    <div class="login-box">
        <!-- Left image column -->
        <div class="image-column">
            <img src="../admin/images/doe.png" alt="Login Image"> <!-- Replace with the actual image path -->
        </div>

        <!-- Right form column -->
        <div class="form-column">
            <h2>Login</h2>
            
            <?php
            if (isset($_SESSION['error_message'])) {
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            }
            ?>
            
            <!-- Login Form -->
            <form action="login.php" method="post">
                <div class="container">
                    <label for="email"><b>Email</b></label> <br>
                    <input type="text" placeholder="Enter Email" id="email" name="email" required><br>

                    <label for="password"><b>Password</b></label><br>
                    <input type="password" placeholder="Enter Password" id="password" name="password" required><br><br>

                    <button type="submit" class="loginbtn" name="login">Login</button><br>
                    <label>
                        <input type="checkbox" checked="checked" name="remember"> Remember me
                    </label>
                </div>

                <div class="container" style="background-color:#f1f1f1">
                    <button type="button" class="cancelbtn">Cancel</button>
                    <span class="psw">Reset <a href="forgotpassword.php">password?</a></span>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
