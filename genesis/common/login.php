<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <style>
      
      /* CSS styling to divide the screen into two columns */
      .column {
          float: left;
          width: calc(80% - 50px); /* Adjusting width to accommodate space between columns */
          padding: 10px;
          box-sizing: border-box;
          margin-bottom: 15px;
        
         
      }
      .col {
          text-align: center;
      }
      /* Clear floats after the columns */
      .column:last-child {
          float: right; /* Float the last column to the right to create separation */
      }

    
      body {font-family: Arial, Helvetica, sans-serif;}
      form {border: 3px solid #f1f1f1;}

      input[type=text], input[type=password] {
      width: 96%;
      padding: 12px;
      margin: 5px 0 5px 0;
      
      border: none;
      background: #f1f1f1;
      }


      .loginbtn {
      background-color: blue;
      color: white;
      padding: 15px 15px;
      margin: 2px;
      align: center;
      border: none;
      cursor: pointer;
      width: 99%;
      opacity: 0.9;
      }
      button:hover {
      opacity: 0.8;
      }

.cancelbtn {
width: auto;
padding: 10px 18px;
background-color: lightblue;
}

.container {
padding: 16px;
}

span.psw {
float: right;
padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
span.psw {
   display: block;
   float: none;
}
.cancelbtn {
   width: 100%;
}
}

.signin {
background-color: #f1f1f1;
text-align: center;
}

.oder1{
margin-top: 20px;
margin-bottom: 100px;
margin-right: 200px;
margin-left: 10px;
background-color: white;
 
}
</style>

</head>
<body>



<?php
session_start();

 //*************** *

if(isset($_POST['login'])){

    include('../partials/connect.php');
      
    $email=filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password=trim($_POST['password']);
    $userType = $_POST['Radio'];

    // $redirectPage = '';
    $errors = []; 

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = '<span style="color: red; font-weight: bold;"> Invalid Email Address.</span>';
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
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
    // Set session variables
    $_SESSION['user_id'] = $final['Id'];
    $_SESSION['EmployeeType'] = $final['employeeType'];
    $_SESSION['email'] = $final['Email'];


    // Redirect based on employee type
    switch ($final['employeeType']) {
        case '1': // teacher
            header('Location: ../teacher/modal.php');
            break;
        case '0': // admin
            header('Location: ../admin/adminindex.php');
            break;
        default:
           $_SESSION['error_message'] = '<span style="color: red; font-weight: bold;">User Not Registered.</span>';

            header('Location: login.php');
            break;
    }
    exit;
} else {
    // incorrect Password
    $_SESSION['error_message'] = '<span style="color: red; font-weight: bold;">Invalid password.</span>';
    header('Location: login.php');
    exit;
}
} else {
// Email does not exist
$_SESSION['error_message'] = '<span style="color: red; font-weight: bold;">Email does not exist.</span>';
header('Location: login.php?');
exit;
}
} else {
// SQL statement preparation failed
$_SESSION['error_message'] = '<span style="color: red; font-weight: bold;">System Offline.</span>';

header('Location: login.php');
exit;
}






}

?>


<div class="oder1">
<div class="row">
    <div class="column" style="background-color:#f5f6fa;">
        <!-- Content for the first column -->
             <h2>Login</h2>
                      <?php
                        if (isset($_SESSION['error_message'])) {
                            echo '<p>' . $_SESSION['error_message'] . '</p>';
                            unset($_SESSION['error_message']);
                        }
                        ?>
          
               <div class="oder">
                    <form action="login.php" method="post">

                    <div class="container">

                   
                        <label for="uname"><b>Email</b></label> <br>
                        <input type="text" placeholder="Enter Email" id="email" name="email"  required><br>

                       
                        <label for="psw"><b>Password</b></label><br>
                        <input type="password" placeholder="Enter Password" id="password" name="password" required><br>
                           
            


                        <button type="submit" class="loginbtn" name="login">Login</button> <br>
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
</div>

</body>
</html>
