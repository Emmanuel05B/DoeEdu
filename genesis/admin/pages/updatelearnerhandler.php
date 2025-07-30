<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
        
        <!-- ./here -->
     
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: black;
    }

    * {
      box-sizing: border-box;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
      width: 100%;
      padding: 10px;
      margin: 5px 0 5px 0;
      display: inline-block;
      border: none;
      background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
      background-color: #ddd;
      outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
      border: 1px solid #f1f1f1;
      margin-bottom: 25px;
    }

    /* Set a style for the submit button */
    .registerbtn {
      background-color: #2d98da;
      color: white;
      padding: 20px 20px;
      margin: 2px;
      align: center;
      border: none;
      cursor: pointer;
      width: 75%;
      opacity: 0.9;
    }

    .registerbtn:hover {
      opacity: 1;
    }

    /* Add a blue text color to links */
    a {
      color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
      background-color: #f1f1f1;
      text-align: center;
    }


    .oder {

      margin-top: 20px;
      margin-bottom: 100px;
      margin-right: 100px;
      margin-left: 100px;
      background-color: white;
    }

</style>
</head>
<body>
<?php   

include(__DIR__ . "/../../partials/connect.php");

  if(isset($_POST['update']))
  {
    $new = $_POST['updatelearner'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $identitynumber = $_POST['identitynumber'];
    $gradeid = $_POST['gradeid'];
    $gender = $_POST['gender'];


    $sql = "UPDATE learner set Name='$name', Surname = '$surname', IdentityNo = '$identitynumber', GradeId = '$gradeid', Gender = '$gender' where Id = '$new'";
    if(mysqli_query($connect, $sql))
    {
        header('location: updatelearner.php');
    }else{
        header('location: adminindex.php');

    }
  }
    echo "dfgh;";

?>
<div class="oder">

<form action="updatelearnerhandler.php" method="POST">
  <div class="container">
    <h2>Register Learner</h2>
    <p>Please fill in this form to register an account.</p>
    <hr>

    
    <label for="name">First name:</label><br>
    <input type="text" placeholder="Enter Name" id="name" name="name"><br>

    <label for="name">Last name:</label><br>
    <input type="text" placeholder="Enter Surname" id="Suname" name="surname"><br>

    <label for="name">Gender :</label><br>
    <input type="text" placeholder="Enter Gender" id="gender" name="gender"><br>

    <label for="name"> </label><br>
    <label for="dateofbirth">Identity Number:</label><br>
    <input type="number" placeholder="Enter Identity Number" id="identitynumber" name="identitynumber"><br>

    <label for="name"> </label><br>
    <label for="name">GradeId:</label><br>
    <input type="text" placeholder="Enter Class/Grade" id="gradeid" name="gradeid"><br>

    <label for="name"> </label><br>
    <label for="like">Things I Like:</label><br>
    <textarea id="like" name="like" rows="4" cols="80"></textarea><br>

    <label for="dontlike">Things I Do Not Like:</label><br>
    <textarea id="dontlike" name="dontlike" rows="4" cols="80"></textarea><br>

    

    <label for="myfile">Select a Learner Picture:</label>
    <input type="file" id="myfile" name="myfile">
    

    <hr>

    <div class="registerbtn">
    <input type="submit" value="Update" name="reg" class="btn btn-primary py-3 px-5">
  </div>

    <button type="submit" class="registerbtn">Update The Learner</button>
  </div>


 

</form>

</div>

</body>
</html>

        <!-- ./to here -->

    </div>
      
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>
