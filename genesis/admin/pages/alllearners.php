<?php
require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");

$tutorId = $_SESSION['user_id'];

// Input parameters
$subjectId = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
$grade     = isset($_GET['grade']) ? $_GET['grade'] : '';
$group     = isset($_GET['group']) ? $_GET['group'] : '';

// Debug output
echo "<pre>";
echo "Filters: SubjectId=$subjectId, Grade=$grade, Group=$group\n";
echo "</pre>";

// Build query
$sql = "
    SELECT DISTINCT lt.LearnerId, lt.Grade, u.Name, u.Surname, c.GroupName
    FROM learners lt
    INNER JOIN users u ON lt.LearnerId = u.Id
    LEFT JOIN learnersubject ls ON lt.LearnerId = ls.LearnerId
    LEFT JOIN learnerclasses lc ON lt.LearnerId = lc.LearnerID
    LEFT JOIN classes c ON lc.ClassID = c.ClassID
    WHERE 1
";

$params = [];
$types = "";

// Apply filters
if ($subjectId > 0) {
    $sql .= " AND ls.SubjectId = ?";
    $types .= "i";
    $params[] = $subjectId;
}
if ($grade !== '') {
    $sql .= " AND lt.Grade = ?";
    $types .= "s"; // use "i" if your Grade column is INT
    $params[] = $grade;
}
if ($group !== '') {
    $sql .= " AND c.GroupName = ?";
    $types .= "s";
    $params[] = $group;
}

$stmt = $connect->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$results = $stmt->get_result();

// Display results
echo "<h2>Learners List</h2>";
if ($results->num_rows === 0) {
    echo "<p>No learners found.</p>";
} else {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>LearnerId</th><th>Name</th><th>Surname</th><th>Grade</th><th>Group</th></tr>";
    while ($row = $results->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['LearnerId'] . "</td>";
        echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Surname']) . "</td>";
        echo "<td>" . $row['Grade'] . "</td>";
        echo "<td>" . htmlspecialchars($row['GroupName']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
