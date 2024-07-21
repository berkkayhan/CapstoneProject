<?php
include 'dbconnection.php';

$name = isset($_GET['name']) ? trim($_GET['name']) : '';
$gradYear = isset($_GET['gradYear']) ? intval($_GET['gradYear']) : ''; // Leave as empty string if no year is set
$major = isset($_GET['major']) ? trim($_GET['major']) : '';
$sql = "
    SELECT alumni.alumniID, alumni.fName, alumni.lName, degree.graduationDate, degree.concentration AS major
    FROM alumni
    JOIN degree ON alumni.alumniID = degree.alumniID
    WHERE 1=1
";
if (!empty($name)) {
    $name = $link->real_escape_string($name);
    $sql .= " AND (CONCAT(alumni.fName, ' ', alumni.lName) LIKE '%$name%')";
}
if (!empty($gradYear)) {
    $sql .= " AND YEAR(degree.graduationDate) = $gradYear";
}

if (!empty($major)) {
    $major = $link->real_escape_string($major);
    $sql .= " AND degree.concentration LIKE '%$major%'";
}

$result = $link->query($sql);

if (!$result) {
    die("Error executing query: " . $link->error);
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Directory</title>
    <link rel="stylesheet" href="css/stylesss.css">
</head>
<body>
    <header>
        <h1>Alumni Directory</h1>
        <nav>
            <ul class="toc-menu">
                <li><a href="ReportsPage.html">Reports</a></li>
                <li><a href="NewsletterPage.php">Newsletter</a></li>
                <li><a href="KSUwebsite.html">Home</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="search-section">
            <form id="alumniSearchForm" action="AlumniDirectory.php" method="get">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter name" value="<?php echo htmlspecialchars($name); ?>">
                </div>
                <div class="form-group">
                    <label for="gradYear">Graduation Year:</label>
                    <input type="number" id="gradYear" name="gradYear" placeholder="Enter graduation year" min="1900" max="2100" value="<?php echo htmlspecialchars($gradYear); ?>">
                </div>
                <div class="form-group">
                    <label for="major">Major:</label>
                    <input type="text" id="major" name="major" placeholder="Enter major" value="<?php echo htmlspecialchars($major); ?>">
                </div>
                <button type="submit">Search</button>
            </form>
        </section>

        <section class="search-results">
            <h2>Search Results</h2>
            <ul id="searchResults">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $alumniID = htmlspecialchars($row['alumniID']);
                        $name = htmlspecialchars($row['fName'] . ' ' . $row['lName']);
                        $graduationYear = date('Y', strtotime($row['graduationDate']));
                        $major = htmlspecialchars($row['major']);
                        echo "<li>
                            <a href='report1.php?alumniID={$alumniID}' style='color:blue; text-decoration:underline;'>{$name}</a> - Graduated: {$graduationYear} - Major: {$major}
                        </li>";
                    }
                } else {
                    echo "<li>No results found</li>";
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