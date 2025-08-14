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

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content --->
  <div class="content-wrapper">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
        <h1>Video Lessons <small>xxxx x x x</small></h1>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
      </section>
    <!-- Main content table--------------------------------------------->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">

              <table id="videosTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Chapter</th>
                    <th>Part</th>
                    <th>Upload Date</th>
                    <th style="width: 80px;">Watch</th>
                    <th style="width: 100px;">Download</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Solving Linear Equations</td>
                    <td>Equations</td>
                    <td>Part 1</td>
                    <td>2024-06-15</td>
                    <td><a href="https://www.youtube.com/watch?v=video1_id" target="_blank" class="btn btn-sm btn-primary btn-block">Watch</a></td>
                    <td><a href="../resources/videos/solving_linear_eq_part1.mp4" download class="btn btn-sm btn-default btn-block">Download</a></td>
                  </tr>
                  <tr>
                    <td>Solving Linear Equations</td>
                    <td>Equations</td>
                    <td>Part 2</td>
                    <td>2024-06-16</td>
                    <td><a href="https://www.youtube.com/watch?v=video1_part2_id" target="_blank" class="btn btn-sm btn-primary btn-block">Watch</a></td>
                    <td><a href="../resources/videos/solving_linear_eq_part2.mp4" download class="btn btn-sm btn-default btn-block">Download</a></td>
                  </tr>
                  <tr>
                    <td>Intro to Trigonometry</td>
                    <td>Trigonometry Basics</td>
                    <td>Full</td>
                    <td>2024-06-12</td>
                    <td><a href="https://www.youtube.com/watch?v=video2_id" target="_blank" class="btn btn-sm btn-info btn-block">Watch</a></td>
                    <td><a href="../resources/videos/intro_trigonometry.mp4" download class="btn btn-sm btn-default btn-block">Download</a></td>
                  </tr>
                  <tr>
                    <td>Working with Graphs</td>
                    <td>Graphs</td>
                    <td>Full</td>
                    <td>2024-06-10</td>
                    <td><a href="https://www.youtube.com/watch?v=video3_id" target="_blank" class="btn btn-sm btn-success btn-block">Watch</a></td>
                    <td><a href="../resources/videos/working_graphs.mp4" download class="btn btn-sm btn-default btn-block">Download</a></td>
                  </tr>
                  <tr>
                    <td>Factorisation Basics</td>
                    <td>Algebra</td>
                    <td>Full</td>
                    <td>2024-06-08</td>
                    <td><a href="https://www.youtube.com/watch?v=video4_id" target="_blank" class="btn btn-sm btn-warning btn-block">Watch</a></td>
                    <td><a href="../resources/videos/factorisation_basics.mp4" download class="btn btn-sm btn-default btn-block">Download</a></td>
                  </tr>
                </tbody>
              </table>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
  $(function () {
    $('#videosTable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "pageLength": 10
    });
  })
</script>

</body>
</html>
