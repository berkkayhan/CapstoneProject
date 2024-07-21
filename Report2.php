<?php
include 'dbconnection.php';
$sql = "
    SELECT a.alumniID, a.fName, a.lName, e.companyName, e.jobTitle, e.startDate, e.endDate, e.city, e.state
    FROM alumni a
    JOIN employment e ON a.alumniID = e.alumniID
    WHERE a.alumniID IN (
        SELECT alumniID
        FROM employment
        GROUP BY alumniID
        HAVING COUNT(employmentID) > 1
    )
    ORDER BY a.alumniID, e.startDate
";
$result = $link->query($sql);

if (!$result) {
    die("Error executing query: " . $link->error);
}

$alumniJobs = [];

while ($row = $result->fetch_assoc()) {
    $alumniID = $row['alumniID'];
    if (!isset($alumniJobs[$alumniID])) {
        $alumniJobs[$alumniID] = [
            'name' => $row['fName'] . ' ' . $row['lName'],
            'jobs' => []
        ];
    }
    $alumniJobs[$alumniID]['jobs'][] = [
        'companyName' => $row['companyName'],
        'jobTitle' => $row['jobTitle'],
        'startDate' => $row['startDate'],
        'endDate' => $row['endDate'],
        'city' => $row['city'],
        'state' => $row['state']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Option 2: Job Growth Report</title>
    <link rel="stylesheet" href="css/stylesss.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        header {
            background: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 10px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        main {
            padding: 1rem;
        }
        .report-content {
            max-width: 800px;
            margin: 0 auto;
        }
        .report-content h2 {
            margin-bottom: 1rem;
        }
        ul#alumni-list {
            list-style: none;
            padding: 0;
        }
        ul#alumni-list li {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f9f9f9;
        }
        .job-list {
            margin: 0;
            padding: 0;
        }
        .job-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #ddd;
        }
        .job-item:last-child {
            border-bottom: none;
        }
        .job-title {
            font-weight: bold;
        }
        .job-details {
            font-size: 0.9rem;
        }
        footer {
            background: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Report Option 2: Job Growth Report</h1>
        <nav>
            <ul class="toc-menu">
                <li><a href="NewsletterPage.php">Newsletter</a></li>
                <li><a href="AlumniDirectory.php">Alumni Directory</a></li>
                <li><a href="KSUwebsite.html">Home</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="report-content">
            <h2>Alumni with Multiple Employment Records</h2>
            <ul id="alumni-list">
                <?php
                if (!empty($alumniJobs)) {
                    foreach ($alumniJobs as $alumniID => $alumni) {
                        echo "<li>
                            <h3>{$alumni['name']}</h3>
                            <div class='job-list'>";
                        foreach ($alumni['jobs'] as $job) {
                            echo "<div class='job-item'>
                                <p class='job-title'>{$job['companyName']}</p>
                                <p class='job-details'>
                                    Job Title: {$job['jobTitle']}<br>
                                    Start Date: {$job['startDate']}<br>
                                    End Date: {$job['endDate']}<br>
                                    Location: {$job['city']}, {$job['state']}
                                </p>
                            </div>";
                        }
                        echo "</div></li>";
                    }
                } else {
                    echo "<li>No alumni with multiple job records found</li>";
                }
                ?>
            </ul>
        </section>
    </main>

    <footer>
        <div class="footer-links">
            <a href="https://www.kennesaw.edu/" class="footer-link">Kennesaw State University</a>
            <a href="https://www.kennesaw.edu/coles/academics/information-systems-security/index.php" class="footer-link">Information Systems Security at Coles College</a>
            <a href="https://www.kennesaw.edu/coles/index.php" class="footer-link">Coles College of Business</a>
        </div>
    </footer>

    <?php $link->close(); ?>
</body>
</html>