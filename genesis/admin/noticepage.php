<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: ../common/login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notices</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
    .dashline {
      border-top: 1px dashed #ccc;
      margin: 15px 0;
    }
    .modal-header {
      background-color: rgb(159, 176, 185);
      color: white;
      padding: 20px;
      border-bottom: 1px solid #eee;
    }
    .modal-header h3 {
      margin: 0;
      color: blue;
    }
    .notice {
      padding: 15px;
      background-color: #f9f9f9;
      border-left: 5px solid #3c8dbc;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }
    .notice:hover {
      background-color: #eef5fb;
    }
    .notice.read {
      background-color: #d9edf7;
      text-decoration: line-through;
      opacity: 0.7;
    }
    .close-notice {
      float: right;
      padding: 6px 12px;
      color: white;
      background-color: #3c8dbc;
      border-radius: 3px;
      font-size: 12px;
      text-align: center;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .close-notice:hover {
      background-color: #367fa9;
    }
    .modal-footer {
      padding: 15px 20px;
      background-color: #f5f5f5;
      text-align: right;
      border-top: 1px solid #ddd;
    }
    .modal-body p {
      margin-bottom: 5px;
    }
    .modal-content {
      box-shadow: 0 3px 9px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>

<?php
include('../partials/connect.php');
$userId = $_SESSION['user_id'];

// Fetch user info if needed (optional)
$usql = "SELECT * FROM users WHERE Id = ?";
$stmtUser = $connect->prepare($usql);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userData = $resultUser->fetch_assoc();
$stmtUser->close();

// Fetch notices where CreatedFor includes user role or ID, and order by date descending
// For simplicity, here fetching all notices (you can customize filtering)

$sql = "SELECT NoticeNo, Title, Content, Date, IsOpened FROM notices ORDER BY Date DESC";
$results = $connect->query($sql);
?>

<div class="container">
  <div class="modal fade" id="myModal" role="dialog" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <a href="adminindex.php" class="close" data-dismiss="modal" onclick="closeModal()">&times;</a>
          <h3 class="modal-title" id="modalTitle">Notification Centre</h3>
          <?php
          if (isset($_SESSION['succes'])) {
            echo '<p>' . $_SESSION['succes'] . '</p>';
            unset($_SESSION['succes']);
          }
          ?>
        </div>

        <div class="modal-body">
          <?php if ($results && $results->num_rows > 0): ?>
            <?php while ($notice = $results->fetch_assoc()): ?>
              <div class="notice <?php echo $notice['IsOpened'] ? 'read' : ''; ?>" data-id="<?php echo $notice['NoticeNo']; ?>">
                <p>
                  <strong style="color: blue;">Date:</strong> <?php echo date('Y-m-d', strtotime($notice['Date'])); ?>
                  <a href="readnotice.php?id=<?php echo $notice['NoticeNo']; ?>" class="close-notice" onclick="markAsRead(this)">Mark Read</a>
                </p>
                <p><strong style="color: blue;">Subject:</strong> <strong style="color: black;"><?php echo htmlspecialchars($notice['Title']); ?></strong></p>
                <p><?php echo nl2br(htmlspecialchars($notice['Content'])); ?></p>
              </div>
              <hr class="dashline" />
            <?php endwhile; ?>
          <?php else: ?>
            <p>No notices available.</p>
          <?php endif; ?>
        </div>

        <div class="modal-footer">
          <a href="adminindex.php" class="btn btn-default">Close</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#myModal').modal({
    backdrop: false,
    keyboard: false,
    show: true
  });

  $('#myModal').on('hidden.bs.modal', function () {
    window.location.href = 'adminindex.php';
  });
});

function closeModal() {
  $('#myModal').modal('hide');
}

function markAsRead(element) {
  const notice = element.closest('.notice');
  notice.classList.add('read');
}
</script>

</body>
</html>
