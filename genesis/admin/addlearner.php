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

<style>

   
.registerbtn {
  background-color: #2d98da;
  color: white;
  padding: 15px 15px;
  margin: 2px;
  align: center;
  border: none;
  cursor: pointer;
  width: 100%;
  height: 50px;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}
.content {  /* for the white thingy */
  background-color: white;
  
  margin-top: 20px;
  margin-left: 80px;
  margin-right: 80px;
}
.pos {
  margin-bottom: 30px;
  text-align: center; 
}
</style>


<?php   


  include('../partials/connect.php');
  
  if(isset($_POST['reg'])){
     
     $name = trim($_POST['name']);
     $surname = trim($_POST['surname']);
    
     $gender = $_POST['gender'];
     $dateofbirth = $_POST['dob'];
 
     $gradeid = $_POST['gradeid'];
     $functionallevel = $_POST['functionallevel'];


        $stmt = $connect->prepare("INSERT INTO learner(Name, Surname, Gender, GradeId ,DateOfBirth, FunctionalLevel) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("sssiss",$name, $surname, $gender, $gradeid, $dateofbirth, $functionallevel);

        if($stmt->execute()){

        $_SESSION['error_message'] = '<span style="color: green; font-weight: bold;">Succesfully Registered The Learner!.</span>';
        header('Location: addlearner.php');
        exit;
   
       }else{
          $_SESSION['error_message'] = '<span style="color: red; font-weight: bold;">Unsuccesfully Registration!.</span>';
          header('Location: addlearner.php');
          exit;
        
        }
        $stmt->close();
        $connect->close();
   
  }else{
    
  }
    
   
?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
    <section class="content">   <!-- start -->

    <form action="addlearner.php" method="POST">

  <div class="pos">
    <h4>Registering</h4>
    <h4>Learner</h4>
    <h2> <?php
      if (isset($_SESSION['error_message'])) {
            echo '<p>' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
            }
      ?>
      </h2>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="name">First Names</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Enter Names" required>
    </div>
    <div class="form-group col-md-6">
      <label for="surname">Last Name</label>
      <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Surname" required>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
    <label for="name">Gender </label>
    <select id="gender" name="gender" class="form-control" required>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
    </select>
    </div>
    <div class="form-group col-md-6">
      <label for="dob">Date of Birth</label>
      <input type="date" class="form-control" id="dob" name="dob" required>
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="name">GradeId</label>
      <select type="text" id="gradeid" name="gradeid" class="form-control" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
      </select><br>
    </div>
    <div class="form-group col-md-6">
      <label for="name">Functional Level</label>
      <select id="functionallevel" name="functionallevel" class="form-control" >
        <option value="ASD Level 1">ASD Level 1(Requiring Support)</option>
        <option value="ASD Level 2">ASD Level 2(Requiring Substantial Support)</option>
        <option value="ASD Level 3">ASD Level 3(Requiring Very Substantial Support)</option>
      </select><br>
    </div>
            
  </div>

  <button type="submit" class="registerbtn" name="reg">Register Learner</button>
</form>

    </section> <!-- end -->
  </div>
</div>


<?php include("adminpartials/queries.php") ?>;

<script src="dist/js/demo.js"></script>
</body>
</html>

