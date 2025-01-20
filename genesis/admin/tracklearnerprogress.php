<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<?php include("adminpartials/head.php"); //affects the alert styling ?>  
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<style>

.content {
  background-color: white;
  
  margin-top: 20px;
  margin-left: 100px;
  margin-right: 100px;
}
.pos {
  margin-top: 50px;
  margin-left: 10px;
  margin-right: 10px;
}

.container {
  width: 100%;
  background-color: white;
  position: relative;
  margin-bottom: 20px;
}

.scale {
  width: 100%;
  height: 20px;
  position: relative;
  background: linear-gradient(to right, #f00 0%, #ffa500 50%, #0f0 100%);
}

.scale-mark {
  position: absolute;
  height: 20px;
  width: 1px;
  background-color: #000;
}

.scale-mark.start { left: 0; }
.scale-mark.middle { left: 50%; }
.scale-mark.end { left: 100%; }

.skills-container {
  position: relative;
  height: 43px; 
  border: 1px solid #000;
  border-radius: 5px; 
}

.skills {
  text-align: right;
  padding-top: 10px;
  padding-bottom: 10px;
  color: white;
  position: absolute;
  box-sizing: border-box;
}

.css {
  background-color: white; /* Default color */
}

.scale-label {
  position: absolute;
  font-size: 12px;
  top: -20px; /* Position above the progress bar */
  width: 40px;
  text-align: center;
}

#buttonContainer {
  display: flex;
  flex-direction: column; /* Stack items vertically */
  align-items: center; /* Center items horizontally */
}

#updateButton {
  background-color: blue;
  width: 180px; 
  padding: 10px; 
  border: none;
  color: white;
  cursor: pointer;
  border-radius: 5px;
  margin-bottom: 20px;
}

#status {
  border: 1px solid #ccc; 
  padding: 10px; /* Padding inside the box */
  background-color: #f9f9f9; 
  border-radius: 5px;
  width: 320px;
}

.scale-label.start { left: 0; }
.scale-label.middle { left: 50%; transform: translateX(-50%); }
.scale-label.end { right: 0; }

.button {
  background-color: blue;
  width: 120px; /* Adjust width as needed */
  padding: 10px; /* Adjust padding for thickness */
  border: none;
  color: white;
  cursor: pointer;
  border-radius: 5px; /* Optional: Add border radius for rounded corners */
  margin-bottom: 20px;
            
        }
</style>

<?php
include('../partials/connect.php');
           
$learner_id = intval($_GET['id']);  //for leaner  //intval to ensure that it is an integer
$statusValue = intval($_GET['val']);  // get the subject value also, Ensure it's an integer  from allleaners.php..
//from some pages.. you have to find a way of chosing a asubject to track for.  such as learnerprofile.php..
// make them select the subject prior to opening the tracking page.


if($fileResults ===false){

   echo "Error executing the query.";

}else{

   // Check if any rows were returned
   if ($fileResults->num_rows > 0) {



      //first check the subject value.  val=?.. then track progress based on that subject.
      if ($statusValue == 1) {
        echo '<h3>Grade 12 Mathematics</h3><br>';

        //the idea is.  i want combined marks for this particular learner for this subject.

        //subjectid/val  can get me ActivityId from the activities table.   

       // select ActivityId, MaxMarks from activities WHERE SubjectId = 1.  //for statusValue = 1

        //after geting the all the activityid for this subject
        //put them in an array...already they are in a array/table form.. then go throgh it below.

        while($results = $fileResults->fetch_assoc())
        {
        //something like.  
        //select * from learneractivitymarks where LearnerId = $learner_id AND ActivityId = (one in array))
        //  $secondsql = " SELECT * from learneractivitymarks where LearnerId = $learner_id AND ActivityId = $results['ActivityId']" ;
        }
        //ill have the folllowing for all the activities of this learner.
        /*
          Id
          LearnerId   ActivityId    MarkerId    MarksObtained   DateAssigned    Attendance
          AttendanceReason    Submission    SubmissionReason
        */

        $sql = "SELECT * FROM learneractivitymarks WHERE LearnerId = $learner_id" ;
        $fileResults = $connect->query($sql);


              //initialise the variables to 0
        $MarksObtained =  0;
        $Totals = 0;

        $numResults = 0;
        //get/fetch all reports for this specific kid
        while($results = $fileResults->fetch_assoc()) 
        {
          //all activities
          $MarksObtained +=  ($results['MarksObtained']);    
          $Totals +=  ($results['MaxMarks']);    

          $numResults ++;
        }

        $AVGscore = round((($MarksObtained) / ($Totals * $numResults)) * 100, 2);


      } else if ($statusValue == 2) {

        echo '<h3>Grade 12 Physical Sciences</h3><br>';

      } else if ($statusValue == 3) {
        echo '<h3>Grade 11 Mathematics</h3><br>';


      } else if ($statusValue == 4) {
        echo '<h3>Grade 11 Physical Sciences</h3><br>';

      } else if ($statusValue == 5) {
        echo '<h3>Grade 10 Mathematics</h3><br>';


      } else if ($statusValue == 6) {
        echo '<h3>Grade 10 Physical Sciences</h3><br>';

      } else {
        // Default case if none of the statuses match
        echo '<h1>Learners - Unknown Status</h1>';
      }




    } else {
        /*
           echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "No Reports Exists.",
                        text: "No reports have been made for this learner.",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "ttrackprogress.php";
                        }
                    });
                </script>';   */

          echo  "No reports have been made for this learner."; 
        
        exit; // stop further execution
    }

 
}


?>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include("adminpartials/header.php") ?>;

  <!-- Left side column. contains the logo and sidebar -->
 
 <?php include("adminpartials/mainsidebar.php") ?>;

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <!-- ./col -->
            <!-- ./col 555555555555555555555-->
  <section class="content">
    <div class="container-fluid">
          
        <div class="oder">
        <div style="text-align: center;">
  <h2 style="display: inline-block;">Learner Progress</h2><br><br><br>

</div>

<div class="container">
  <div class="scale">
    <div class="scale-mark start"></div>
    <div class="scale-mark middle"></div>
    <div class="scale-mark end"></div>
    <div class="scale-label start">0%</div>
    <div class="scale-label middle">50%</div>
    <div class="scale-label end">100%</div>

  </div><br><br>

  <div class="skills-container">
    <div class="skills css" id="cssSkill">50%</div>
  </div><br>
  <!-- <p style="text-align: center; font-weight: bold;">Results Based on Scores from <?php //echo $numResults ?>  Report/s </p>-->
</div>

<div id="buttonContainer">
  
  <button id="updateButton" onclick="updateSkill()">Update To Clear Old Scores</button>
  <p id="status"></p><br>
  <p><a class="button" href="a-analytics.php?id=<?php echo $learner_id ?>">View Scores</a></p>  


</div>  
        </div>
    </div>
</section>

        <!-- ./col -->
      </div>
      
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<script>

document.addEventListener('DOMContentLoaded', (event) => {
  let lastScore = localStorage.getItem('cssSkillPercentage');
  if (lastScore) {
    updateSkillElement(parseInt(lastScore));
  }
});

function updateSkill() {
  var score = <?php echo $AVGscore ?>; 
  localStorage.setItem('cssSkillPercentage', score);
  updateSkillElement(score);
  document.getElementById('updateButton').innerHTML = 'Updated';
}

function updateSkillElement(score) {
  var skillElement = document.getElementById('cssSkill');
  skillElement.style.width = score + '%';
  skillElement.innerHTML = score + '%';
  
  var status;
  if (score < 50) {
    skillElement.style.backgroundColor = 'red';
    status = 'Bad, if the leaner continues like this , he will probably fail';
  } else if (score < 80) {
    skillElement.style.backgroundColor = 'orange';
    status = 'Getting There, the leaner is doing good, the aim is to get him to 100%';
  } else {
    skillElement.style.backgroundColor = '#53f527';
    status = 'Good, the leaner is really improving, motivate the learner to keep working hard.';
  }

  document.getElementById('status').innerHTML = '<p style="font-weight: bold;" >Status: </p>' + status + '<br><br> <p style="text-align: center; font-weight: bold;">Results Based on Aggregate Scores from All Reports to Date</p>'
  ;
}
</script>

<?php include("adminpartials/queries.php") ?>;
<script src="dist/js/demo.js"></script>
</body>
</html>


