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
 <?php include("adminpartials/mainsidebar.php") ?>;
  <!-- Content Wrapper. Contains page content ##start -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->


        <!-- ./col 555555555555555555555-->
  <section class="content">
  <body>

    <!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<?php include("adminpartials/head.php"); ?>

<!------------------------------------starting here------------------------------->
<?php
include('../partials/connect.php');
           
$learner_id = intval($_GET['id']);  //for leaner  //intval to ensure that it is an integer

//     include('shared.php');

?>

<!------------------------------------ending here------------------------------->




<!-----------------------------start for the line graph here--------------------->
<?php
include('../partials/connect.php');

$learner_id = intval($_GET['id']);  // Ensure it's an integer

// Initialize arrays to store averages

$monthlyData = [];
$yearlyData = [];

// Calculate daily averages////////////////////////////////////////////////////////////////////////
$dayCounter = 1;
$daySql = "SELECT DATE(ReportDate) as reportDay, 
                  ((SUM(EngagementLevel) + SUM(IndependanceLevel)) / 80) * 100 as dailyAverage 
           FROM scores 
           WHERE LearnerId = ? 
           GROUP BY reportDay 
           ORDER BY reportDay ASC";

$stmt = $connect->prepare($daySql);
$stmt->bind_param("i", $learner_id);
$stmt->execute();
$dayResults = $stmt->get_result();

// Fetch all daily averages for the learner
while ($row = $dayResults->fetch_assoc()) {
    $weeklyData['Day ' . $dayCounter] = $row['dailyAverage']; // Storing average as "Day 1", "Day 2"
    $dayCounter++; 
}


// Calculate monthly averages//////////////////////////////////////////////////////////////////////////////

$monthlySum = 0;
$monthCount = 0;

foreach ($weeklyData as $index => $average) {
    $monthlySum += $average;
    $monthCount++;

    // Every 4 weeks, calculate the monthly average
    if ($monthCount == 4) {
        $monthlyData[] = $monthlySum / 4;  // Calculate the monthly average
        $monthlySum = 0;                        // Reset for the next month
        $monthCount = 0;                        // Reset month counter
    }
}
if ($monthCount > 0) {
    $monthlyData[] = $monthlySum / $monthCount;  // Average for the last partial month
}



// Pass the data to JavaScript
$chartData = [
    
    'weeks' => $weeklyData,
    'months' => $monthlyData,
];

echo "<script>
        var chartData = " . json_encode($chartData) . ";
    </script>";
?>



<!------------------------------------ending here------------------------------->


<style>
    /* Container for the label and select */
    .time-frame-container {
    display: flex;
    gap: 10px; 
    }

    /* the select element */
    #timeFrame {
    font-size: 14px; 
    cursor: pointer; 
    width: 150px; 
    }

    .chart-container {
        position: relative;
        width: 80%; /* Adjust width as needed */
        height: 265px; /* Adjust height as needed */
    }

    #pie_chart {
        width: 100% !important; /* Full width */
        height: 255px !important; /* Full height */
    }

  
    
</style>

<body>

    <div id="main-wrapper">
    
    
        <div class="content-body">
            <div class="container-fluid">
            
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Mathematics/Physical sciences</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="barChart_1"></canvas>
                                    </div>
                                </div>
                            </div>

                         

                            <div class="col-lg-6 col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Activity Averages</h4>
                                    </div>
                                    
                                    <div class="chart-container">
                                        <canvas id="pie_chart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div><br>

            

                <div class="row">
                
                    <div class="row-lg-6 row-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="text-align: center;">Overall Perfomance</h4>

                                <div class="time-frame-container">
                                <label for="timeFrame" class="time-frame-label">Select Time Frame:</label>
                                <select id="timeFrame" class="form-select">
                                    <option value="weeks">Weeks</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>

                            </div>
                            <div class="card-body">
                                <canvas id="lineChart_1" style="height: 300px;"></canvas>

                            </div>
                        </div>
                    </div>

                </div><br>

                <div class="row">
                
               

              
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
        // Basic Bar Chart
        var ctxBar = document.getElementById("barChart_1").getContext('2d');
        var barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Outdoor Play', 'Story Time', 'Sensory Intergration', 'Social Skills'],
            
                datasets: [{
        label: 'Engagement Avarage',    
        //my possible properties  // border width t control the thickness of the line chart
        borderWidth: 1,
        borderColor: 'gray',
        hoverBorderWidth:3,
        hoverBorderColor:'black',
        backgroundColor: 'rgba(67, 222, 24, 0.73)',
        data: [<?php echo $OutdoorPlayEngagementAverage ?>, <?php echo $StoryTimeEngagementAverage ?>, <?php echo $SensoryIntegrationEngagementAverage ?>, <?php echo $LifeSkillsEngagementAverage ?>]

        
      },
      {
        label: 'Independace Avarage',    
        //my possible properties
        borderWidth: 1,
        borderColor: 'gray',
        hoverBorderWidth:3,
        hoverBorderColor:'black',
        backgroundColor: 'rgba(54,162,235,0.6)',
        data: [<?php echo $OutdoorPlayIndependenceAverage ?>, <?php echo $StoryTimeIndependenceAverage ?>, <?php echo $SensoryIntegrationIndependenceAverage ?>, <?php echo $LifeSkillsIndependenceAverage ?>]
        
        
      }
     
    ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        // Basic Line Chart
        const ctxLine = document.getElementById('lineChart_1').getContext('2d');
        let lineChart;

        const data = {
            days: {
                labels: [
                 ],
                datasets: [{
                    label: 'Overall Perfomance by Days',
                    data: chartData.days,
                    borderColor: 'purple',
                    borderWidth: 2,
                    fill: false,
                    tension: 0,
                }]
            },
            weeks: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 
                        'Week 8', 'Week 9', 'Week 10', 'Week 11', 'Week 12', 'Week 13', 
                        'Week 14', 'Week 15', 'Week 16', 'Week 17', 'Week 18', 'Week 19', 
                        'Week 20'],
                datasets: [{
                    label: 'Overall Perfomance by Weeks',
                    data: chartData.weeks,
                    borderColor: 'blue',
                    borderWidth: 2,
                    fill: false,
                    tension: 0,
                }]
            },
            months: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November'],

                datasets: [{
                    label: 'Overall Perfomance by Months',
                    data: chartData.months,
                    borderColor: 'green',
                    borderWidth: 2,
                    fill: false,
                    tension: 0,
                }]
            },
            years: {
                labels: ['2024', '2025', '2026'],
                datasets: [{
                    label: 'Overall Perfomance by Years',
                    data: chartData.years,
                    borderColor: 'red',
                    borderWidth: 2,
                    fill: false,
                    tension: 0,
                }]
            }
        };
/*
        // Dual Line Chart 
        var ctxDual = document.getElementById("lineChart_3").getContext('2d');
        var dualLineChart = new Chart(ctxDual, {
            type: 'line',
            data: {
                labels: ['Weeek 1', 'Weeek 2', 'Weeek 3', 'Weeek 4','Weeek 5', 'Weeek 6', 'Weeek 7', 'Weeek 8','Weeek 9', 'Weeek 10', 'Weeek 11', 'Weeek 12'],
                datasets: [{
                    label: 'Engagement Avarage',    
                    data: [50, 77, 24, 41,50, 77, 24, 41,50, 77, 24, 41],
                    borderColor: 'green',
                    borderWidth: 2,
                    fill: false 
                }, {
                    label: 'Independace Avarage',    
                    data: [20, 47, 85, 54,20, 47, 85, 54,20, 47, 85, 54],
                    borderColor: 'blue',
                    borderWidth: 2,
                    fill: false  
                }]
            },
            options: {
                maintainAspectRatio: false,

                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });  */

        // Pie Chart
        var ctxPie = document.getElementById("pie_chart").getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ["Social Skills", "Outdoor Play", "Story Time", "Sensory Intergration"],
                datasets: [{
                    label: 'Sales by Color',

                    data: [<?php echo 50; ?>, <?php echo 40; ?>, <?php echo 54; ?>, <?php echo 75; ?>],
                    backgroundColor: [
                        'yellow',
                        'green',
                        'blue',
                        'black)'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });




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
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        document.getElementById('timeFrame').addEventListener('change', function () {
            updateChart(this.value);
        });

        // Initialize with 'days' data
        updateChart('days');

    </script>


<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>



</body>

</html>


  </section>



</div> <!-- /. ##start -->
      
  <div class="control-sidebar-bg"></div>
</div>


</body>
</html>

