<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Averages Line Chart</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/helpers.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <canvas id="myChart"></canvas>
    </div>

    <?php
    include('../partials/connect.php');

    $learner_id = intval($_GET['id']);  // Ensure it's an integer

    // Initialize arrays to store daily averages
    $dailyAverages = [];

    // Fetch distinct dates and their respective scores
    $dateSql = "SELECT DISTINCT ReportDate FROM scores WHERE LearnerId = $learner_id";
    $dateResults = $connect->query($dateSql);

    if ($dateResults === false) {
        echo "<p>Error: " . $connect->error . "</p>";
        exit;
    }

    // Fetch scores and calculate averages per day
    while ($dateRow = $dateResults->fetch_assoc()) {
        $date = $dateRow['ReportDate'];

        // Initialize totals and count for the current date
        $totalEngagement = 0;
        $totalIndependence = 0;
        $count = 0;

        // Query to get scores for the current date
        $sql = "SELECT EngagementLevel, IndependanceLevel FROM scores WHERE LearnerId = $learner_id AND ReportDate = '$date'";
        $fileResults = $connect->query($sql);

        if ($fileResults === false) {
            echo "<p>Error fetching scores for date $date: " . $connect->error . "</p>";
            continue; // Skip to the next date if there's an error
        }

        // Fetch results and accumulate totals
        while ($results = $fileResults->fetch_assoc()) {
            $totalEngagement += $results['EngagementLevel'];
            $totalIndependence += $results['IndependanceLevel'];
            $count++;
        }

        if ($count > 0) {
            $averageEngagement = ($totalEngagement / ($count * 10)) * 100;
         
            $averageIndependence = ($totalIndependence / ($count * 10)) * 100;

            // Store the average for this day
            $dailyAverages[$date] = [
                'engagement' => $averageEngagement,
                'independence' => $averageIndependence,
            ];
        } else {
            // Handle case where no scores were found for the date 12 august
            $dailyAverages[$date] = [
                'engagement' => 0,
                'independence' => 0,
            ];
        }
    }

    // Sort dates
    ksort($dailyAverages);

    $chartData = [
        'dates' => array_keys($dailyAverages),
        'engagement' => array_column($dailyAverages, 'engagement'),
        'independence' => array_column($dailyAverages, 'independence'),
    ];
    ?>

    <script>
        // Prepare chart data
        const chartData = <?php echo json_encode($chartData); ?>;

        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.dates,
                datasets: [{
                    label: 'Engagement',
                    data: chartData.engagement,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                }, {
                    label: 'Independence',
                    data: chartData.independence,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Percentage (%)'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>
</html>