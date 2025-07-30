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

  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
        
        <!-- ./col -->
    <section class="content">
    <div class="container-fluid">

                     <?php

                        include(__DIR__ . "/../../partials/connect.php");

                            $learnerId = $_GET['id'];

                          $sql =  "SELECT COUNT(finalreport.LearnerId) as count, learner.Name 
                            FROM finalreport 
                            JOIN learner ON finalreport.LearnerId = learner.LearnerId
                            WHERE finalreport.LearnerId = $learnerId";
                            // Execute the query
                            $result = $connect->query($sql);
                            $row = $result->fetch_assoc();
                            

                    ?>
  
  


    <section class="ftco-section ftco-cart">
            <div class="centr">
                <h2><?php echo $row['Name'] ?> Reports</h2>
                <p>Enter Name or ID to search.</p>
                <form action="/searchpage.php">
                    <input type="text" placeholder="Search.." name="search" size="30">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>

                <p><?php echo "Total Records: " . $row['count'];?></p>

            </div>

                <h2></h2>
                <div style="overflow-x:auto;">
                    <table id="myTable">
                        <tr>
                            <th>No.</th>
                            <th>Filed By</th>
                            <th>ArrivalTime</th>
                            <th>Arrival_Time_Reason</th>
                            <th>Separation_From_Parent</th>
                            <th>Classroom_Transition</th>
                            <th>More</th>

                        </tr>
                        <?php
                            
                            //I need data from the Users table and the Learner table as well as from the Report table.
                            
                            $sql = "SELECT * FROM finalreport WHERE LearnerId = $learnerId ";
                            $results = $connect->query($sql);
                            while($final = $results->fetch_assoc()) { ?>
                                <tbody>
                                    <tr class="text-center">
                                        <td><?php echo $final['ReportId'] ?></td>
                                        <td><?php echo $final['ReporterId'] ?></td>
                                        <td><?php echo $final['ArrivalTime'] ?></td>
                                        <td><?php echo $final['Reason'] ?></td>
                                        <td><?php echo $final['SeparationParent'] ?></td>
                                        <td><?php echo $final['ClassroomTransition'] ?></td>
                       
                                        <td><p><a class="button" href="../common/file.php?file_id=<?php echo $final['ReportId'] ?>&l_id=<?php echo $final['LearnerId'] ?>"class="btn btn-primary py-3 px-4">View_Report</a></p></td>
                                    </tr>
                                </tbody>
                        <?php } ?>    
                    </table>
                </div>
    </div>
</section>
        <!-- ./col -->


      </div>
      
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

</body>
</html>


