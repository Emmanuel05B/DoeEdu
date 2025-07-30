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

    <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <div class="col-md-6">
            <!-- Activity Averages Bar Chart -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Activity Averages</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="newPieChart" style="height: 250px"></canvas>
              </div>
            </div>

            <!-- Activity Engagement Line Chart -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Activity Engagement Rates</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="pieChart" style="height: 250px"></canvas>
              </div>
            </div>

            <div class="text-center" style="margin-top: 10px">
              <a href="xx.php" class="btn btn-primary">Learners Analysis</a>
            </div>
          </div>

          <div class="col-md-6">
            <!-- Class Overall Scores Bar Chart -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Class Overall Scores Performances</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="newBarChart" style="height: 250px"></canvas>
              </div>
            </div>

            <!-- Class Attendance & Submission Rates Bar Chart -->
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Class Attendances and Submissions Rates</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="box-body">
                <canvas id="barChart" style="height: 230px"></canvas>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="control-sidebar-bg"></div>
  </div>

  <!-- jQuery 3 -->
  <?php include(__DIR__ . "/../../common/partials/queries.php"); ?>


  <script>
    $(function () {
      // Activity Averages Bar Chart (#newPieChart)
      var ctx1 = $("#newPieChart")[0].getContext("2d");
      var newPieChart = new Chart(ctx1, {
        type: "bar",
        data: {
          labels: [
            "Maths 10",
            "Maths 11",
            "Maths 12",
            "Physics 10",
            "Physics 11",
            "Physics 12",
          ],
          datasets: [
            {
              label: "Programme Subjects",
              backgroundColor: "rgba(60,141,188,0.7)",
              borderColor: "rgba(60,141,188,1)",
              borderWidth: 1,
              data: [70, 50, 100, 40, 60, 75],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          legend: {
            display: true,
            onClick: Chart.helpers.handleClick,
          },
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
          },
        },
      });

      // Activity Engagement Rates Line Chart (#pieChart)
      var ctx2 = $("#pieChart")[0].getContext("2d");
      var pieChart = new Chart(ctx2, {
        type: "line",
        data: {
          labels: [
            "Maths 10",
            "Maths 11",
            "Maths 12",
            "Physics 10",
            "Physics 11",
            "Physics 12",
          ],
          datasets: [
            {
              label: "mmmmmmmmmmmmmm",
              fill: true,
              backgroundColor: "rgba(60,141,188,0.2)",
              borderColor: "rgba(60,141,188,1)",
              pointBackgroundColor: "rgba(60,141,188,1)",
              pointBorderColor: "#fff",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(60,141,188,1)",
              data: [70, 50, 100, 40, 75, 85],
              lineTension: 0.3,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          legend: {
            display: true,
            onClick: Chart.helpers.handleClick,
          },
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
          },
        },
      });

      // Class Overall Scores Bar Chart (#newBarChart)
      var ctx3 = $("#newBarChart")[0].getContext("2d");
      var newBarChart = new Chart(ctx3, {
        type: "bar",
        data: {
          labels: [
            "Maths 10",
            "Maths 11",
            "Maths 12",
            "Physics 10",
            "Physics 11",
            "Physics 12",
          ],
          datasets: [
            {
              label: "Attendance",
              backgroundColor: "rgba(28, 230, 6, 0.7)",
              borderColor: "rgba(28, 230, 6, 1)",
              borderWidth: 1,
              data: [50, 75, 60, 50, 75, 60],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          legend: {
            display: true,
            onClick: Chart.helpers.handleClick,
          },
          scales: {
            yAxes: [
              {
                ticks: { beginAtZero: true },
              },
            ],
          },
        },
      });

      // Class Attendance and Submissions Rates Bar Chart (#barChart)
      var ctx4 = $("#barChart")[0].getContext("2d");
      var barChart = new Chart(ctx4, {
        type: "bar",
        data: {
          labels: [
            "Maths 10",
            "Maths 11",
            "Maths 12",
            "Physics 10",
            "Physics 11",
            "Physics 12",
          ],
          datasets: [
            {
              label: "Attendance Rate",
              backgroundColor: "rgba(243, 156, 18, 0.7)",
              borderColor: "rgba(243, 156, 18, 1)",
              borderWidth: 1,
              data: [28, 48, 40, 19, 59, 97],
            },
            {
              label: "Submission Rate",
              backgroundColor: "rgba(0, 192, 239, 0.7)",
              borderColor: "rgba(0, 192, 239, 1)",
              borderWidth: 1,
              data: [38, 28, 30, 29, 62, 80],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          legend: {
            display: true,
            onClick: Chart.helpers.handleClick,
          },
          scales: {
            yAxes: [
              {
                ticks: { beginAtZero: true },
              },
            ],
          },
        },
      });
    });
  </script>
</body>
</html>
