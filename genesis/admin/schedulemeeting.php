<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../common/login.php");
    exit();
}
?>

<?php include("adminpartials/head.php"); ?>

<style>
    /* Tabs */
    .nav-pills .nav-link {
        text-align: center;
        color: #333;
    }

    .nav-pills .nav-link.parent {
        background-color: #dfe6e9;
        
    }

    .content {
        background-color: white;
        margin-top: 20px;
        margin-left: 100px;
        margin-right: 100px;
    }

    .pos {
        margin-top: 50px;
        margin-left: 70px;
        margin-right: 70px;
    }


</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php include("adminpartials/header.php"); ?>
        <!-- Left side column. contains the logo and sidebar -->
        <?php include("adminpartials/mainsidebar.php"); ?>
        <!-- Content Wrapper. Contains page content ##start -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content">
                <div class="container-fluid">

                    <div class="oder">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link parent" href="#parent" data-toggle="tab" style="border: 2px solid gray;">Announce Parents Meeting</a></li>
                                    <li class="nav-item"><a class="nav-link teacher" href="#teacher" data-toggle="tab" style="border: 2px solid gray;">Announce Teachers Meeting</a></li>
                                    <li class="nav-item"><a class="nav-link parentteacher" href="#parentteacher" data-toggle="tab" style="border: 2px solid gray;">Announce Meeting for both Parents and Teachers</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- /.tab-pane -->
                                    <div class="active tab-pane" id="parent">
                                        <!-- email must start here -->
                                        <div class="pos">
                                            <!-- quick email widget -->
                                            <div class="box box-info">
                                                <div class="box-header" style="text-align: center;">
                                                    <i class="fa fa-envelope"></i>
                                                    <h3 class="box-title" >Send Announcement To All The <span style="color: red;">Parents</span></h3>
                                                </div>
                                                <div class="box-body">
                                                    <form action="handler/parentmail.php" method="post">
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="emailto" placeholder="Email to:">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="subject" placeholder="Subject">
                                                        </div>
                                                        <div>
                                                            <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                        <input type="submit" value="Submit" name="btnsendP">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- email must be here -->
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="teacher">
                                    <!-- email must start here -->
                                    <div class="pos">
                                            <!-- quick email widget -->
                                            <div class="box box-info">
                                                <div class="box-header" style="text-align: center;">
                                                    <i class="fa fa-envelope"></i>
                                                    <h3 class="box-title" >Send Announcement To All The <span style="color: red;">Teachers</span></h3>
                                                </div>
                                                <div class="box-body">
                                                    <form action="handler/teachermail.php" method="post">
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="emailto" placeholder="Email to:">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="subject" placeholder="Subject">
                                                        </div>
                                                        <div>
                                                            <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                        <input type="submit" value="Submit" name="btnsendT">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- email must be here -->
                                        
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="parentteacher">
                                    <!-- email must start here -->
                                    <div class="pos">
                                            <!-- quick email widget -->
                                            <div class="box box-info">
                                                <div class="box-header" style="text-align: center;">
                                                    <i class="fa fa-envelope"></i>
                                                    <h3 class="box-title" >Send Announcement To <span style="color: red;">Parents & Teachers</span></h3>
                                                </div>
                                                <div class="box-body">
                                                    <form action="handler/parentteachermail.php" method="post">
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="emailto" placeholder="Email to:">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="subject" placeholder="Subject">
                                                        </div>
                                                        <div>
                                                            <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                        </div>
                                                        <input type="submit" value="Submit" name="btnsendPT">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- email must be here -->
                                    </div>
                                   
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                </div>
            </section>
            <!-- ./col 555555555555555555555-->
        </div> <!-- /. ##start -->

        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <?php include("adminpartials/queries.php"); ?>
    <script src="dist/js/demo.js"></script>
</body>
</html>