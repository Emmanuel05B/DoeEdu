<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
?>

<?php
include('../partials/connect.php');
?>

<?php include("adminpartials/head.php"); ?>
     
<style>
    .example-modal .modal {
        position: relative;
        top: auto;
        bottom: auto;
        right: auto;
        left: auto;
        display: block;
        z-index: 1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid gray;
        text-align: center;
        padding: 8px;
    }

    th {
        background-color:rgb(242, 242, 242);
    }

    .modal-body {
        padding: 20px;
    }

    textarea {
        width: 100%;
        height: 60px;
        font-size: 14px;
        line-height: 18px;
        border: 1px solid #dddddd;
        padding: 10px;
    }

    .btn-submit {
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-submit:hover {
        background-color: #45a049;
    }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Content Wrapper. Contains page content. -->
    <div class="content-wrapper">
    
        <!-- Main content -->
        <section class="content">
        
        

            <?php
 include('../partials/connect.php');
$userId = $_SESSION['user_id'];  //for looged in teacher

$sql = "SELECT Surname FROM users WHERE Id =  $userId";

$usql = "SELECT * FROM users WHERE Id = $userId" ;
$Teacherresults = $connect->query($usql);
$Teacherresultsfinal = $Teacherresults->fetch_assoc();  

?>

<div class="container">
  
  <!-- Modal -->
  <div class="modal fade" id="modal-default">

    <div class="modal-dialog">

    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">

            <a href="teacherindex.php" class="close" data-dismiss="modal" onclick="closeModal()">&times;</a>
            <h4 class="modal-title" style="text-align: center;">WELCOME BACK</h4><br>
            <h4 class="modal-title" style="text-align: center;">Mr <?php echo $Teacherresultsfinal['Surname'] ?></h4><br>

            <h3 class="modal-title" style="color: blue;">Notification Centre</h3><br>
            <?php
                        if (isset($_SESSION['succes'])) {
                            echo '<p>' . $_SESSION['succes'] . '</p>';
                            unset($_SESSION['succes']);
                        }
                        ?>
            </div>
            <div class="modal-body">

            
            <?php

                  $sql = "SELECT * FROM parentreply WHERE IsOpened = 0";  //comeback for condition
                  $results = $connect->query($sql);
                  while($final = $results->fetch_assoc()) { ?>

                
            <div class="notice" data-id="2">
            <p><strong style="color: blue;">Date:</strong> <?php echo $final['CreatedAt'];?> <a href="readmessage.php?id=<?php echo $final['No']; ?>" class="close-notice" onclick="markAsRead(this)">Mark Read</a></p>
            <p><strong style="color: blue;">Subject: </strong> <?php echo $final['Subject'];?><p>
            <p><?php echo $final['Message'];?>.</p>   
            </div>
            <hr class="dashline">
            
            <?php } ?>

        </div>
  
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModalButton">Close</button>  -->
          <a href="teacherindex.php" class="btn btn-default">Close</a>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

        </section>
        <!-- /.content -->
    </div>
  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
  $(document).ready(function() {
      $('#modal-default').modal('show'); // Show the modal immediately on page load

      // Close the modal and redirect when clicking on the backdrop
      $('.modal').on('click', function (e) {
          if ($(e.target).is('.modal')) {
              window.location.href = 'adminindex.php'; 
          }
      });
  });

$(document).ready(function() {
  $('#myModal').modal('show');

  // Redirect to home page after modal is closed
  $('#myModal').on('hidden.bs.modal', function () {
    window.location.href = 'teacherindex.php';
  });
});

function closeModal() {
    $('#myModal').modal('hide');
  }

  function markAsRead(element) {
    const notice = element.closest('.notice');
    notice.classList.add('read'); 
  }

</script>

</body>
</html>
