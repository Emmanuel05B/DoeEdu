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
            background-color: #0056b3; /* Darker blue color on hover */
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


            <div class="centr">
                <h2>Unopened Messages</h2>
            </div>
                <h2></h2>
                <div style="overflow-x:auto;">
                    <table id="myTable">
                        <tr>
                            <th>Sender Name</th>
                            <th>Sender Email</th>
                            <th>Subject</th>
                            <th>open</th>
                        </tr>
                        <?php
                        include('../partials/connect.php');


                        $sql = "SELECT COUNT(*) as count FROM pmessages";
                        $result = $connect->query($sql);
                        if ($result) {
                            $row = $result->fetch_assoc();
                            echo "Total Records: " . $row['count'];
                        } else {
                            echo "Error: " . $mysqli->error;
                        }

                  $sql = "SELECT * FROM pmessages ";  //comeback for condition
                  $results = $connect->query($sql);
                        while($final = $results->fetch_assoc()) { ?>
                            <tbody>
                            <h2> </h2>
                            
                                <tr class="text-center">
                                
                                    <td><?php echo $final['SenderName'] ?></td>
                                    <td><?php echo $final['Subject'] ?></td>
                                    <td><?php echo $final['Message'] ?></td>
                                    <td><p><a class="button" href="communications.php?id=<?php echo $final['No'] ?>">Open Message</a></p></td>
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

