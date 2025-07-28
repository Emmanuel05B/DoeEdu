<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
?>

<?php
include(__DIR__ . "/../../partials/connect.php");
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

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
        
            <!-- Modal -->
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Complete</h4><br>
                            <p><strong> Set up To Do List </strong></p>
                        </div>
                        
                        <div class="modal-body">
                            <form action="todohandler.php" method="post">

                                <table>
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Labels</th>
                                            <th colspan="5">Fill</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Task Name -->
                                        <tr>
                                            <td>Task Name</td>
                                            <td>
                                                <textarea class="textarea" name="task_name" placeholder="Enter task description here" required></textarea>
                                            </td>
                                        </tr>

                                        <!-- Hidden Fields to pass additional info (Grade, Subject, Chapter) -->
                                        <input type="hidden" name="graid" value="<?php echo 10; ?>">
                                        <input type="hidden" name="subid" value="<?php echo 2; ?>">
                                        <input type="hidden" name="chaid" value="<?php echo 5; ?>">

                                        <!-- Due Date -->
                                        <tr>
                                            <td>Date</td>
                                            <td><input type="date" name="due_date" required></td>
                                        </tr>

                                        <!-- Time -->
                                        <tr>
                                            <td>Time</td>
                                            <td><input type="time" name="due_time" required></td>
                                        </tr>

                                        <!-- Priority -->
                                        <tr>
                                            <td>Priority</td>
                                            <td>
                                                <select name="priority" required>
                                                    <option value="Low">Low</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="High">High</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <button type="submit" class="btn-submit" name="submit">Submit Task</button>
                            </form>
                        </div>
                        <div class="modal-footer">
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
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../common/dist/js/demo.js"></script> 

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
</script>

</body>
</html>
