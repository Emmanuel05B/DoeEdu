<!DOCTYPE html>
<html> 
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
include(__DIR__ . "/../../common/partials/head.php");
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include(__DIR__ . "/../../common/partials/header.php"); ?>
  <?php include(__DIR__ . "/../../common/partials/mainsidebar.php"); ?>

  <div class="content-wrapper">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <section class="content-header">
      <h1>Study Resources <small>Upload and manage learning materials.</small></h1>
      <ol class="breadcrumb">
        <li><a href="adminindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Resources</li>
      </ol>
    </section>

    <section class="content">
      
      <div class="row">
        <!-- Upload Resource - Left Side -->
        <div class="col-md-6">
          <div class="box box-primary" style="border-top: 3px solid #3c8dbc;">
            <div class="box-header with-border" style="background-color:#f0f8ff;">
              <h3 class="box-title" style="color:#3c8dbc;"><i class="fa fa-upload"></i> Upload New Resource</h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form action="upload_resource.php" method="POST" enctype="multipart/form-data">
                <div class="row">

                  <!-- Title and Subject & Grade side by side -->
                  <div class="col-md-6 form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="E.g. Newton’s Laws Summary" required>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="subject_grade">Subject & Grade</label>
                    <select name="subject_grade" class="form-control" required>
                      <option value="">Select Subject & Grade</option>
                      <!-- Options from PHP will populate here -->
                    </select>
                  </div>

                  <!-- Type of Resource and Choose File side by side -->
                  <div class="col-md-6 form-group">
                    <label for="resource_type">Type of Resource</label>
                    <select name="resource_type" class="form-control" required>
                      <option value="">Select Type</option>
                      <option value="PDF">PDF Document</option>
                      <option value="Image">Image</option>
                      <option value="Slides">Slides (e.g. PPT)</option>
                      <option value="Video">Video</option>
                    </select>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="resource_file">Choose File</label>
                    <input type="file" name="resource_file" class="form-control" required>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="visibility">Visibility</label>
                    <select name="visibility" class="form-control" required>
                      <option value="private">Private (Only assigned classes)</option>
                      <option value="public">Public (All learners can access)</option>
                    </select>
                  </div>

                  <!-- Description -->
                  <div class="col-md-6 form-group">
                    <label for="description">Description / Notes (Optional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief info about the resource"></textarea>
                  </div>

                </div>
                
                  <div class="col-md-12 text-right" style="margin-top: 10px;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Upload Resource</button>
                  </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Bulk Assign Resources to Class - Right Side -->
        <div class="col-md-6">
          <div class="box box-info" style="border-top: 3px solid #00c0ef;">
            <div class="box-header with-border" style="background-color:#d9f0fb;">
              <h3 class="box-title" style="color:#0073b7;">
                <i class="fa fa-tasks"></i> Bulk Assign Resources to Class/Group
              </h3>
            </div>
            <div class="box-body" style="background-color:#ffffff;">
              <form action="assign_resource.php" method="POST">
                <div class="form-group">
                  <label>Select Resources</label>
                  <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9;">
                    <table class="table table-striped" style="margin-bottom: 0;">
                      <thead>
                        <tr>
                          <th style="width: 40px;"></th> <!-- checkbox column -->
                          <th>Title</th>
                          <th>Grade</th>
                          <th>Subject</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><input type="checkbox" name="resourceIds[]" value="1"></td>
                          <td>Newton’s Laws Summary.pdf</td>
                          <td>Grade 12</td>
                          <td>Physical Science</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="resourceIds[]" value="2"></td>
                          <td>Energy Conversion Slide.ppt</td>
                          <td>Grade 10</td>
                          <td>Physical Science</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="resourceIds[]" value="3"></td>
                          <td>Photosynthesis Video.mp4</td>
                          <td>Grade 9</td>
                          <td>Life Sciences</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="resourceIds[]" value="2"></td>
                          <td>Energy Conversion Slide.ppt</td>
                          <td>Grade 10</td>
                          <td>Physical Science</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="resourceIds[]" value="3"></td>
                          <td>Photosynthesis Video.mp4</td>
                          <td>Grade 9</td>
                          <td>Life Sciences</td>
                        </tr>
                        
                        <!-- Add more rows dynamically -->
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="form-group row" style="margin-top: 15px;">
                  <div class="col-xs-8">
                    <select name="classId" id="classId" class="form-control" required>
                      <option value="">-- Select a Class/Group --</option>
                      <option value="A">Grade 12 - Physical Science</option>
                      <option value="B">Grade 9 - Life Sciences</option>
                      <option value="C">Grade 10 - Chemistry Club</option>
                    </select>
                  </div>
                  <div class="col-xs-4">
                    <button type="submit" class="btn btn-info btn-block" style="margin-top: 0;">
                      <i class="fa fa-check"></i> Assign Selected
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>

      <!-- Uploaded Resources -->
      <div class="box box-solid" style="border-top: 3px solid #605ca8;">
        <div class="box-header with-border" style="background-color:#f3edff;">
          <h3 class="box-title" style="color:#605ca8;"><i class="fa fa-folder-open"></i> Your Uploaded Resources</h3>
        </div>
        <div class="box-body" style="background-color:#ffffff;">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="resourceTable">
              <thead style="background-color:#e6e0fa; color:#333;">
                <tr>
                  <th>Title</th>
                  <th>Type</th>
                  <th>Subject</th>
                  <th>Grade</th>
                  <th style="width:130px;">Actions</th>
                  <th>Uploaded At</th>
                </tr>
              </thead>
              <tbody>
                <!-- Dummy Resource 1 -->
                <tr>
                  <td>Newton’s Laws Summary</td>
                  <td>PDF</td>
                  <td>Physical Science</td>
                  <td>Grade 10</td>
                  <td>
                    <a href="../uploads/resources/newton_laws.pdf" class="btn btn-xs btn-primary" title="Download" download>
                      <i class="fa fa-download"></i>
                    </a>
                    <a href="delete_resource.php?id=1" class="btn btn-xs btn-danger" title="Delete" onclick="return confirm('Delete this resource?')">
                      <i class="fa fa-trash"></i>
                    </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" title="Assign Resource">
                        <i class="fa fa-link"></i> Assign <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="#">Grade 10 - Group A</a></li>
                        <li><a href="#">Grade 10 - Group B</a></li>
                      </ul>
                    </div>
                  </td>
                  <td>2025-07-28 14:25</td>

                </tr>

                <!-- Dummy Resource 2 -->
                <tr>
                  <td>Cell Structure Diagram</td>
                  <td>Image</td>
                  <td>Life Sciences</td>
                  <td>Grade 11</td>
                  <td>
                    <a href="../uploads/resources/cell_structure.jpg" class="btn btn-xs btn-primary" title="Download" download>
                      <i class="fa fa-download"></i>
                    </a>
                    <a href="delete_resource.php?id=2" class="btn btn-xs btn-danger" title="Delete" onclick="return confirm('Delete this resource?')">
                      <i class="fa fa-trash"></i>
                    </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" title="Assign Resource">
                        <i class="fa fa-link"></i> Assign <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="#">Grade 11 - Group B</a></li>
                        <li><a href="#">Grade 11 - Group C</a></li>
                      </ul>
                    </div>
                  </td>
                  <td>2025-07-27 11:05</td>

                </tr>
                <!-- Dummy Resource 3 -->
                <tr>
                  <td>Trigonometry Notes</td>
                  <td>Slides</td>
                  <td>Mathematics</td>
                  <td>Grade 12</td>
                  <td>
                    <a href="../uploads/resources/trigonometry_slides.pptx" class="btn btn-xs btn-primary" title="Download" download>
                      <i class="fa fa-download"></i>
                    </a>
                    <a href="delete_resource.php?id=3" class="btn btn-xs btn-danger" title="Delete" onclick="return confirm('Delete this resource?')">
                      <i class="fa fa-trash"></i>
                    </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" title="Assign Resource">
                        <i class="fa fa-link"></i> Assign <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="#">Grade 12 - Group A</a></li>
                        <li><a href="#">Grade 12 - Group D</a></li>
                      </ul>
                    </div>
                  </td>
                  <td>2025-07-25 08:40</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>




    </section>

  </div>

  <div class="control-sidebar-bg"></div>
</div>

<!-- Scripts -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../../common/dist/js/demo.js"></script> 

<script>
  $(function () {
    $('#resourceTable').DataTable();
  });
</script>

</body>
</html>
