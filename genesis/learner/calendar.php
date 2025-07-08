<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>
<?php include("learnerpartials/head.php"); ?>

<!-- FullCalendar CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css"/>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("learnerpartials/header.php"); ?>
  <?php include("learnerpartials/mainsidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>📅 My Calendar</h1>
      <small>Track your tutoring sessions, homework deadlines, and important events.</small>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Schedule Overview</h3>
        </div>
        <div class="box-body">
          <div id="calendar"></div>
        </div>
      </div>
    </section>
  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

<script>
  $(function () {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next, today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      defaultView: 'month',
      editable: false,
      events: [
        {
          title: 'Tutoring: Maths',
          start: '2025-06-21T15:00:00',
          color: '#3c8dbc' // blue
        },
        {
          title: 'Homework: Physical Science',
          start: '2025-06-22',
          color: '#f39c12' // yellow
        },
        {
          title: 'Revision Workshop',
          start: '2025-06-24',
          color: '#00a65a' // green
        }
      ]
    });
  });
</script>
</body>
</html>
