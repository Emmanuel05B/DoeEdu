<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
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

    /* Hide all steps by default */
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

    /* Circles for steps */
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

    .step.finish {
      background-color: #04AA6D;
    }

    /* Invisible table styles */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border: none;
      padding: 8px 12px;
    }

    td {
      text-align: center;
    }

    input[type="radio"], input[type="text"], input[type="email"], input[type="tel"], select {
      width: 100%;
      padding: 8px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    input[type="radio"] {
      width: auto;
      margin: 0;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
    }

    /* Additional styles for subject-table */
    .subject-table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    .subject-table th, .subject-table td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ddd;
    }

    /* Align next button to the right */
    .next-btn-container {
      text-align: right;
      margin-top: 20px;
    }
  </style>
</head>

<body>

  <form id="regForm" action="addlearnerhandler.php" method="POST" enctype="multipart/form-data">
    <h1>Registration:</h1>

    <!-- 1st tab (Learner Info) -->
    <div class="tab">
      <h4>Learner Info:</h4>
      <table>
        <tr>
          <td>
            <label for="name">First Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name" required>
          </td>
          <td>
            <label for="surname">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your surname" required>
          </td>
        </tr>
        <tr>
          <td>
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
          </td>
          <td>
            <label for="contactnumber">Contact Number (10 digits)</label>
            <input type="tel" class="form-control" id="contactnumber" name="contactnumber" pattern="[0-9]{10}" maxlength="10" required>
          </td>
        </tr>
        <tr>
      <td colspan="2" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="flex: 1;">
          <label for="grade">Grade</label>
          <select id="grade" name="grade" class="form-control">
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
        </div>

        <div style="flex: 1; margin-left: 20px;">
          <label for="knockout_time">Knockout Time</label>
          <input type="time" class="form-control" id="knockout_time" name="knockout_time">
        </div>
      </td>
    </tr>
          
      </table>
    </div>

    <!-- 2nd tab (Subject Selection and Duration) -->
    <div class="tab">
      <h4>Select Subjects and Duration:</h4>
      <table class="subject-table">
        <tr>
          <th>Subject</th>
          <th>Not Registered</th>
          <th>3 Months</th>
          <th>6 Months</th>
          <th>12 Months</th>
        </tr>
        <tr>
          <td>Mathematics</td>
          <td><input type="radio" name="subjects[Mathematics]" value="0" checked></td>
          <td><input type="radio" name="subjects[Mathematics]" value="450.00"></td>
          <td><input type="radio" name="subjects[Mathematics]" value="750.00"></td>
          <td><input type="radio" name="subjects[Mathematics]" value="1119.00"></td>
        </tr>
        <tr>
          <td>Physical Sciences</td>
          <td><input type="radio" name="subjects[physics]" value="0" checked></td>
          <td><input type="radio" name="subjects[physics]" value="450.00"></td>
          <td><input type="radio" name="subjects[physics]" value="750.00"></td>
          <td><input type="radio" name="subjects[physics]" value="1119.00"></td>
        </tr>
        <!-- Add more subjects as needed -->
      </table>
    </div>

    <!-- 3rd tab (Setting Current and Target Levels) -->
<!-- 3rd tab (Setting Current and Target Levels) -->
<div class="tab">
  <h4>Select Current and Target Levels:</h4>
  <table class="subject-table">
    <tr>
      <th>Subject</th>
      <th>Current Level (1 - 7)</th>
      <th>Target Level (3 - 7)</th>
    </tr>
    <tr>
      <td>Mathematics</td>
      <td>
        <select name="levels[Mathematics][current]" class="form-control">
          <option value="0">Select Level</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
      </td>
      <td>
        <select name="levels[Mathematics][target]" class="form-control">
          <option value="0">Select Target</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Physical Sciences</td>
      <td>
        <select name="levels[physics][current]" class="form-control">
          <option value="0">Select Level</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
      </td>
      <td>
        <select name="levels[physics][target]" class="form-control">
          <option value="0">Select Target</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
      </td>
    </tr>
    <!-- Add more subjects as needed -->
  </table>
</div>


    <!-- 4th tab (Parent Info) -->
    <div class="tab">
      <h4>Parent Info:</h4>
      <table>
        <tr>
          <td>
            <label for="parentname">First Names</label>
            <input type="text" class="form-control" id="parentname" name="parentname" placeholder="Names" value="">
          </td>
          <td>
            <label for="parentsurname">Surname</label>
            <input type="text" class="form-control" id="parentsurname" name="parentsurname" value="">
          </td>
          <td>
              <label for="name">Title </label>
              <select id="title" name="title" class="form-control" >
                  <option value="Mr">Mr</option>
                  <option value="Ms">Ms</option>
                  <option value="Mrs">Mrs</option>
              </select>
          </td>


        </tr>
        <tr>
          <td>
            <label for="parentemail">Email</label>
            <input type="email" class="form-control" id="parentemail" name="parentemail" value="">
          </td>
          <td>
            <label for="parentcontactnumber">Contact Number</label>
            <input type="tel" class="form-control" id="parentcontactnumber" name="parentcontactnumber" pattern="[0-9]{10}" maxlength="10" value="">
          </td>
        </tr>
      </table>
    </div>

    <!-- Navigation buttons -->
    <div class="next-btn-container">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    </div>

    <!-- Step indicators -->
    <div style="text-align:center;margin-top:40px;">
      <span class="step"></span>
      <span class="step"></span>
      <span class="step"></span>
      <span class="step"></span>
    </div>
  </form>

  <script>
    // Script to handle tab navigation and step indicators
    var currentTab = 0;
    showTab(currentTab);

    function showTab(n) {
      var tabs = document.getElementsByClassName("tab");
      tabs[n].style.display = "block";
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (tabs.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
      } else {
        document.getElementById("nextBtn").innerHTML = "Next";
      }
      fixStepIndicator(n);
    }

    function nextPrev(n) {
      var tabs = document.getElementsByClassName("tab");
      if (n == 1 && !validateForm()) return false;
      tabs[currentTab].style.display = "none";
      currentTab = currentTab + n;
      if (currentTab >= tabs.length) {
        document.getElementById("regForm").submit();
        return false;
      }
      showTab(currentTab);
    }

    function validateForm() {
      var x, y, i, valid = true;
      x = document.getElementsByClassName("tab");
      y = x[currentTab].getElementsByTagName("input");
      for (i = 0; i < y.length; i++) {
        if (y[i].value == "") {
          y[i].className += " invalid";
          valid = false;
        }
      }
      return valid;
    }

    function fixStepIndicator(n) {
      var steps = document.getElementsByClassName("step");
      for (i = 0; i < steps.length; i++) {
        steps[i].className = steps[i].className.replace(" active", "");
      }
      steps[n].className += " active";
    }
  </script>

</body>

</html>
