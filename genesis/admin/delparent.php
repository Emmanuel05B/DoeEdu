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

    .example-modal .modal {
        background: transparent !important;
    }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <!-- Main content -->
    <section class="content">

    <?php
    include('../partials/connect.php');

    $learnerId = $_GET['learnerId']; // Learner ID passed from form or URL

    // Prepare statement to fetch ParentId
    $sql = "SELECT ParentId FROM parentlearner WHERE LearnerId = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $learnerId);
    $stmt->execute();
    $results = $stmt->get_result();
    $final = $results->fetch_assoc();  

    // Fetch parent info
    $parentId = $final['ParentId'];

    // Prepare statement to fetch parent info
    $sql1 = "SELECT * FROM users WHERE Id = ?";
    $stmt1 = $connect->prepare($sql1);
    $stmt1->bind_param("i", $parentId);
    $stmt1->execute();
    $results1 = $stmt1->get_result();
    $final1 = $results1->fetch_assoc(); // Fetch parent info

    $name = $final1['Name'];
    ?>

        <div class="modal modal-danger fade" id="modal-danger">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Are you sure You want to delete <?php echo $name; ?>'s parent</h4><br>
                <h3 class="modal-title">Parent Details </h3>

              </div>

              <div class="modal-body">
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th>Field</th><th>Details</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>Name</td> <td><?php echo $name ?></td> 
                          </tr>
                          <tr>
                              <td>Surname</td> <td><?php echo $final1['Surname']; ?></td>
                          </tr>
                          <tr>
                              <td>Gender</td><td>Male</td>
                          </tr>
                          <tr>
                              <td>Contact Number</td> <td>+1234567890</td> 
                          </tr>
                          <tr>
                              <td>Email</td><td>johndoe@example.com</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
              <div class="modal-footer">
                  <a href="deleteparenthandler.php?learnerId=<?php echo $learnerId ?>" class="btn btn-outline pull-left">Yes, Delete</a>
                  <a href="deleteparent.php" class="btn btn-outline ml-auto">Back</a> <!-- Added ml-auto class here -->
              </div>
            </div>
          </div>
        </div>
        <!-- /.modal end here -->

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
      $('#modal-danger').modal('show'); // Show the modal immediately on page load

      // Close the modal and redirect when clicking on the backdrop
      $('.modal').on('click', function (e) {
          // Check if the click target is the modal backdrop
          if ($(e.target).is('.modal')) {
              window.location.href = 'deleteparent.php'; // Redirect to deleteparent.php
          }
      });
  });
</script>
</body>
</html>
