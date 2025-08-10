<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}
include(__DIR__ . "/../../common/partials/head.php");
include(__DIR__ . "/../../partials/connect.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Practice Questions Setup <small>Select details before creating questions</small></h1>
            <ol class="breadcrumb">
                <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Practice Questions Setup</li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
                <div class="box-header with-border" style="background-color:#f0f8ff;">
                    <h3 class="box-title" style="color:#3c8dbc;">
                        <i class="fa fa-cogs"></i> Select Details
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <!-- Left side: Smaller form -->
                        <div class="col-md-6">
                            <form action="create_questions.php" method="POST">
                                <div class="form-group">
                                    <label>Grade</label>
                                    <select name="grade" class="form-control" required>
                                        <option value="">Select Grade</option>
                                        <option value="Grade 8">Grade 8</option>
                                        <option value="Grade 9">Grade 9</option>
                                        <option value="Grade 10">Grade 10</option>
                                        <option value="Grade 11">Grade 11</option>
                                        <option value="Grade 12">Grade 12</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Subject</label>
                                    <select name="subject" class="form-control" required>
                                        <option value="">Select Subject</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="Physical Science">Physical Science</option>
                                        <option value="Life Sciences">Life Sciences</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Chapter</label>
                                    <input type="text" name="chapter" class="form-control" placeholder="Enter chapter name" required>
                                </div>

                                <div class="form-group">
                                    <label>Level</label>
                                    <select name="level" class="form-control" required>
                                        <option value="">Select Level</option>
                                        <option value="Easy">Easy</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-arrow-right"></i> Continue
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right side: Info panel -->
                        <div class="col-md-6">
                            <div class="callout callout-info">
                                <h4><i class="fa fa-info-circle"></i> About This Page</h4>
                                <p>This setup page allows you to define the key details for your practice questions before creating them. Once you select a grade, subject, chapter, and difficulty level, you can proceed to add the questions.</p>
                            </div>

                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Tips for Creating Good Questions</h3>
                                </div>
                                <div class="box-body">
                                    <ul>
                                        <li>Keep the question clear and concise.</li>
                                        <li>Match difficulty level to learners’ capabilities.</li>
                                        <li>Use relevant and up-to-date examples.</li>
                                        <li>Attach diagrams or supporting files when needed.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div class="control-sidebar-bg"></div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>
</body>
</html>
