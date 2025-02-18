<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Registration</title>
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

        /* Table styling */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px; /* Adds space between rows */
    margin-top: 20px;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border: 1px solid #ddd; /* Adds subtle borders around cells */
    border-radius: 6px; /* Rounded corners for each cell */
}

th {
    background-color: #00a8ff;
    color: white;
    font-size: 16px;
    font-weight: bold;
}

td {
    background-color: #f9f9f9;
}

td input[type="radio"] {
    margin-right: 10px; /* Adds space between radio buttons */
}

td select, td input[type="text"], td input[type="email"], td input[type="tel"], td input[type="time"] {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
}

td input[type="radio"]:checked {
    background-color: #00a8ff;
    border-color: #00a8ff;
}

td input[type="radio"]:not(:checked) {
    background-color: #f9f9f9;
    border-color: #ccc;
}

td select:focus, td input[type="text"]:focus, td input[type="email"]:focus, td input[type="tel"]:focus, td input[type="time"]:focus {
    border-color: #00a8ff; /* Highlight border when focused */
    outline: none;
}

td input[type="radio"], td input[type="text"], td input[type="email"], td input[type="tel"], td select {
    box-sizing: border-box;
}

/* Add hover effect for table rows */
tr:hover td {
    background-color: #e6f7ff; /* Light blue background when hovering over rows */
}

/* Additional styling for the buttons */
button {
    background-color: #00a8ff;
    color: white;
    padding: 12px 25px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    opacity: 0.8;
}

button:active {
    transform: scale(0.98);
}

/* Styling for the form input fields and selects */
input[type="radio"], input[type="text"], input[type="email"], input[type="tel"], select {
    box-sizing: border-box;
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

label {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Form containers for spacing */
.tab {
    padding: 20px;
}


 

        .next-btn-container {
            text-align: right;
            margin-top: 20px;
        }
        .top-right-image {
            position: absolute;
            top: 0;
            right: 0;
            max-height: 130px; /* Adjust the size of the image */
            margin-top: 120px; /* Adjust the space from the top */
            margin-right: 260px; /* Adjust the space from the right */
        }
    </style>
</head>
<body>

<form id="regForm" action="addlearnerhandler.php" method="POST" enctype="multipart/form-data">
    <h1>Registration:

     <!-- Adding the image -->
     <img src="images/doe.png" alt="Your Image" class="top-right-image">

    </h1>

    
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
                        <select id="grade" name="grade" class="form-control" required>
                         <option value="" disabled selected>Select Grade</option>
                         <option value="10">10</option>
                         <option value="11">11</option>
                         <option value="12">12</option>
                        </select>
                    </div>

                    <div style="flex: 1; margin-left: 20px;">
                        <label for="knockout_time">Knockout Time</label>
                        <input type="time" class="form-control" id="knockout_time" name="knockout_time" required>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- 2nd tab (Subject Selection and Duration) -->
    <div class="tab">
        <h4>Select Subjects and Duration:</h4>
        <table>
            <tr>
                <th>Subject</th>
                <th>Not Registered</th>
                <th>3 Months</th>
                <th>6 Months</th>
                <th>12 Months</th>
            </tr>
            <tr>
                <td>Mathematics</td>
                <td><input type="radio" name="maths" value="0" checked></td>
                <td><input type="radio" name="maths" value="450.00"></td>
                <td><input type="radio" name="maths" value="750.00"></td>
                <td><input type="radio" name="maths" value="1199.00"></td>
            </tr>
            <tr>
                <td>Physical Sciences</td>
                <td><input type="radio" name="physics" value="0" checked></td>
                <td><input type="radio" name="physics" value="450.00"></td>
                <td><input type="radio" name="physics" value="750.00"></td>
                <td><input type="radio" name="physics" value="1199.00"></td>
            </tr>
        </table>
    </div>

    <!-- 3rd tab (Setting Current and Target Levels) -->
    <div class="tab">
        <h4>Select Current and Target Levels:</h4>
        <table>
            <tr>
                <th>Subject</th>
                <th>Current Level (1 - 7)</th>
                <th>Target Level (3 - 7)</th>
            </tr>
            <tr>
                <td>Mathematics</td>
                <td>
                    <select name="math-current" class="form-control" required>
                        <option value="">Select Level</option>
                        <option value="100">none</option>
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
                    <select name="math-target" class="form-control" required>
                        <option value="">Select Target</option>
                        <option value="100">none</option>
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
                    <select name="physics-current" class="form-control" required>
                        <option value="">Select Level</option>
                        <option value="100">none</option>
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
                    <select name="physics-target" class="form-control" required>
                        <option value="">Select Target</option>
                        <option value="100">none</option>
                        <option value="1">1</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>

    <!-- 4th tab (Parent Info) -->
    <div class="tab">
        <h4>Parent Info:</h4>
        <table>
            <tr>
                <td>
                    <label for="parentname">First Name</label>
                    <input type="text" class="form-control" id="parentname" name="parentname" placeholder="Enter parent first name" required>
                </td>
                <td>
                    <label for="parentsurname">Surname</label>
                    <input type="text" class="form-control" id="parentsurname" name="parentsurname" placeholder="Enter parent surname" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="parentemail">Email</label>
                    <input type="email" class="form-control" id="parentemail" name="parentemail" placeholder="Enter parent email" required>
                </td>
                <td>
                    <label for="parentcontact">Contact Number</label>
                    <input type="tel" class="form-control" id="parentcontact" name="parentcontact" pattern="[0-9]{10}" maxlength="10" required>
                </td>
                <td>
                    <label for="parenttitle">Title</label>
                    <select class="form-control" id="parenttitle" name="parenttitle" required>
                        <option value="">Select Title</option>
                        <option value="Mr">Mr.</option>
                        <option value="Mrs">Mrs.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Dr">Dr.</option>
                        <option value="Prof">Prof.</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>

    <!-- Navigation Buttons -->
    <div class="next-btn-container">
        <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
        <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    </div>

    <!-- Step Indicator -->
    <div style="text-align:center;margin-top:40px;">
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
    </div>
</form>

<script>
// JavaScript to handle form navigation and validation

var currentTab = 0;
showTab(currentTab);

function showTab(n) {
    var tabs = document.getElementsByClassName("tab");
    tabs[n].style.display = "block";
    
    if (n === 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    
    if (n === (tabs.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }

    fixStepIndicator(n);
}

function nextPrev(n) {
    var tabs = document.getElementsByClassName("tab");

    // Only proceed if the form is valid
    if (n === 1 && !validateForm()) return false;

    tabs[currentTab].style.display = "none";
    currentTab = currentTab + n;

    if (currentTab >= tabs.length) {
        document.getElementById("regForm").submit();
        return false;
    }

    showTab(currentTab);
}

function validateForm() {
    var tabs, inputs, selects, i, valid = true;

    tabs = document.getElementsByClassName("tab");
    inputs = tabs[currentTab].getElementsByTagName("input");
    selects = tabs[currentTab].getElementsByTagName("select");

    // Validate input elements
    for (i = 0; i < inputs.length; i++) {
        if (inputs[i].type !== "radio" && (inputs[i].value === "" || (inputs[i].type === "radio" && !inputs[i].checked))) {
            inputs[i].style.border = "1px solid red";
            valid = false;
        } else {
            inputs[i].style.border = "";
        }
    }

    // Validate select elements
    for (i = 0; i < selects.length; i++) {
        if (selects[i].value === "" || selects[i].value === "0") {
            selects[i].style.border = "1px solid red";
            valid = false;
        } else {
            selects[i].style.border = "";
        }
    }

    return valid;
}

function fixStepIndicator(n) {
    var steps = document.getElementsByClassName("step");
    
    for (var i = 0; i < steps.length; i++) {
        steps[i].classList.remove("active");
        steps[i].classList.remove("finish");
    }

    steps[n].classList.add("active");
    if (n > 0) steps[n - 1].classList.add("finish");
}
</script>

</body>
</html>
