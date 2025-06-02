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

// Initialize variables to store results
$resultsDetail = '';
$averageScoreSummary = '';

// Initializing  variables for each activity's averages
$OutdoorPlayEngagementAverage = 0;
$OutdoorPlayIndependenceAverage = 0;

$StoryTimeEngagementAverage = 0;
$StoryTimeIndependenceAverage = 0;

$SensoryIntegrationEngagementAverage = 0;
$SensoryIntegrationIndependenceAverage = 0;

$LifeSkillsEngagementAverage = 0;
$LifeSkillsIndependenceAverage = 0;

// Fetch distinct activities
$activitySql = "SELECT DISTINCT ActivityName FROM scores WHERE LearnerId = $learner_id";
$activityResults = $connect->query($activitySql);

if ($activityResults === false) {
    echo "Error: " . $connect->error;
    exit;
}

// Arrays to store totals and counts
$engagementTotals = [];
$independenceTotals = [];
$activityCounts = [];

// Initialize totals and counts for each activity
while ($activityRow = $activityResults->fetch_assoc()) {
    $activity = $activityRow['ActivityName'];
    
    $engagementTotals[$activity] = 0;
    $independenceTotals[$activity] = 0;
    $activityCounts[$activity] = 0;

    // Query to get scores for this activity
    $sql = "SELECT EngagementLevel, IndependanceLevel FROM scores WHERE LearnerId = $learner_id AND ActivityName = '$activity'";
    $fileResults = $connect->query($sql);

    if ($fileResults === false) {
        echo "Error: line 50" ;
        continue; // jump to the next activity if there's an error
    }

    // Fetch results and accumulate totals
    while ($results = $fileResults->fetch_assoc()) {
        $engagementTotals[$activity] += $results['EngagementLevel'];
        $independenceTotals[$activity] += $results['IndependanceLevel'];
        $activityCounts[$activity]++;
    }
}

// Calculate averages for each activity and assign to separate variables
foreach ($engagementTotals as $activity => $totalEngagement) {
    $count = $activityCounts[$activity];

    if ($count > 0) {
        $averageEngagement = ($totalEngagement / (10 * $count)) * 100;
        $averageIndependence = ($independenceTotals[$activity] / (10 * $count)) * 100;

        // Assign to the corresponding activity variables
        switch ($activity) {
            case 'OutdoorPlay':
                $OutdoorPlayEngagementAverage = $averageEngagement;
                $OutdoorPlayIndependenceAverage = $averageIndependence;
                break;
            case 'StoryTime':
                $StoryTimeEngagementAverage = $averageEngagement;
                $StoryTimeIndependenceAverage = $averageIndependence;
                break;
            case 'SensoryIntegration':
                $SensoryIntegrationEngagementAverage = $averageEngagement;
                $SensoryIntegrationIndependenceAverage = $averageIndependence;
                break;
            case 'LifeSkills':
                $LifeSkillsEngagementAverage = $averageEngagement;
                $LifeSkillsIndependenceAverage = $averageIndependence;
                break;
        }


        //calculate the for each activity
        $OutdoorAverage = ($OutdoorPlayEngagementAverage + $OutdoorPlayIndependenceAverage)/2;
        $StoryTimeAverage = ($StoryTimeEngagementAverage + $StoryTimeIndependenceAverage)/2;
        $SensoryIntegrationAverage = ($SensoryIntegrationEngagementAverage + $SensoryIntegrationIndependenceAverage)/2;
        $LifeSkillsAverage = ($LifeSkillsEngagementAverage + $LifeSkillsIndependenceAverage)/2;



    } else {
        // Handling cases where no results were found for the activity
        if ($activity === 'OutdoorPlay') {
            $OutdoorPlayEngagementAverage = 0;
            $OutdoorPlayIndependenceAverage = 0;
        } elseif ($activity === 'StoryTime') {
            $StoryTimeEngagementAverage = 0;
            $StoryTimeIndependenceAverage = 0;
        } elseif ($activity === 'SensoryIntegration') {
            $SensoryIntegrationEngagementAverage = 0;
            $SensoryIntegrationIndependenceAverage = 0;
        } elseif ($activity === 'LifeSkills') {
            $LifeSkillsEngagementAverage = 0;
            $LifeSkillsIndependenceAverage = 0;
        }
    }
}

?>

<!------------------------------------ending here------------------------------->


<!-----------------------------start for the line graph here--------------------->
<?php
include('../partials/connect.php');

$learner_id = intval($_GET['id']);  // Ensure it's an integer

// Initialize arrays to store averages
$dailyData = [];
$weeklyData = [];
$monthlyData = [];
$yearlyData = [];

// Calculate daily averages, excluding weekends
$daySql = "SELECT DATE(ReportDate) as reportDay, 
                  AVG((EngagementLevel + IndependanceLevel) / 2) as dailyAverage 
           FROM scores 
           WHERE LearnerId = ? 
           AND DAYOFWEEK(ReportDate) NOT IN (1,7)  -- Exclude weekends
           GROUP BY reportDay
           ORDER BY reportDay ASC";
$stmt = $connect->prepare($daySql);
$stmt->bind_param("i", $learner_id);
$stmt->execute();
$dayResults = $stmt->get_result();

while ($row = $dayResults->fetch_assoc()) {
    $dailyData[] = $row['dailyAverage'];
}

// Calculate weekly averages
$weekSql = "SELECT YEAR(ReportDate) as year, WEEK(ReportDate) as week, 
                   AVG((EngagementLevel + IndependanceLevel) / 2) as weeklyAverage 
            FROM scores 
            WHERE LearnerId = ? 
            AND DAYOFWEEK(ReportDate) NOT IN (1,7)  -- Exclude weekends
            GROUP BY year, week
            ORDER BY year, week ASC";
$stmt = $connect->prepare($weekSql);
$stmt->bind_param("i", $learner_id);
$stmt->execute();
$weekResults = $stmt->get_result();

while ($row = $weekResults->fetch_assoc()) {
    $weeklyData[] = $row['weeklyAverage'];
}

// Calculate monthly averages
$monthSql = "SELECT YEAR(ReportDate) as year, MONTH(ReportDate) as month, 
                    AVG((EngagementLevel + IndependanceLevel) / 2) as monthlyAverage 
             FROM scores 
             WHERE LearnerId = ? 
             GROUP BY year, month
             ORDER BY year, month ASC";
$stmt = $connect->prepare($monthSql);
$stmt->bind_param("i", $learner_id);
$stmt->execute();
$monthResults = $stmt->get_result();

while ($row = $monthResults->fetch_assoc()) {
    $monthlyData[] = $row['monthlyAverage'];
}

// Calculate yearly averages
$yearSql = "SELECT YEAR(ReportDate) as year, 
                   AVG((EngagementLevel + IndependanceLevel) / 2) as yearlyAverage 
            FROM scores 
            WHERE LearnerId = ? 
            GROUP BY year
            ORDER BY year ASC";
$stmt = $connect->prepare($yearSql);
$stmt->bind_param("i", $learner_id);
$stmt->execute();
$yearResults = $stmt->get_result();

while ($row = $yearResults->fetch_assoc()) {
    $yearlyData[] = $row['yearlyAverage'];
}

// Pass the data to JavaScript
$chartData = [
    'days' => $dailyData,
    'weeks' => $weeklyData,
    'months' => $monthlyData,
    'years' => $yearlyData
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
                
                    <div class="row-lg-6 row-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="text-align: center;">Parent-Teacher Communication</h4>

                                <div class="time-frame-container">
                                <label for="timeFrame" class="time-frame-label">Select Time Frame:</label>
                                <select id="timeFrame" class="form-select">
                                    <option value="days">Days</option>
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
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Engagement vs Independance Levels</h4>
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
                                    
                                    <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                        <canvas id="pie_chart" width="44" height="27" style="display: block; width: 40px; height: 20px;" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div><br>

                <div class="row">
                
                <div class="text-center" style="margin-top: 10px;">
              <a href="a-analytics.php?id=<?php echo $learner_id ?>.php" class="btn btn-primary">Communications Analysis</a>
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
                'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8', 'Day 9', 'Day 10',
                'Day 11', 'Day 12', 'Day 13', 'Day 14', 'Day 15', 'Day 16', 'Day 17', 'Day 18', 'Day 19', 'Day 20',
                'Day 21', 'Day 22', 'Day 23', 'Day 24', 'Day 25', 'Day 26', 'Day 27', 'Day 28', 'Day 29', 'Day 30',
                'Day 31', 'Day 32', 'Day 33', 'Day 34', 'Day 35', 'Day 36', 'Day 37', 'Day 38', 'Day 39', 'Day 40',
                'Day 41', 'Day 42', 'Day 43', 'Day 44', 'Day 45', 'Day 46', 'Day 47', 'Day 48', 'Day 49', 'Day 50',
                'Day 51', 'Day 52', 'Day 53', 'Day 54', 'Day 55', 'Day 56', 'Day 57', 'Day 58', 'Day 59', 'Day 60'
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
                labels: [
                    'Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 
                    'Week 6', 'Week 7', 'Week 8', 'Week 9', 'Week 10', 
                    'Week 11', 'Week 12'],
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
                labels: ['2024', '2025', '2026', '2027', '2028', '2029'],
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
            type: 'bar',
            data: {
                labels: ["Social Skills", "Outdoor Play", "Story Time", "Sensory Intergration"],
                datasets: [{
                    label: 'Sales by Color',

                    data: [<?php echo $LifeSkillsAverage; ?>, <?php echo $OutdoorAverage; ?>, <?php echo $StoryTimeAverage; ?>, <?php echo $SensoryIntegrationAverage; ?>],
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

