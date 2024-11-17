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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

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

<!-- handler code here -->

<!-- HTML CODE DOWN HERE -->



<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->

 
  <div class="content-wrapper">

    <section class="content">   <!-- start -->


      
<?php
        include('../partials/connect.php');

        $id = $_GET['id'];  //for learner

        $sql = "SELECT * FROM learner WHERE LearnerId = $id" ;
        $results = $connect->query($sql);
        $final = $results->fetch_assoc();           
      
        ?>


 <form action="addparenthandler.php" method="POST">

  <div class="pos">
    
    <h4>Registering</h4>
    <h2><?php echo $final['Name'] ?> <?php echo $final['Surname'] ?></h2>
    <h4>Parent</h4>

  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">First Names</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Names" required>
    </div>
    <div class="form-group col-md-6">
      <label for="surname">Last Name</label>
      <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required>
    </div>
  </div>


  <div class="form-row">
    <div class="form-row">
        
           <div class="form-group col-md-6">
                  <div class="form-group col-md-6">
                    <label for="id">ID Number (13 digits):</label><br>
                    <input type="text" class="form-control" id="id" name="id" pattern="[0-9]{13}" maxlength="13" required>
                   </div>
                   <div class="form-group col-md-6">
                      <label for="name">Title </label>
                      <select id="gender" name="gender" class="form-control" >
                        <option value="Mr">Mr</option>
                        <option value="Ms">Ms</option>
                        <option value="Mrs">Mrs</option>
                      </select>
                   </div>
            </div>
    </div>
  </div>

  
  <div class="form-row">
    <div class="form-row">
        
           <div class="form-group col-md-6">
                  <div class="form-group col-md-6">

                  <label for="contactnumber">Contact Number (10 digits):</label><br>
                  <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>

                   </div>
                   <div class="form-group col-md-6">
                    
                  <label for="secondcontactnumber">Contact Number (10 digits):</label><br>
                  <input type="tel" class="form-control" id="secondcontactnumber" name="secondcontactnumber" pattern="[0-9]{10}" maxlength="10" >

                  </select>
                   </div>
            </div>
    </div>
  </div>


  <div class="form-row">
    <div class="form-group col-md-6">
      
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
    </div>
    <div class="form-group col-md-6">
    <label for="address">Address</label>
    <input type="text" class="form-control" id="address" name="address" placeholder="Enter address">
    </div>
  </div>
  
  <div class="form-row">
    
  </div>
  <div class="form-row">

  </div>

    <input type="hidden" id="password" name="password" value="12345">
    <input type="hidden" id="urlParams" name="learnerFakeid" value="<?php echo htmlspecialchars($final['LearnerId']) ?>">   <!-- you can use $_GET['id']; straight!! -->  
          
    
    <button type="submit" class="registerbtn" name="reg">Register The Parent</button>


</form>

    </section> <!-- end -->
  </div>
</div>




<?php include("adminpartials/queries.php") ?>;

<script src="dist/js/demo.js"></script>



</body>
</html>

