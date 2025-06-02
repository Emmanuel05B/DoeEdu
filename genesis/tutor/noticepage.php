
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notices</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<style>
.dashline{
    border-top: 1px dashed #ddd;
}
.notice.read {
    background-color: blue; 
    text-decoration: line-through;
}
.close-notice {
    float: right;
    padding: 8px 16px;
    color: #fff;
    background-color: blue;
    border-radius: 2px;
    text-align: center;
    cursor: pointer;
}

  

</style>



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
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">

            <a href="adminindex.php" class="close" data-dismiss="modal" onclick="closeModal()">&times;</a>
            <h4 class="modal-title" style="text-align: center;"><?php echo $Teacherresultsfinal['Gender'] ?> <?php echo $Teacherresultsfinal['Surname'] ?></h4><br>

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

                  $sql = "SELECT * FROM notices WHERE IsOpened = 0 ORDER BY Date DESC";  //comeback for condition
                  $results = $connect->query($sql);
                  while($final = $results->fetch_assoc()) { ?>

                
            <div class="notice" data-id="2">
            <p><strong style="color: blue;">Date:</strong> <?php echo $final['Date'];?> <a href="readnotice.php?id=<?php echo $final['NoticeNo']; ?>" class="close-notice" onclick="markAsRead(this)">Mark Read</a></p>
            <p><strong style="color: blue;">Subject: </strong> <?php echo $final['Notice'];?><p>
            <p><?php echo $final['Reason'];?>.</p>   
            </div>
            <hr class="dashline">
            
            <?php } ?>

        </div>
  
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModalButton">Close</button>  -->
          <a href="adminindex.php" class="btn btn-default">Close</a>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<!-- JavaScript to trigger modal after login and redirect to home page after closing modal -->
<script>
$(document).ready(function() {
  $('#myModal').modal('show');

  // Redirect to home page after modal is closed
  $('#myModal').on('hidden.bs.modal', function () {
    window.location.href = 'adminindex.php';
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