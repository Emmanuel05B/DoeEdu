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




 

  label {
    display: inline-block;
    width: 150px; /* Adjust width as needed */
    margin-right: 10px; /* Add some spacing between label and input */
    vertical-align: middle; /* Align label with input field */
  }

  input {
    display: inline-block;
    vertical-align: middle;
    width: 200px; 

  }

  
    .pos {
        padding: 1px;
        margin-top: 10px;
        
    }

    /* Create two equal columns that floats next to each other */
    .column1 {
        float: left;
        width: 50%;
        padding: 20px;
        height: 200px;
    }

    .column2 {
        float: left;
        width: 50%;
        padding: 5px;

    }

    .column3 {
        float: left;
        width: 50%;
        padding: 5px;
      
    }

 
</style>
<body>

<form id="regForm" action="treporthandler.php" method="POST" enctype="multipart/form-data">

                <!--Teacher Details. from URL ...> id..& l_id...&re_id-->
                <?php
                include('../partials/connect.php');

                $file_id = $_GET['file_id'];  // for File/Report
                $sql = "SELECT * FROM finalreport WHERE ReportId = $file_id";
                $fileResults = $connect->query($sql);
                $fileFinal = $fileResults->fetch_assoc();

                ///////////////////////////////////////////////
                $id = $_GET['l_id'];  // for learner
                $sql = "SELECT * FROM learner WHERE LearnerId = $id";
                $results = $connect->query($sql);
                $final = $results->fetch_assoc();
                //////////////////////////////////////////////
                ?>

    <h1 style="text-align: center; margin-bottom: 20px;">Report for: <?php echo $final['Name'] ?></h1>

    <!-- One "tab" for each step in the form: -->
    <div class="tab">

        <fieldset>
            <!-- my deviding double line -->
        </fieldset>

        <div class="pos">
            <div class="column1">


                <h4>Learner Details:</h4>
                <label style="width: 400px;">Name : <?php echo $final['Name'] ?> </label> <br>
                <label style="width: 400px;">Surname : <?php echo $final['Surname'] ?> </label> <br>
                <label style="width: 400px;">Date Of Birth : <?php echo $final['DateOfBirth'] ?> </label> <br>
                <label style="width: 400px;">Gender : <?php echo $final['Gender'] ?> </label> <br>
                <label style="width: 400px;">Diagnosis : ASD Level 2 </label> <br>

                <input type="hidden" id="urlParams" name="learnerFakeid" value="<?php echo $final['LearnerId'] ?>">
            </div>
            <div class="column1">
                <!--Teacher Details -->
                <?php
                $userId = $_SESSION['user_id'];  // for user
                $usql = "SELECT * FROM users WHERE Id = $userId";
                $uresults = $connect->query($usql);
                $ufinal = $uresults->fetch_assoc();
                ?>

                <h4>Teacher Details:</h4>
                <label style="width: 400px;">Name : <?php echo $ufinal['Name'] ?> </label> <br>
                <label style="width: 400px;">Surname : <?php echo $ufinal['Surname'] ?> </label> <br>
                <label style="width: 400px;">Email : <?php echo $ufinal['Email'] ?> </label> <br>
                <input type="hidden" id="urlParams" name="userfakeid" value="<?php echo $ufinal['Id'] ?>">
            </div>
        </div>

        <fieldset>
            <!-- my deviding double line -->
        </fieldset>

        <div class="pos">
            <div class="column2">
                <!--Arrival and Attendance -->
                <fieldset>
                    <legend>
                        <h4>Attendance</h4>
                    </legend>
                    <label for="arrival-time">Attendance Rate:</label>
                    <input type="text" id="arrival-time" name="arrival-time" value="<?php echo $fileFinal['ArrivalTime'] ?>" readonly><br><br>

                    <label for="attendance-reason">Classes missed: </label>
                    <input type="text" id="attendance-reason" name="attendance-reason" value="<?php echo $fileFinal['Reason'] ?>" readonly>
                   
                   
                </fieldset>

                <fieldset>
                    <legend>
                        <h4>Strengths</h4>
                    </legend>
                               <ul>
                                    <li>I nee happening</li>
                                    <li>I need time to process information</li>
                                    <li>Eatirectly into my hand.</li>
                                    <li>I am still in nappies aessing</li>
                                    <li>Eatirectly into my hand.</li>
                                    <li>I am still in nappies aessing</li>
                                </ul>
                </fieldset>

                
                <fieldset>
                    <legend>
                        <h4>Medication </h4>
                    </legend>
                    <input type="text" id="SKAengagementlevel" name="SKAengagementlevel" value="Engagement Level  = <?php echo $fileFinal['SKAengagementlevel'] ?>/10" readonly><br><br>
                    <input type="text" id="SKAindependancelevel" name="SKAindependancelevel" value="Independence Level  = <?php echo $fileFinal['SKAindependancelevel'] ?>/10" readonly>
                </fieldset>

                <fieldset>
                    <legend>
                        <h4>Interests</h4>
                    </legend>
                                <ul>
                                    <li>Puzzles</li>
                                    <li>Being outside</li>
                                    <li>Animals</li>
                                    <li>Bubbles</li>
                                    <li>Chocolate cake</li>
                                    <li>Messy play</li>
                                </ul>
                </fieldset>
                
            </div>

            <div class="column3">
                <fieldset>
                    <legend>
                        <h4>Participation</h4>
                    </legend>
                    <label for="arrival-time">Attendance Rate:</label>
                    <input type="text" id="arrival-time" name="arrival-time" value="<?php echo $fileFinal['ArrivalTime'] ?>" readonly><br><br>

                    <label for="attendance-reason">Classes missed: </label>
                    <input type="text" id="attendance-reason" name="attendance-reason" value="<?php echo $fileFinal['Reason'] ?>" readonly>
                   
                </fieldset> 


                <fieldset>
                    <legend>
                        <h4>My Support Strategies</h4>
                    </legend>

                                <ul>
                                    <li>I need visuals to help me understant what is happening</li>
                                    <li>I need time to process information</li>
                                    <li>Eating is still hard for meI may need extra time and food put directly into my hand.</li>
                                    <li>I am still in nappies and need help with dressing</li>
                                </ul>

                </fieldset>
                

                <fieldset>
                    <legend>
                        <h4>Activities</h4>
                    </legend>
                    <label for="arrival-time">Life Skills:</label>
                    <input type="text" id="arrival-time" name="arrival-time" value="<?php echo $fileFinal['ArrivalTime'] ?>" readonly><br><br>

                    <label for="attendance-reason">Sensory Intergration: </label>
                    <input type="text" id="attendance-reason" name="attendance-reason" value="<?php echo $fileFinal['Reason'] ?>" readonly><br><br>
                    
                    <label for="arrival-time">Story Time:</label>
                    <input type="text" id="arrival-time" name="arrival-time" value="<?php echo $fileFinal['ArrivalTime'] ?>" readonly><br><br>

                    <label for="attendance-reason">Outdoor Activities: </label>
                    <input type="text" id="attendance-reason" name="attendance-reason" value="<?php echo $fileFinal['Reason'] ?>" readonly>
                   
                </fieldset>

                <fieldset>
                    <legend>
                        <h4>How to help me calm down</h4>
                    </legend>
                    <ul>
                                    <li>I need visuals to help me understant what is happening</li>
                                    <li>I need time to process information</li>
                                 
                                    <li>Eating is still hard for meI may need extra time and food put directly into my hand.</li>
                                    <li>I am still in nappies and need help with dressing</li>
                                </ul>

                </fieldset> <br>

            </div>
        </div>

        <fieldset>
        </fieldset>

       <!-- <h4>add a download button and email button</h4> -->

    </div>

</form>

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
        fixStepIndicator(n);
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