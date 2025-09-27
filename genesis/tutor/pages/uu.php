<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../common/pages/login.php");
    exit();
}

include(__DIR__ . "/../../partials/connect.php");
?>

<?php include(__DIR__ . "/../../common/partials/head.php"); ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include(__DIR__ . "/../partials/header.php"); ?>
    <?php include(__DIR__ . "/../partials/mainsidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Learners & Finances Report</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>StNo</th>
                                            <th>Name</th>
                                            <th>Surname</th>
                                            <th>Total Fees</th>
                                            <th>Total Paid</th>
                                            <th>Balance</th>
                                            <th>Last Payment</th>
                                            <th>Subjects & Expiry</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "
                                            SELECT f.*, u.Name, u.Surname
                                            FROM finances f
                                            JOIN users u ON f.LearnerId = u.Id
                                            ORDER BY u.Surname, u.Name
                                        ";
                                        $results = $connect->query($sql);

                                        while ($learner = $results->fetch_assoc()) {
                                            // Fetch all subjects for this learner
                                            $stmtSubj = $connect->prepare("
                                                SELECT s.SubjectName, ls.ContractExpiryDate
                                                FROM learnersubject ls
                                                JOIN subjects s ON ls.SubjectId = s.SubjectId
                                                WHERE ls.LearnerId = ?
                                                ORDER BY ls.ContractExpiryDate DESC
                                            ");
                                            $stmtSubj->bind_param("i", $learner['LearnerId']);
                                            $stmtSubj->execute();
                                            $subjResult = $stmtSubj->get_result();

                                            $subjectsText = [];
                                            while ($subj = $subjResult->fetch_assoc()) {
                                                $subjectsText[] = htmlspecialchars($subj['SubjectName']) . ' (' . $subj['ContractExpiryDate'] . ')';
                                            }
                                            $stmtSubj->close();
                                        ?>
                                        <tr>
                                            <td><?php echo $learner['LearnerId']; ?></td>
                                            <td><?php echo htmlspecialchars($learner['Name']); ?></td>
                                            <td><?php echo htmlspecialchars($learner['Surname']); ?></td>
                                            <td>R<?php echo number_format($learner['TotalFees'],2); ?></td>
                                            <td>R<?php echo number_format($learner['TotalPaid'],2); ?></td>
                                            <td>R<?php echo number_format($learner['Balance'],2); ?></td>
                                            <td>
                                                <?php 
                                                if (!empty($learner['LastPaymentDate'])) {
                                                    echo date('d M Y, H:i', strtotime($learner['LastPaymentDate']));
                                                } else {
                                                    echo "Never";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo implode('<br>', $subjectsText); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>StNo</th>
                                            <th>Name</th>
                                            <th>Surname</th>
                                            <th>Total Fees</th>
                                            <th>Total Paid</th>
                                            <th>Balance</th>
                                            <th>Last Payment</th>
                                            <th>Subjects & Expiry</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include(__DIR__ . "/../../common/partials/queries.php"); ?>

<script>
$(function () {
    $('#example1').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });
});
</script>

</body>
</html>
