<?php 
session_start();  // Ensure session is started before any output

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}

include("adminpartials/head.php");

?>

<!DOCTYPE html>
<html>
<?php include("adminpartials/head.php"); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

<style>
    .registerbtn {
        background-color: #2d98da;
        color: white;
        padding: 15px 15px;
        margin: 2px;
        align: center;
        border: none;
        cursor: pointer;
        width: 100%;
        height: 50px;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity: 1;
    }

    .content {
        background-color: white;
        margin-top: 20px;
        margin-left: 80px;
        margin-right: 80px;
    }

    .pos {
        margin-bottom: 30px;
        text-align: center;
    }

    .subject-table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .subject-table th,
    .subject-table td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .subject-name {
        font-weight: bold;
    }

    .subject-options {
        text-align: center;
    }

    .subject-options input {
        margin: 0 5px;
    }
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include("adminpartials/header.php") ?>
        <?php include("adminpartials/mainsidebar.php") ?>

        <div class="content-wrapper">
            <section class="content">

                <form action=".....php" method="POST">
                    <div class="pos">
                        <h4>Registering Learner</h4>
                    </div>
                  
                    <!-- Performance -->
                    <h4>Select current level and target level:</h4>
                    <table class="subject-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Current Level (1 - 7)</th>
                                <th>Target Level (3 - 7)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="subject-name">Mathematics</td>
                                <td class="subject-options">
                                    <select name="subjects[maths][current]" class="form-control">
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
                                <td class="subject-options">
                                    <select name="subjects[maths][target]" class="form-control">
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
                                <td class="subject-name">Physical Sciences</td>
                                <td class="subject-options">
                                    <select name="subjects[physics][current]" class="form-control">
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
                                <td class="subject-options">
                                    <select name="subjects[physics][target]" class="form-control">
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
                        </tbody>
                    </table><br>

                    <!-- Parent Info -->
                    <h4>Parent Info:</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="parentname">First Names</label>
                            <input type="text" class="form-control" id="parentname" name="parentname" placeholder="Names" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="parentsurname">Surname</label>
                            <input type="text" class="form-control" id="parentsurname" name="parentsurname" placeholder="Surname" value="">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="parentemail">Email</label>
                            <input type="email" class="form-control" id="parentemail" name="parentemail" placeholder="Email" value="">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-group col-md-6">
                                    <label for="parentcontactnumber">Contact Number (10 digits):</label>
                                    <input type="tel" class="form-control" id="parentcontactnumber" name="parentcontactnumber" pattern="[0-9]{10}" maxlength="10" value="" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="title">Title</label>
                                    <select type="text" id="title" name="title" class="form-control">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                        <option value="Prof">Prof</option>
                                        <option value="Rev">Rev</option>
                                        <option value="Sir">Sir</option>
                                        <option value="Lord">Lord</option>
                                        <option value="Capt">Capt</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="registerbtn" name="reg1">Register Learner</button>
                </form>

            </section>
        </div>
    </div>

    <?php include("adminpartials/queries.php"); ?>
    <script src="dist/js/demo.js"></script>
</body>
</html>
