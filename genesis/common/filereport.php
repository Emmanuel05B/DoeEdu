<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#regForm {
  background-color: #ffffff;
  margin: 100px auto;
  font-family: Raleway;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}
textarea {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
textarea.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #00a8ff;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #00a8ff;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}

select {
  width: 100%;; /* Set the width of the select */
  padding: 10px; /* Add padding to improve readability */
  font-size: 16px; /* Set the font size */
  cursor: pointer; /* Add cursor pointer on hover */
  border: 1px solid #aaaaaa;

}
/* Style the select when hovered */
select:hover {
  background-color: #e6e6e6; /* Change background color on hover */
}

.align {
  padding: 10px;
  width: 100%;
  border: 1px solid #aaaaaa;
  display:flex;
  justify-content: space-around;
}
.align label {
  display:flex;
  align-items: center;
}



.range-label {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 10px;

            
        }
        .label-item {
            flex: 1;
            text-align: center;
            font-size: 0.8em;
            position: relative;
        }
        .label-item:before {
            content: attr(data-label);
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
        }
        .range-slider {
            width: 100%;
        }
        

</style>
<body>

<form id="regForm" action="handler/testhandler.php" method="POST" enctype="multipart/form-data">

                                 
  <h1>Report:</h1>
  <!--1st tab.  One "tab" for each step in the form: -->
  <div class="tab">
     <?php

     include('../partials/connect.php');

     $id = $_GET['id'];  //for learner
   

     $sql = "SELECT * FROM learner WHERE LearnerId = $id" ;
     $results = $connect->query($sql);
     $final = $results->fetch_assoc();           
               
    ?>
 
    <h2>File a Report for:</h2> <br>
    <label> Name : <?php echo $final['Name'] ?> </label> <br>
    <label >Surname : <?php echo $final['Surname'] ?> </label> <br>
    <label >Date Of Birth :  <?php echo $final['DateOfBirth'] ?> </label> <br>
    <label >Gender :  <?php echo $final['Gender'] ?> </label> <br>
    <label >Grade : <?php echo $final['GradeId'] ?> </label> <br>

    <input type="hidden" id="urlParams" name="learnerFakeid" value="<?php echo $final['LearnerId'] ?>">

  </div>

  <div class="tab">
   <!--2nd tab. Arrival and Attendance -->
        <fieldset>
            
              <legend>Arrival Time and Attendance</legend> <br>

              <label for="arrival-time">Arrival Time:</label>
              <input type="time" id="arrival-time" name="arrival-time"><br><br>

              <label for="arrival-time">Attendance:</label>
              <div class="align">
              <label>
                  <input type="radio" name="attendance" value="Present"> Present
              </label>
              <label>
                  <input type="radio" name="attendance" value="Late"> Late
              </label>
              <label>
                  <input type="radio" name="attendance" value="Absent"> Absent
              </label> 
              </div><br>
              <label for="attendance-reason">Reason for Tardiness/Absence:</label>
              <input type="text" id="attendance-reason" name="attendance-reason">
            
        </fieldset>
 
  </div>

  <div class="tab">
     <!--3rd tab. -->
  <fieldset>
            <legend>Initial Transition</legend> <br>
            <label for="separation-parent">Separation from Parent/Caregiver:</label> <br>
            <select id="separation-parent" name="separation-parent">
                <option value="Calm">Calm</option>
                <option value="Anxious">Anxious</option>
                <option value="Upset">Upset</option>
                <option value="Needed Additional Support">Needed Additional Support</option>
            </select><br><br>
            
            <label for="transition-classroom">Transition to Classroom:</label>
            <select id="transition-classroom" name="transition-classroom">
                <option value="Smooth Transition">Smooth Transition</option>
                <option value="Needed Prompts/Assistance">Needed Prompts/Assistance</option>
                <option value="Difficult">Difficult</option>
            </select>
        </fieldset>
  </div>

 
  <div class="tab">
   <!-- 4th tab.Morning Circle Activity -->
   <fieldset>  
        <legend>Morning Circle Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="engagement-level">Engagement Level =  <output0 style="font-size: 25px; font-weight: bold;">5</output0></label>
        </div>
   
        <div class="range-label" > 
        <div class="label-item" data-label="Not Engaged"></div>
        <div class="label-item" data-label="Partially Engaged"></div>
        <div class="label-item" data-label="Highly Engaged"></div>
        </div>
            <input type="range" id="engagement-level" name="engagement-level" min="1" max="10" step="1" value="5" oninput="updateValue0(this.value)">

        </fieldset><br>
       

  </div>

  <div class="tab">
     <!-- nnnnnnnnnnnnnn -->
     <fieldset>  
        <legend>Morning Circle Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="independance-level">Independance Level =  <output1 style="font-size: 25px; font-weight: bold;">5</output1></label>
                </div>

        <div class="range-label" > 
        <div class="label-item" data-label="Dependent"></div>
        <div class="label-item" data-label="Fairly Independent"></div>
        <div class="label-item" data-label="Fully Independent"></div>
        </div>
            <input type="range" id="independance-level" name="independance-level" min="1" max="10" step="1" value="5" oninput="updateValue1(this.value)">

        </fieldset>
    
  </div>


  <div class="tab">
     <!-- 5th tab social skills Activity -->
     <fieldset>  
        <legend>Social Skills Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="ssEngagement-level">Engagement Level =  <output2 style="font-size: 25px; font-weight: bold;">5</output2></label>
        </div>
        
        <div class="range-label" > 
        <div class="label-item" data-label="Not Engaged"></div>
        <div class="label-item" data-label="Partially Engaged"></div>
        <div class="label-item" data-label="Highly Engaged"></div>
        </div>
        <input type="range" id="ssEngagement-level" name="ssEngagement-level" min="1" max="10" step="1" value="5" oninput="updateValue2(this.value)">

        </fieldset><br>
        
  </div>

  <div class="tab">
     <!-- nnnnnnnnnnnnnnnnnnnnnnnnn -->
     <fieldset> 
        <legend>Social Skills Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="ssIndependance-level">Independance Level =  <output3 style="font-size: 25px; font-weight: bold;">5</output3 ></label>
                </div>

        <div class="range-label" >  
        <div class="label-item" data-label="Dependent"></div>
        <div class="label-item" data-label="Fairly Independent"></div>
        <div class="label-item" data-label="Fully Independent"></div>
        </div>
            <input type="range" id="ssIndependance-level" name="ssIndependance-level" min="1" max="10" step="1" value="5" oninput="updateValue3(this.value)">

        </fieldset>
    
  </div>

 
 
  <div class="tab">
  <!-- 6th tab. Sensory and Emotional State -->
  <fieldset>  
        <legend>Sensory and Emotional Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="seEngagement-level">Engagement Level =  <output4 style="font-size: 25px; font-weight: bold;">5</output4></label>
        </div>  
   
        <div class="range-label" > 
        <div class="label-item" data-label="Not Engaged"></div>
        <div class="label-item" data-label="Partially Engaged"></div>
        <div class="label-item" data-label="Highly Engaged"></div>
        </div>
        <input type="range" id="seEngagement-level" name="seEngagement-level" min="1" max="10" step="1" value="5" oninput="updateValue4(this.value)">

        </fieldset><br>
      
  
  </div>

  <div class="tab">
     <!--nnnnnnnnnnnnnnnnnnnnn -->
     <fieldset> 
        <legend>Sensory and Emotional Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="seIndependance-level">Independance Level =  <output5 style="font-size: 25px; font-weight: bold;">5</output5 ></label>
                </div>

        <div class="range-label" > 
        <div class="label-item" data-label="Dependent"></div>
        <div class="label-item" data-label="Fairly Independent"></div>
        <div class="label-item" data-label="Fully Independent"></div>
        </div>
            <input type="range" id="seIndependance-level" name="seIndependance-level" min="1" max="10" step="1" value="5" oninput="updateValue5(this.value)">

        </fieldset>
    
  </div>

  <div class="tab">
    <!-- 7th tab. Sensory and Emotional State -->
  <fieldset>  
        <legend>Story Time Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="stEngagement-level">Engagement Level =  <output6 style="font-size: 25px; font-weight: bold;">5</output6></label>
        </div>
   
        <div class="range-label" > 
        <div class="label-item" data-label="Not Engaged"></div>
        <div class="label-item" data-label="Partially Engaged"></div>
        <div class="label-item" data-label="Highly Engaged"></div>
        </div>
        <input type="range" id="stEngagement-level" name="stEngagement-level" min="1" max="10" step="1" value="5" oninput="updateValue6(this.value)">

        </fieldset><br>
        

  </div>

  <div class="tab">
     <!-- nnnnnnnnnnnnnnnn -->
     <fieldset> 
        <legend>Story Time Activity</legend>
                <div class="range-label" > 
                <h6>.</h6>
                <label for="stIndependance-level">Independance Level =  <output7 style="font-size: 25px; font-weight: bold;">5</output7 ></label>
                </div>

        <div class="range-label" > 
        <div class="label-item" data-label="Dependent"></div>
        <div class="label-item" data-label="Fairly Independent"></div>
        <div class="label-item" data-label="Fully Independent"></div>
        </div>
            <input type="range" id="stIndependance-level" name="stIndependance-level" min="1" max="10" step="1" value="5" oninput="updateValue7(this.value)">

        </fieldset>
  </div>
 
  <div class="tab">
     <!-- 8th tab. freeeee -->
    
  </div>
 
    <div class="tab">
 <!-- 9th/last tab.  -->

        <?php

          $userId = $_SESSION['user_id'];  //for user/reporter
          $type = $_SESSION['EmployeeType'] ;   //user type
          

          $usql = "SELECT * FROM users WHERE Id = $userId" ;
          $uresults = $connect->query($usql);
          $ufinal = $uresults->fetch_assoc();                    
        ?>

        <h2>My Details:</h2> <br>
        <label> Name : <?php echo $ufinal['Name'] ?> </label> <br>
        <label> Surname : <?php echo $ufinal['Surname'] ?> </label> <br>
        <label> Identity Number :  <?php echo $ufinal['UserId'] ?> </label> <br>
        <label> Email :  <?php echo $ufinal['Email'] ?> </label> <br>
        <input type="hidden" id="urlParams" name="teacherFakeid" value="<?php echo $ufinal['Id'] ?>">
        <input type="hidden" id="urlParams" name="usertype" value="<?php echo $type ?>">


    </div>

  <div style="overflow:auto;">
    <div style="float:right;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    </div>
  </div>
  <!-- Circles which indicates the steps of the form: -->
  <div style="text-align:center;margin-top:40px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>

    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>

</form>

    <script>  //script for the Range Slider 
    
       

        function updateValue0(value) {
            document.querySelector('output0').innerText = value;
        }
        function updateValue1(value) {
            document.querySelector('output1').innerText = value;
        }

        function updateValue2(value) {
            document.querySelector('output2').innerText = value;
        }
        function updateValue3(value) {
            document.querySelector('output3').innerText = value;
        }
        //the second 4
        function updateValue4(value) {
            document.querySelector('output4').innerText = value;
        }
        function updateValue5(value) {
            document.querySelector('output5').innerText = value;
        }
        function updateValue6(value) {
            document.querySelector('output6').innerText = value;
        }
        function updateValue7(value) {
            document.querySelector('output7').innerText = value;
        }
        
    </script>

<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

</body>
</html>
