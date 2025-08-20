            <?php
                include('../../partials/connect.php');

                $userId = $_SESSION['user_id'];  //for looged in teacher


                $usql = "SELECT * FROM users WHERE Id = $userId" ;
                $Teacherresults = $connect->query($usql);
                $Teacherresultsfinal = $Teacherresults->fetch_assoc();  

               /*
                $sql = "SELECT COUNT(*) as count FROM notices WHERE IsOpened = 0";
                // Execute the query
                $result = $connect->query($sql);
                $messagesrow = $result->fetch_assoc();
               */
            ?>

  <header class="main-header">
    <!-- Logo -->
    <a href="adminindex.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Click</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lgd"><b>DoE_Genesis </b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        
        <span class="logo-lg"><b>Distributors Of Education </b></span>

      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->

          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">25</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 25 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">

                <?php
                  
                 // $sql = "SELECT * FROM tutors";  //comeback for condition
                  $sql = "
                    SELECT 
                        t.TutorId, u.Name, u.Surname, u.Email, u.Contact, u.Gender, t.Availability, t.ProfilePicture, 
                        GROUP_CONCAT(DISTINCT s.SubjectName SEPARATOR ', ') AS Subjects
                    FROM tutors t
                    JOIN users u ON t.TutorId = u.Id
                    LEFT JOIN tutorsubject ts ON t.TutorId = ts.TutorId
                    LEFT JOIN subjects s ON ts.SubjectId = s.SubjectId
                    GROUP BY t.TutorId
                ";
                  $results = $connect->query($sql);
                  while($final = $results->fetch_assoc()) { ?>

                    <?php 
                    /*
                    $currentTime = time();
                    $recievedtime = strtotime($final['CreatedAt']);
                    $timepast = $currentTime - $recievedtime;
                    
                    $inMinutes = floor($timepast /60);  //floor.. cuts the milliseconds
                    $inHours = floor($timepast /3660);
                    $inDays = floor($timepast /86400);
                    $inMonths = floor($timepast /(30 * 86400));


                    if ($timepast < 60) {
                        $_SESSION['elapsed'] = $timepast . ' seconds';
                    } elseif ($timepast < 3600) {
                        $_SESSION['elapsed'] = $inMinutes . ' minute/s'; 
                    } elseif ($timepast < 86400) {
                        $_SESSION['elapsed'] = $inHours . ' hour/s';
                    } elseif ($timepast < 2592000) {
                        $_SESSION['elapsed'] = $inDays . ' day/s'; 
                    } else {
                        $_SESSION['elapsed'] = $inMonths . ' month/s'; 
                    }
                    */
                    ?>

                  <li><!-- start message -->
                    <a href="mread-mail.php?id=<?php echo $final['Email'];?>">
                      <div class="pull-left">
                        <img src="<?= !empty($tutor['ProfilePicture']) ? '' . htmlspecialchars($tutor['ProfilePicture']) : '../uploads/doe.jpg' ?>"class="img-circle" alt="User Image">

                      </div>
                      <h4>
                        <?php echo $final['Name'];?> <!--sender Name -->
                   
                        <?php
                        if (isset($_SESSION['elapsed'])) {
                            echo '<small><i class="fa fa-clock-o"></i>' . $_SESSION['elapsed'] . '</small>';
                            unset($_SESSION['elapsed']);
                        }
                        ?>
            

                      </h4>
                      <p><?php echo $final['Surname'];?></p>  <!--message/title -->
                    </a>
                  </li>
                  <!-- end message -->
                  <?php } ?>
                  
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->

          <?php

                $sql = "SELECT COUNT(*) as countnotices FROM users";
                // Execute the query
                $result = $connect->query($sql);
                $noticesrow = $result->fetch_assoc();
               
          ?>

          <li class="dropdown notifications-menu">
            <a href="noticepage.php" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"><?php echo $noticesrow['countnotices'];?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $noticesrow['countnotices'];?> notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                <?php
                  
                  $sql = "SELECT * FROM users";  //comeback for condition
                  $results = $connect->query($sql);
                  while($final = $results->fetch_assoc()) { ?>
         
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> <?php echo $final['Name'];?>
                    </a>
                  </li>
                  <?php } ?>
                </ul>
              </li>
              <li class="footer"><a href="noticepage.php">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../images/emma.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $Teacherresultsfinal['Surname'] ?></span>
            </a>

          </li>
          
        </ul>
      </div>
    </nav>
  </header>
