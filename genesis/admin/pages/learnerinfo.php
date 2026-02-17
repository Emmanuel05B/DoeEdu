<!DOCTYPE html>
<html>
<?php

require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  

/*
$requests = $connect->query("
    SELECT lt.LearnerId, lt.Grade, lt.ParentName, lt.ParentSurname, lt.ParentContactNumber, lt.ParentEmail,
           u.Name, u.Surname, u.Contact AS UserContact, u.Email AS UserEmail
    FROM learners lt
    JOIN users u ON lt.LearnerId = u.Id
");
*/

$realLearners = $connect->query("
    SELECT lt.LearnerId, lt.Grade, lt.ParentName, lt.ParentSurname, lt.ParentContactNumber, lt.ParentEmail,
           u.Name, u.Surname, u.Contact AS UserContact, u.Email AS UserEmail
    FROM learners lt
    JOIN users u ON lt.LearnerId = u.Id
    WHERE u.Name NOT LIKE 'z%'
    ORDER BY u.Surname ASC, u.Name ASC
");


$systemLearners = $connect->query("
    SELECT lt.LearnerId, lt.Grade, lt.ParentName, lt.ParentSurname, lt.ParentContactNumber, lt.ParentEmail,
           u.Name, u.Surname, u.Contact AS UserContact, u.Email AS UserEmail
    FROM learners lt
    JOIN users u ON lt.LearnerId = u.Id
    WHERE u.Name LIKE 'z%'
    ORDER BY u.Surname ASC, u.Name ASC
");




?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Learners Management <small>Manage all learners registered in the system</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Learners Information & Profiles</li>
      </ol>
    </section>
     
    <section class="content">
    
      <!-- Real Learners -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Real Learners</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="realLearners" class="table table-bordered table-hover">
              <thead style="background-color:#d1d9ff;">
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>P_Name</th>
                  <th>P_Surname</th>
                  <th>P_Contact</th>
                  <th>P_Email</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $realLearners = $connect->query("
                    SELECT lt.LearnerId, lt.Grade, lt.ParentName, lt.ParentSurname, lt.ParentContactNumber, lt.ParentEmail,
                           u.Name, u.Surname, u.Contact AS UserContact, u.Email AS UserEmail
                    FROM learners lt
                    JOIN users u ON lt.LearnerId = u.Id
                    WHERE u.Name NOT LIKE 'z%'
                    ORDER BY u.Surname ASC, u.Name ASC
                ");
                while ($req = $realLearners->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($req['Name']) ?></td>
                    <td><?= htmlspecialchars($req['Surname']) ?></td>
                    <td><?= htmlspecialchars($req['UserEmail']) ?></td>
                    <td><?= htmlspecialchars($req['UserContact']) ?></td>
                    <td><?= htmlspecialchars($req['ParentName']) ?></td>
                    <td><?= htmlspecialchars($req['ParentSurname']) ?></td>
                    <td><?= htmlspecialchars($req['ParentContactNumber']) ?></td>
                    <td><?= htmlspecialchars($req['ParentEmail']) ?></td>
                    <td class="text-center">
                      <a href="updatelearner.php?id=<?= $req['LearnerId'] ?>" class="btn btn-xs btn-warning">Update</a>
                      <a href="learnerprofile.php?id=<?= $req['LearnerId'] ?>" class="btn btn-xs btn-primary">Profile</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    
      <!-- System Learners -->
      <div class="box box-warning" style="margin-top:30px;">
        <div class="box-header with-border">
          <h3 class="box-title">System Learners</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="systemLearners" class="table table-bordered table-hover">
              <thead style="background-color:#ffe6cc;">
                <tr>
                  <th>Name</th>
                  <th>Surname</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>P_Name</th>
                  <th>P_Surname</th>
                  <th>P_Contact</th>
                  <th>P_Email</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $systemLearners = $connect->query("
                    SELECT lt.LearnerId, lt.Grade, lt.ParentName, lt.ParentSurname, lt.ParentContactNumber, lt.ParentEmail,
                           u.Name, u.Surname, u.Contact AS UserContact, u.Email AS UserEmail
                    FROM learners lt
                    JOIN users u ON lt.LearnerId = u.Id
                    WHERE u.Name LIKE 'z%'
                    ORDER BY u.Surname ASC, u.Name ASC
                ");
                while ($req = $systemLearners->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($req['Name']) ?></td>
                    <td><?= htmlspecialchars($req['Surname']) ?></td>
                    <td><?= htmlspecialchars($req['UserEmail']) ?></td>
                    <td><?= htmlspecialchars($req['UserContact']) ?></td>
                    <td><?= htmlspecialchars($req['ParentName']) ?></td>
                    <td><?= htmlspecialchars($req['ParentSurname']) ?></td>
                    <td><?= htmlspecialchars($req['ParentContactNumber']) ?></td>
                    <td><?= htmlspecialchars($req['ParentEmail']) ?></td>
                    <td class="text-center">
                      <a href="updatelearner.php?id=<?= $req['LearnerId'] ?>" class="btn btn-xs btn-warning">Update</a>
                      <a href="learnerprofile.php?id=<?= $req['LearnerId'] ?>" class="btn btn-xs btn-primary">Profile</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    
    </section>


  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- JS Libraries -->

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>
<script>
  $(function () {
    $('#realLearners').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });
    $('#systemLearners').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });
  });
</script>
<script>
  $(function () {

    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });

    // Delete with SweetAlert
    $('.swal-delete').on('click', function () {
      const id = $(this).data('id');
      const name = $(this).data('name');
      Swal.fire({
        title: 'Are you sure?',
        text: `Delete request from ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
       // cancelButtonText: 'give the cancel button a custom name',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `manage_inviterequests.php?delete_id=${id}`;
        }
      });
    });

    // Success alert (after redirect)
    // Alerts for emailsuperhandler
    <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Email Sent',
            text: '<?= addslashes($_SESSION['success']) ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Failed to Send',
            text: '<?= addslashes($_SESSION['error']) ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


  });
</script>


</body>
</html>
