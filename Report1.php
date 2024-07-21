<?php
include 'dbconnection.php';

$selectedAlumniID = null;
if (isset($_GET['alumniID'])) {
    $selectedAlumniID = intval($_GET['alumniID']);
}
$sql = "
    SELECT alumni.alumniID, alumni.fName, alumni.lName, alumni.phone, alumni.email, address.streetAddress, address.city, address.state, address.zipCode, degree.graduationDate, degree.concentration AS major, 
           employment.companyName, employment.city AS jobCity, employment.state AS jobState
    FROM alumni
    JOIN degree ON alumni.alumniID = degree.alumniID
    LEFT JOIN employment ON alumni.alumniID = employment.alumniID AND employment.currentlyEmployed = 1
    LEFT JOIN address ON alumni.alumniID = address.alumniID
";

$result = $link->query($sql);

if (!$result) {
    die("Error executing query: " . $link->error);
}

$details = null;
if ($selectedAlumniID) {
    $detailSql = "
        SELECT alumni.fName, alumni.lName, alumni.phone, alumni.email, address.streetAddress, address.city, address.state, address.zipCode, 
               employment.companyName, employment.city AS jobCity, employment.state AS jobState
        FROM alumni
        LEFT JOIN address ON alumni.alumniID = address.alumniID
        LEFT JOIN employment ON alumni.alumniID = employment.alumniID AND employment.currentlyEmployed = 1
        WHERE alumni.alumniID = $selectedAlumniID
    ";

    $detailResult = $link->query($detailSql);

    if ($detailResult && $detailResult->num_rows > 0) {
        $details = $detailResult->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Option 1</title>
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Report Option 1: Alumni Details</h1>
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
            <h2>All Alumni with Details</h2>
            <ul id="alumni-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $alumniID = htmlspecialchars($row['alumniID']);
                        $name = htmlspecialchars($row['fName'] . ' ' . $row['lName']);
                        $graduationYear = date('Y', strtotime($row['graduationDate']));
                        $major = htmlspecialchars($row['major']);
                        $company = htmlspecialchars($row['companyName']);
                        $jobCity = htmlspecialchars($row['jobCity']);
                        $jobState = htmlspecialchars($row['jobState']);
                        echo "<li>
                            <a href='report1.php?alumniID={$alumniID}' style='color:blue; text-decoration:underline;'>{$name}</a> - Graduated: {$graduationYear} - Major: {$major} - Company: {$company}, {$jobCity}, {$jobState}
                        </li>";
                    }
                } else {
                    echo "<li>No data found</li>";
                }
                ?>
            </ul>
        </section>

        <?php if ($details): ?>
        <div id="myModal" class="modal" style="display:block;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
                <h2><?php echo htmlspecialchars($details['fName'] . ' ' . $details['lName']); ?></h2>
                <p>Phone: <?php echo htmlspecialchars($details['phone']); ?></p>
                <p>Email: <?php echo htmlspecialchars($details['email']); ?></p>
                <p>Address: <?php echo htmlspecialchars($details['streetAddress']) . ', ' . htmlspecialchars($details['city']) . ', ' . htmlspecialchars($details['state']) . ' ' . htmlspecialchars($details['zipCode']); ?></p>
                <p>Current Job: <?php echo htmlspecialchars($details['companyName']) . ', ' . htmlspecialchars($details['jobCity']) . ', ' . htmlspecialchars($details['jobState']); ?></p>
            </div>
        </div>
        <?php endif; ?>

    </main>

    <footer>
        <div class="footer-links">
            <a href="https://www.kennesaw.edu/" class="footer-link">Kennesaw State University</a>
            <a href="https://www.kennesaw.edu/coles/academics/information-systems-security/index.php" class="footer-link">Information Systems Security at Coles College</a>
            <a href="https://www.kennesaw.edu/coles/index.php" class="footer-link">Coles College of Business</a>
        </div>
    </footer>
</body>
</html>

<?php $link->close(); ?>