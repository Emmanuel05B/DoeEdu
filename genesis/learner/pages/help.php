<!DOCTYPE html>
<html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../../common/pages/login.php");
  exit();
}
?>
<?php include(__DIR__ . "/../../common/partials/head.php"); ?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
  <?php include(__DIR__ . "/../partials/header.php"); ?>
  <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">

    <section class="content-header">
      <h1>Help & Support</h1>
      <small>Your quick guide to getting the most out of our platform</small>
        <ol class="breadcrumb">
          <li><a href="learnerindex.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">xxxx</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">

        <!-- FAQ Section -->
        <div class="col-md-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Frequently Asked Questions</h3>
            </div>
            <div class="box-body">
              <div class="box-group" id="faqAccordion">

                <div class="panel box box-default">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#faqAccordion" href="#faq1" aria-expanded="true">
                        How do I book a tutoring session?
                      </a>
                    </h4>
                  </div>
                  <div id="faq1" class="panel-collapse collapse in">
                    <div class="box-body">
                      Navigate to the "Book a Tutoring Session" page from your dashboard. Select the subject, date, and time, then submit your booking.
                    </div>
                  </div>
                </div>

                <div class="panel box box-default">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a class="collapsed" data-toggle="collapse" data-parent="#faqAccordion" href="#faq2">
                        How can I submit homework or assessments?
                      </a>
                    </h4>
                  </div>
                  <div id="faq2" class="panel-collapse collapse">
                    <div class="box-body">
                      Go to the "Homework & Assessments" page, select the assigned homework, complete the questions, and submit before the deadline.
                    </div>
                  </div>
                </div>

                <div class="panel box box-default">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a class="collapsed" data-toggle="collapse" data-parent="#faqAccordion" href="#faq3">
                        Who can I contact for technical support?
                      </a>
                    </h4>
                  </div>
                  <div id="faq3" class="panel-collapse collapse">
                    <div class="box-body">
                      You can reach out to our support team via email at <a href="mailto:support@doe.com">support@doe.com</a> or call +27 12 345 6789 during office hours.
                    </div>
                  </div>
                </div>

                <div class="panel box box-default">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a class="collapsed" data-toggle="collapse" data-parent="#faqAccordion" href="#faq4">
                        How do I reset my password?
                      </a>
                    </h4>
                  </div>
                  <div id="faq4" class="panel-collapse collapse">
                    <div class="box-body">
                      Use the "Forgot Password" link on the login page to request a password reset link via your registered email.
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="col-md-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Contact Support</h3>
            </div>
            <div class="box-body">
              <p><strong>Email:</strong> <a href="mailto:support@doe.com">support@doe.com</a></p>
              <p><strong>Phone:</strong> +27 12 345 6789</p>
              <p><strong>Office Hours:</strong> Mon - Fri, 08:00 - 17:00</p>
              <p>If you experience any technical issues or need help, please reach out and we'll assist you promptly.</p>
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
