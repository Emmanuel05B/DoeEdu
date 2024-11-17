<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/helpers.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <title>MyChart.js Chart</title>

</head>

<script>


</script>

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



<body>
    
<div class="column">

    <div class="row">
        <div class="column" style="background-color:#7ed6df;">
            <div class="container">
                
                <div class="column"> <br>

                    <a href="../common/activitiesbar.php?id=<?php echo $learner_id ?>" class="btn btn-primary py-3 px-4"> Show Activities Bar </a>
                    <a href="../common/overtimereport.php?id=<?php echo $learner_id ?>" class="btn btn-primary py-3 px-4"> Show Reports Trend </a>
                    
                </div><br>
            
            </div>
        </div>
        <div class="column">

            <div class="container">
            <canvas id="myChart"></canvas>
            </div>
            
        </div>
    </div>

</div>


<script>

  //let  for those that are subject to change
  //const for those tht wont change except if they are contents of an object or array
  
  const chart = document.getElementById('myChart');

   //let chartName = 
  new Chart(chart, {
    type: 'line',  //bar, horizontalBar, pie, line, doughnut, radar, polarArea
    data: {
      labels: ['Outdoor Play', 'Story Time', 'Sensory Intergration', 'Social Skills'],
      datasets: [{
        label: 'Engagement Avarage',    
        //my possible properties  // border width t control the thickness of the line chart
        borderWidth: 2,
        borderColor: 'green',
        hoverBorderWidth:3,
        hoverBorderColor:'black',
        backgroundColor: 'rgba(67, 222, 24, 0.73)',
        data: [<?php echo $OutdoorPlayEngagementAverage ?>, <?php echo $StoryTimeEngagementAverage ?>, <?php echo $SensoryIntegrationEngagementAverage ?>, <?php echo $LifeSkillsEngagementAverage ?>]
        
      },
      {
        label: 'Independace Avarage',    
        //my possible properties
        borderWidth: 2,
        borderColor: 'blue',
        hoverBorderWidth:3,
        hoverBorderColor:'black',
        backgroundColor: 'rgba(54,162,235,0.6)',
        data: [<?php echo $OutdoorPlayIndependenceAverage ?>, <?php echo $StoryTimeIndependenceAverage ?>, <?php echo $SensoryIntegrationIndependenceAverage ?>, <?php echo $LifeSkillsIndependenceAverage ?>]
        
        
      }
     
    ]
      
    },

    options: {
      scales: {
        y: {
          beginAtZero: true,
          title: {
                    display: true,
                    text: 'Avarage(%)' 
                } 

        },
        x: {
            beginAtZero: true ,
            title: {
                    display: true,
                    text: 'Report No', 
                    fontSize:250
                } 
            
        }
      },
     
      legend: {
        display: true,
        position: 'right',
        labes:{
          fontColor: '#000'
        }
      },
      layout:{
        padding:{
          left:200,
          right:200,
          bottom:125,
          top:25
          
        }
      },
      tooltips:{
          enabled:true
        }

    }
  });
</script>
 

</body>
</html>


 