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

table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #DDD;
            font-size: 14px; 
        }
        .pos {
            margin-bottom: 100px;
            margin-right: 100px;
            margin-left: 100px;
            background-color: white;
        }
        tr:hover {
            background-color: #D6EEEE;
        }
        .button {
            display: inline-block;
            padding: 4px 8px;
            background-color: #007bff; /* Blue color */
            color: #fff; /* White text color */
            text-decoration: none; /* Remove underline */
            border-radius: 4px; /* Rounded corners */
            transition: background-color 0.3s ease; /* Smooth transition */
        }
        .button:hover {
            background-color: #0056b3; /* Darker bluxe color on hover */
        }

.content {  /* for the white thingy */
  background-color: white;
  
  margin-top: 20px;
  margin-left: 80px;
  margin-right: 80px;
}

.centr {
    text-align: center;     
}


</style>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;
  <!-- Left side column. contains the logo and sidebar -->
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
  
  <section class="content">
    <div class="container-fluid">
          
    <section class="ftco-section ftco-cart">

      <?php
        include('../partials/connect.php');

        $id = $_GET['id'];  //for grade

        $sql = "SELECT * FROM grade WHERE GradeId = $id" ;
        $results = $connect->query($sql);
        $final = $results->fetch_assoc();           
      
        ?>

            <div class="centr">
                <h2>Grade <?php echo $final['GradeId'] ?> Learners</h2>
                <p>Enter Name or ID to search.</p>
                <form action="/searchpage.php">
                    <input type="text" placeholder="Search.." name="search" size="30">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>

            </div>
                <h2></h2>
                <div style="overflow-x:auto;">
                    <table id="myTable">
                        <tr>
                            <th>No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Grade</th>
                            <th>More</th>
                        </tr>
                        <?php
                        include('../partials/connect.php');
                        $sql = "SELECT COUNT(*) as count FROM learner WHERE GradeId = $id ";
                        $result = $connect->query($sql);
                        if ($result) {
                            $row = $result->fetch_assoc();
                            echo "Total Records: " . $row['count'];
                        } else {
                            echo "Error: " . $mysqli->error;
                        }
                        
                        $sql = "SELECT * FROM learner WHERE GradeId = $id";
                        $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                            <tbody>
                            <h2> </h2>
                            
                                <tr class="text-center">
                                
                                    <td><?php echo $final['LearnerId'] ?></td>
                                    <td><?php echo $final['Name'] ?></td>
                                    <td><?php echo $final['Surname'] ?></td>
                                    <td><?php echo $final['DateOfBirth'] ?></td>
                                    <td><?php echo $final['Gender'] ?></td>
                                    <td><?php echo $final['GradeId'] ?></td>
                                    <td><p><a class="button" href="learnerprofile.php?id=<?php echo $final['LearnerId'] ?>">Open Profile</a></p></td>
                                </tr>
                            </tbody>
                        <?php } ?>
                    </table>
                </div>
    </div>
</section>
</div> <!-- /. ##start -->
      

  <div class="control-sidebar-bg"></div>
</div>

<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>
</body>
</html>

