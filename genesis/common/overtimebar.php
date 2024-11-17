<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/helpers.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    

</head>

<?php
include('../partials/connect.php');
           
$learner_id = intval($_GET['id']);  //for leaner  //intval to ensure that it is an integer

$sql = "SELECT * FROM finalreport WHERE LearnerId = $learner_id" ;
$fileResults = $connect->query($sql);

if($fileResults ===false){
  // echo out error
}
$numRows = $fileResults->num_rows;

//initialise the variables to 0
$MCAengagementlevelTotal =  0;
$MCAindependancelevelTotal = 0;
//social skills
$SKAengagementlevelTotal =  0;
$SKAindependancelevelTotal =  0;
//sensory intergration
$SEAengagementlevelTotal =  0;
$SEAindependancelevelTotal =  0;
//story time
$STAengagementlevelTotal =  0;
$STAindependancelevelTotal =  0;

$arrayResults = array();
$numResults = 0;

//get/fetch all reports for this specific kid
while($results = $fileResults->fetch_assoc()) 
{
  //morning circle
  $MCAengagementlevelTotal =  ($results['MCAengagementlevel']);
  $MCAindependancelevelTotal =  ($results['MCAindependancelevel']);

  //social skills
  $SKAengagementlevelTotal =  ($results['SKAengagementlevel']);
  $SKAindependancelevelTotal =  ($results['SKAindependancelevel']);

  //sensory intergration
  $SEAengagementlevelTotal =  ($results['SEAengagementlevel']);
  $SEAindependancelevelTotal =  ($results['SEAindependancelevel']);

  //story time
  $STAengagementlevelTotal =  ($results['STAengagementlevel']);
  $STAindependancelevelTotal =  ($results['STAindependancelevel']); 

///////////////////////////////////////////////////////////////////////////////////
    //morning circle
    $MCAengagementlevelAVG =  (($MCAengagementlevelTotal)/(10))*100;
    $MCAindependancelevelAVG =  (($MCAindependancelevelTotal)/(10))*100;

    //social skills
    $SKAengagementlevelAVG =  (($SKAengagementlevelTotal)/(10))*100;
    $SKAindependancelevelAVG =  (($SKAindependancelevelTotal)/(10))*100;

    //sensory intergration
    $SEAengagementlevelAVG =  (( $SEAengagementlevelTotal)/(10))*100;
    $SEAindependancelevelAVG =  (($SEAindependancelevelTotal)/(10))*100;

    //story time
    $STAengagementlevelAVG =  (($STAengagementlevelTotal)/(10))*100;
    $STAindependancelevelAVG =  (($STAindependancelevelTotal)/(10))*100;

    //score for 1 report
    $reportAVGscore =  ((($MCAengagementlevelAVG + $MCAindependancelevelAVG + $SKAengagementlevelAVG + $SKAindependancelevelAVG + $SEAengagementlevelAVG + $SEAindependancelevelAVG + $STAengagementlevelAVG + $STAindependancelevelAVG)/8)); 

    $arrayResults[] = $reportAVGscore;

  $numResults ++;
  
}

  $labels = range(1, $numRows); 

  $resultsJSON = json_encode($arrayResults);
  $labelsJSON = json_encode($labels);


?>

<body>
    
<div class="container">
  <canvas id="myChart"></canvas>
</div>


<script>
  
  const chart = document.getElementById('myChart');
  //Chart.defaults.font.family = 'Lato';
  Chart.defaults.font.size = 16; 
  Chart.defaults.font.color = 'pink'; 

 

   //let chartName = 
  new Chart(chart, {
    type: 'bar',  //bar, horizontalBar, pie, line, doughnut, radar, polarArea
    data: {
      labels: <?php echo $labelsJSON; ?>,
      datasets: [{
        label: 'Report vs Avarage',
        data: <?php echo $resultsJSON; ?>,
        //my possible properties
        borderWidth: 1,
        borderColor: 'black',
        hoverBorderWidth:3,
        hoverBorderColor:'black',
        backgroundColor: '#95afc0',

      }]
      
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
      layout:{
        padding:{
          left:200,
          right:200,
          bottom:50,
          top:50
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


 