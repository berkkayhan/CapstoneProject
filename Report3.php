<?php
include 'dbconnection.php';
$sql = "
    SELECT a.fName, a.lName, d.amount, d.donationDate, d.notes
    FROM alumni a
    JOIN donations d ON a.alumniID = d.alumniID
    ORDER BY a.lName, a.fName, d.donationDate
";

$result = $link->query($sql);

if (!$result) {
    die("Error executing query: " . $link->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Option 3: Alumni Donations</title>
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
        ul#donation-list {
            list-style: none;
            padding: 0;
        }
        ul#donation-list li {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f9f9f9;
        }
        .donation-details {
            margin: 0;
            padding: 0;
        }
        .donation-details p {
            margin: 0.5rem 0;
        }
        .donation-amount {
            font-weight: bold;
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
        <h1>Report Option 3: Alumni Donations</h1>
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
            <h2>Alumni Donation Records</h2>
            <ul id="donation-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>
                            <h3>{$row['fName']} {$row['lName']}</h3>
                            <div class='donation-details'>
                                <p class='donation-amount'>Donation Amount: \$" . number_format($row['amount'], 2) . "</p>
                                <p>Donation Date: {$row['donationDate']}</p>
                                <p>Notes: {$row['notes']}</p>
                            </div>
                        </li>";
                    }
                } else {
                    echo "<li>No donations recorded</li>";
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