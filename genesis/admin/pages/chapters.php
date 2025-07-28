<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
  exit();
}
?>

<?php include(__DIR__ . "/../../common/partials/head.php");  ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <?php include('../../partials/connect.php'); ?> 

      <h1>Select a Chapter <small>Chapters</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Chapters</li>
      </ol>
    </section>

        <?php
        $subjectId = intval($_GET['subject']);  // Subject ID
        $grade = intval($_GET['grade']);        // Grade
        $group = $_GET['group'];        // Group

        // Check the subject and grade combination
        if ($subjectId == 1 && $grade == 10) {
        ?>
            <!-- Grade 10 Mathematics Control panel HTML goes here -->
                 <section class="content">

                    <div class="row">
                        <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header">
                            <i class="fa fa-edit"></i>

                            <h3 class="box-title">Grade 10 Mathematics</h3>
                            </div>
                            <div class="box-body pad table-responsive">
                            <p>Select chapter</p>
                              <table class="table table-bordered text-center">
                                <tr>
                                  <th>Term 1</th>
                                  <th>Term 2</th>
                                  <th>Term 3</th>
                                  <th>Term 4/Revision</th>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Algebraic Expressions" class="btn btn-block btn-info btn-lg">Algebraic expressions</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Trigonometry" class="btn btn-block btn-warning btn-lg">Trigonometry</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Finance and Growth" class="btn btn-block btn-success btn-lg">Finance and growth</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Measurement" class="btn btn-block btn-primary btn-lg">Measurement</a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Exponents" class="btn btn-block btn-info btn-lg">Exponents</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Functions" class="btn btn-block btn-warning btn-lg">Functions</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Statistics" class="btn btn-block btn-success btn-lg">Statistics</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Probability" class="btn btn-block btn-primary btn-lg">Probability</a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Number Patterns" class="btn btn-block btn-info btn-lg">Number patterns</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Euclidean Geometry" class="btn btn-block btn-warning btn-lg">Euclidean geometry</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Trigonometry" class="btn btn-block btn-success btn-lg">Trigonometry</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=43" class="btn btn-block btn-primary btn-lg">...</a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Equations and Inequalities" class="btn btn-block btn-info btn-lg">Equations and inequalities</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Analytical Geometry" class="btn btn-block btn-warning btn-lg">Analytical geometry</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Euclidean Geometry" class="btn btn-block btn-success btn-lg">Euclidean geometry</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=44" class="btn btn-block btn-primary btn-lg">...</a>
                                  </td>
                                </tr>
                              </table>

                            </div>
                            <!-- /.box -->
                        </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /. row -->
                </section>

        <?php
        } else if ($subjectId == 4 && $grade == 10) {
        ?>
            <!-- Grade 10 Physical Sciences Control panel HTML goes here -->
                  <section class="content">

                      <div class="row">
                        <div class="col-md-12">
                          <div class="box box-primary">
                            <div class="box-header">
                              <i class="fa fa-edit"></i>

                              <h3 class="box-title">Grade 10 Physical Sciences</h3>
                            </div>
                            <div class="box-body pad table-responsive">
                              <p>Select chapter</p>
                              <table class="table table-bordered text-center">
                                <tr>
                                  <th>Term 1</th>
                                  <th>Term 2</th>
                                  <th>Term 3</th>
                                  <th>Term 4/Revision</th>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Classification Of Matter" class="btn btn-block btn-info btn-lg">Classification of matter</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Chemical Bonding" class="btn btn-block btn-warning btn-lg">Chemical bonding</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Sound" class="btn btn-block btn-success btn-lg">Sound</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Representing Chemical Change" class="btn btn-block btn-primary btn-lg">Representing chemical change</a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=States of matter and Kinetic Theory" class="btn btn-block btn-info btn-lg">States of matter and kinetic theory</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Transverse Pulses" class="btn btn-block btn-warning btn-lg">Transverse pulses</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electromagnetic Radiation" class="btn btn-block btn-success btn-lg">Electromagnetic radiation</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Magnetism" class="btn btn-block btn-primary btn-lg">Magnetism</a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=The Atom" class="btn btn-block btn-info btn-lg">The atom</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Transverse Waves" class="btn btn-block btn-warning btn-lg">Transverse waves</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Particles That Make Up Substances" class="btn btn-block btn-success btn-lg">Particles that make up substances</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electrostatics" class="btn btn-block btn-primary btn-lg">Electrostatics</a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=The Periodic Table" class="btn btn-block btn-info btn-lg">The periodic table</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Longitudinal Waves" class="btn btn-block btn-warning btn-lg">Longitudinal waves</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Physical and Chemical_Change" class="btn btn-block btn-success btn-lg">Physical and chemical change</a>
                                  </td>
                                  <td>
                                    <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electric Circuits" class="btn btn-block btn-primary btn-lg">Electric circuits</a>
                                  </td>
                                </tr>
                              </table>

                            </div>
                            <!-- /.box -->
                          </div>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /. row -->
                  </section>

        <?php
        } else if ($subjectId == 2 && $grade == 11) {
        ?>
            <!-- Grade 11 Mathematics Control panel HTML goes here -->
                <section class="content">

                  <div class="row">
                    <div class="col-md-12">
                      <div class="box box-primary">
                        <div class="box-header">
                          <i class="fa fa-edit"></i>

                          <h3 class="box-title">Grade 11 Mathematics</h3>
                        </div>
                        <div class="box-body pad table-responsive">
                          <p>Select chapter</p>
                          <table class="table table-bordered text-center">
                            <tr>
                              <th>Term 1</th>
                              <th>Term 2</th>
                              <th>Term 3</th>
                              <th>Term 4/Revision</th>
                            </tr>
                            <tr>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Exponents and Surds" class="btn btn-block btn-info btn-lg">Exponents and surds</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Analytical Geometry" class="btn btn-block btn-warning btn-lg">Analytical geometry</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Measurement" class="btn btn-block btn-success btn-lg">Measurement</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Probability" class="btn btn-block btn-primary btn-lg">Probability</a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Equations and Inequalities" class="btn btn-block btn-info btn-lg">Equations and inequalities</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Functions" class="btn btn-block btn-warning btn-lg">Functions</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Euclidean Geometry" class="btn btn-block btn-success btn-lg">Euclidean geometry</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Statistics" class="btn btn-block btn-primary btn-lg">Statistics</a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Number Patterns" class="btn btn-block btn-info btn-lg">Number patterns</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Trigonometry" class="btn btn-block btn-warning btn-lg">Trigonometry</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Finance Growth and Decay" class="btn btn-block btn-success btn-lg">Finance growth and decay</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=43" class="btn btn-block btn-primary btn-lg">...</a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=14" class="btn btn-block btn-info btn-lg">...</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=24" class="btn btn-block btn-warning btn-lg">...</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=34" class="btn btn-block btn-success btn-lg">...</a>
                              </td>
                              <td>
                                <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=44" class="btn btn-block btn-primary btn-lg">...</a>
                              </td>
                            </tr>
                          </table>

                        </div>
                        <!-- /.box -->
                      </div>
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /. row -->
                </section>

        <?php
        } else if ($subjectId == 5 && $grade == 11) {
        ?>
            <!-- Grade 11 Physical Sciences Control panel HTML goes here -->
                  <section class="content">

                    <div class="row">
                      <div class="col-md-12">
                        <div class="box box-primary">
                          <div class="box-header">
                            <i class="fa fa-edit"></i>

                            <h3 class="box-title">Grade 11 Physical Sciences</h3>
                          </div>
                          <div class="box-body pad table-responsive">
                            <p>Select chapter</p>
                            <table class="table table-bordered text-center">
                              <tr>
                                <th>Term 1</th>
                                <th>Term 2</th>
                                <th>Term 3</th>
                                <th>Term 4/Revision</th>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Vectors In 2 dimensions" class="btn btn-block btn-info btn-lg">Vectors in two dimensions</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Intermolecular Forces" class="btn btn-block btn-warning btn-lg">Intermolecular forces</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Ideal Gases" class="btn btn-block btn-success btn-lg">Ideal gases</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electromagnetism" class="btn btn-block btn-primary btn-lg">Electromagnetism</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Newtons Laws" class="btn btn-block btn-info btn-lg">Newtons laws</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Geometrical Optics" class="btn btn-block btn-warning btn-lg">Geometrical optics</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Quantitative Aspects Of Chemical Change" class="btn btn-block btn-success btn-lg">Quantitative aspects of chemical change</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electric Circuits" class="btn btn-block btn-primary btn-lg">Electric circuits</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Atomic Combinations" class="btn btn-block btn-info btn-lg">Atomic combinations</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=2d and 3d wavefronts" class="btn btn-block btn-warning btn-lg">2d and 3d wavefronts</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electrostatics" class="btn btn-block btn-success btn-lg">Electrostatics</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Energy and Chemical Change" class="btn btn-block btn-primary btn-lg">Energy and chemical change</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=14" class="btn btn-block btn-info btn-lg">a</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=24" class="btn btn-block btn-warning btn-lg">b</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Types Of Reactions" class="btn btn-block btn-success btn-lg">Types of reactions</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=The Lithosphere" class="btn btn-block btn-primary btn-lg">The lithosphere</a>
                                </td>
                              </tr>
                            </table>

                          </div>
                          <!-- /.box -->
                        </div>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /. row -->
                  </section>
        <?php
        } else if ($subjectId == 3 && $grade == 12) {
        ?>
            <!-- Grade 12 Mathematics Control panel HTML goes here -->
                  <section class="content">

                    <div class="row">
                      <div class="col-md-12">
                        <div class="box box-primary">
                          <div class="box-header">
                            <i class="fa fa-edit"></i>

                            <h3 class="box-title">Grade 12 Mathematics</h3>
                          </div>
                          <div class="box-body pad table-responsive">
                            <p>Select chapter</p>
                            <table class="table table-bordered text-center">
                              <tr>
                                <th>Term 1</th>
                                <th>Term 2</th>
                                <th>Term 3</th>
                                <th>Term 4/Revision</th>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Sequences and Series" class="btn btn-block btn-info btn-lg">Sequences & Series</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Financial Mathematics" class="btn btn-block btn-warning btn-lg">Financial Mathematics</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Statistics" class="btn btn-block btn-success btn-lg">Statistics</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=3D_Trigonometry" class="btn btn-block btn-primary btn-lg">3D Trigonometry...</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Trigonometry" class="btn btn-block btn-info btn-lg">Trigonometry</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Differential Calculus" class="btn btn-block btn-warning btn-lg">Differential Calculus</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Probability" class="btn btn-block btn-success btn-lg">Probability</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Measurement" class="btn btn-block btn-primary btn-lg">Measurement</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Functions" class="btn btn-block btn-info btn-lg">Functions</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Analytical Geometry" class="btn btn-block btn-warning btn-lg">Analytical Geometry</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Euclidean Geometry" class="btn btn-block btn-success btn-lg">Euclidean Geometry</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Calculas Application" class="btn btn-block btn-primary btn-lg">Calculas Application...</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Inverse_Graphs_and_Log_Functions" class="btn btn-block btn-info btn-lg">Inverse Graphs and Log Functions</a>
                                </td>
                                <td>
                                  <a href="#recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=24" class="btn btn-block btn-warning btn-lg">...</a>
                                </td>
                                <td>
                                  <a href="#recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=34" class="btn btn-block btn-success btn-lg">...</a>
                                </td>
                                <td>
                                  <a href="#recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=44" class="btn btn-block btn-primary btn-lg">4</a>
                                </td>
                              </tr>
                            </table>

                          </div>
                          <!-- /.box -->
                        </div>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /. row -->
                  </section>
        <?php
        } else if ($subjectId == 6 && $grade == 12) {
        ?>
            <!-- Grade 12 Physical Sciences Control panel HTML goes here -->
                  <section class="content">

                    <div class="row">
                      <div class="col-md-12">
                        <div class="box box-primary">
                          <div class="box-header">
                            <i class="fa fa-edit"></i>

                            <h3 class="box-title">Grade 12 Physical Sciences</h3>
                          </div>
                          <div class="box-body pad table-responsive">
                            <p>Select chapter</p>
                            <table class="table table-bordered text-center">
                              <tr>
                                <th>Term 1</th>
                                <th>Term 2</th>
                                <th>Term 3</th>
                                <th>Term 4/Revision</th>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Momentum and Impulse" class="btn btn-block btn-info btn-lg">Momentum and impulse</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Work Energy and Power" class="btn btn-block btn-warning btn-lg">Work energy and power</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Chemical Equilibrium" class="btn btn-block btn-success btn-lg">Chemical equilibrium</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Optical Phenomena and Properties Of Matter" class="btn btn-block btn-primary btn-lg">Optical phenomena and properties of matter</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Vertical Projectile" class="btn btn-block btn-info btn-lg">Vertical projectile motion in one dimension</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Doppler Effect" class="btn btn-block btn-warning btn-lg">Doppler effect</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Acids and Bases" class="btn btn-block btn-success btn-lg">Acids and bases</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electrochemical Reactions" class="btn btn-block btn-primary btn-lg">Electrochemical reactions</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Organic Chemistry" class="btn btn-block btn-info btn-lg">Organic Chemistry</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Rate and Extent of  Reaction" class="btn btn-block btn-warning btn-lg">Rate and extent of reaction</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=Electric Circuits" class="btn btn-block btn-success btn-lg">Electric circuits</a>
                                </td>
                                <td>
                                  <a href="recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=The Chemical Industry" class="btn btn-block btn-primary btn-lg">The chemical industry</a>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a href="#recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=14" class="btn btn-block btn-info btn-lg">a</a>
                                </td>
                                <td>
                                  <a href="#recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=24" class="btn btn-block btn-warning btn-lg">b</a>
                                </td>
                                <td>
                                  <a href="#recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=34" class="btn btn-block btn-success btn-lg">c</a>
                                </td>
                                <td>
                                  <a href="#recmodal.php?gra=<?= $grade ?>&sub=<?= $subjectId ?>&group=<?= $group ?>&cha=44" class="btn btn-block btn-primary btn-lg">d</a>
                                </td>
                              </tr>
                            </table>

                          </div>
                          <!-- /.box -->
                        </div>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /. row -->
                  </section>
        <?php
        } else {
        ?>
            <h1>Unknown Subject or Grade</h1>
        <?php
        }
        ?>


  </div>


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../common/dist/js/demo.js"></script> 
</body>
</html>
