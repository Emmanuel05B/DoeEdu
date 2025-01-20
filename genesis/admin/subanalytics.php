<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Left side column. contains the logo and sidebar -->
  <?php include("adminpartials/mainsidebar.php") ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <body>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            </head>

            <?php include("adminpartials/head.php"); ?>

            <!-- PHP Data Fetching -->
            <?php
            include('../partials/connect.php');

            $learner_id = intval($_GET['id']);  // Ensure it's an integer

            // Initialize arrays to store percentages for each week and month
            $weeklyData = [];
            $monthlyData = [];
            $weeklyLabels = [];

            // Fetch data for weekly marks (as percentages)
            $weekCounter = 1;
            $weekSql = "
                SELECT 
                    lam.ActivityId, 
                    lam.MarksObtained, 
                    a.MaxMarks,
                    lam.DateAssigned 
                FROM learneractivitymarks lam
                JOIN activities a ON lam.ActivityId = a.ActivityId
                WHERE lam.LearnerId = ? 
                ORDER BY lam.DateAssigned ASC
            ";

            $stmt = $connect->prepare($weekSql);
            $stmt->bind_param("i", $learner_id);
            $stmt->execute();
            $weekResults = $stmt->get_result();

            // Fetch all weekly marks for the learner and calculate percentage
            while ($row = $weekResults->fetch_assoc()) {
                $percentage = ($row['MarksObtained'] / $row['MaxMarks']) * 100;
                $weeklyData[] = $percentage;  // Store Percentage for the week
                $weeklyLabels[] = "Week " . $weekCounter;  // Label for the week
                $weekCounter++;
            }

            // Fetch data for monthly marks (as percentages)
            $monthlyLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November'];
            $monthlyData = [];

            // Split weekly data into months (4 weeks per month)
            foreach ($weeklyData as $index => $percentage) {
                $monthIndex = intdiv($index, 4);  // Calculate which month the data belongs to
                if (!isset($monthlyData[$monthIndex])) {
                    $monthlyData[$monthIndex] = [];
                }
                $monthlyData[$monthIndex][] = $percentage;
            }

            // Prepare the data for JavaScript
            $chartData = [
                'weeks' => [
                    'labels' => $weeklyLabels,
                    'data' => $weeklyData
                ],
                'months' => [
                    'labels' => $monthlyLabels,
                    'data' => array_map(function($month) { 
                        return array_sum($month) / count($month);  // Calculate average percentage for each month
                    }, $monthlyData) 
                ]
            ];

            echo "<script>
                    var chartData = " . json_encode($chartData) . ";
                </script>";
            ?>

            <!-- Chart Display -->
            <style>
                .time-frame-container {
                    display: flex;
                    gap: 10px;
                }

                #timeFrame {
                    font-size: 14px;
                    cursor: pointer;
                    width: 150px;
                }
            </style>

            <body>
                <div id="main-wrapper">
                    <div class="content-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="row-lg-6 row-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title" style="text-align: center;">Overall Performance</h4>
                                            <div class="time-frame-container">
                                                <label for="timeFrame" class="time-frame-label">Select Time Frame:</label>
                                                <select id="timeFrame" class="form-select">
                                                    <option value="weeks">Weeks</option>
                                                    <option value="months">Months</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="lineChart_1" style="height: 250px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div><br>

                            <div class="row">
                                <div class="row-lg-6 row-sm-6">
                                    <div class="card">
                                        
                                        <div class="card-body">
                                            <canvas id="bargraph" style="height: 250px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>

                <!-- Required vendors -->
                <script src="./vendor/global/global.min.js"></script>
                <script src="./js/quixnav-init.js"></script>
                <script src="./js/custom.min.js"></script>

                <!-- Chart.js plugin files -->
                <script src="./vendor/chart.js/Chart.bundle.min.js"></script>

                <!-- Chart.js Initialization -->
                <script>
                    // Line Chart Initialization
                    const ctxLine = document.getElementById('lineChart_1').getContext('2d');
                    let lineChart;

                    const data = {
                        weeks: {
                            labels: chartData.weeks.labels,
                            datasets: [{
                                label: 'Performance by Weeks (Percentage)',
                                data: chartData.weeks.data,
                                borderColor: 'purple',
                                borderWidth: 2,
                                fill: false,
                                tension: 0,
                            }]
                        },
                        months: {
                            labels: chartData.months.labels,
                            datasets: [{
                                label: 'Performance by Months (Percentage)',
                                data: chartData.months.data,
                                borderColor: 'green',
                                borderWidth: 2,
                                fill: false,
                                tension: 0,
                            }]
                        }
                    };

                    function updateChart(timeFrame) {
                        if (lineChart) {
                            lineChart.destroy();
                        }
                        lineChart = new Chart(ctxLine, {
                            type: 'line',
                            data: data[timeFrame],
                            options: {
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100  // Ensure the percentage scale goes up to 100
                                    }
                                }
                            }
                        });
                    }

                    document.getElementById('timeFrame').addEventListener('change', function () {
                        updateChart(this.value);
                    });

                    // Initialize with 'weeks' data
                    updateChart('weeks');

                    // Bar Chart Initialization
                    const ctxBar = document.getElementById('bargraph').getContext('2d');
                    let barChart;

                    function updateBarChart(timeFrame) {
                        if (barChart) {
                            barChart.destroy();
                        }
                        barChart = new Chart(ctxBar, {
                            type: 'bar',
                            data: data[timeFrame],
                            options: {
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100  // Ensure the percentage scale goes up to 100
                                    }
                                }
                            }
                        });
                    }

                    document.getElementById('timeFrame').addEventListener('change', function () {
                        updateBarChart(this.value);
                    });

                    // Initialize with 'weeks' data for bar chart
                    updateBarChart('weeks');
                </script>

                <?php include("adminpartials/queries.php"); ?>
                <script src="dist/js/demo.js"></script>
            </body>
        </html>
    </section>
  </div>
</div>

</body>
</html>
