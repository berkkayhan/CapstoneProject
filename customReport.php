<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Report</title>
    <link rel="stylesheet" href="css/stylesss.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        header {
            position: relative;
            padding: 0 20px;
            background-color: #333;
            color: #fff;
            text-align: center; 
        }
        .header-content {
            display: flex;
            flex-direction: column; 
            align-items: center; 
            padding: 15px 0;
            position: relative;
        }
        .logo {
            height: 82px;
            width: auto; 
            position: absolute; 
            top: 10px;
            right: 20px; 
        }
        .toc-menu {
            list-style-type: none;
            display: flex;
            justify-content: center; 
            gap: 20px;
            margin-top: 10px;
        }
        .toc-menu li a {
            text-decoration: none;
            color: white;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f4f4f4;
        }
        form {
            margin: 1rem 0;
        }
        input[type="text"], select {
            padding: 0.5rem;
            margin-right: 0.5rem;
        }
        input[type="submit"] {
            padding: 0.5rem 1rem;
            background: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #555;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
        footer {
            background: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Custom Alumni Report</h1>
      <img src="Images/ColesLogo.png" alt="Coles Logo" class="logo">
        <nav>
            <ul class="toc-menu">
                <li><a href="NewsletterPage.php">Newsletter</a></li>
                <li><a href="AlumniDirectory.php">Alumni Directory</a></li>
                <li><a href="KSUwebsite.html">Home</a></li>
            </ul>
        </nav>
    </header>
  
    <main>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Search by Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            <select name="concentration">
                <option value="">Select Concentration</option>
                <option value="MIS" <?php echo isset($_POST['concentration']) && $_POST['concentration'] == 'MIS' ? 'selected' : ''; ?>>MIS</option>
                <option value="ISA" <?php echo isset($_POST['concentration']) && $_POST['concentration'] == 'ISA' ? 'selected' : ''; ?>>ISA</option>
                <option value="IS" <?php echo isset($_POST['concentration']) && $_POST['concentration'] == 'IS' ? 'selected' : ''; ?>>IS</option>
            </select>
            <input type="text" name="grad_year" placeholder="Graduation Year" value="<?php echo isset($_POST['grad_year']) ? htmlspecialchars($_POST['grad_year']) : ''; ?>">
            <input type="submit" value="Generate Report">
        </form>

        <?php
        $host = 'db5016077830.hosting-data.io';
        $database = 'dbs13095086';
        $user = 'dbu3394684';
        $password = 'Heroes123789!';
        $conn = new mysqli($host, $user, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT a.fName, a.lName, d.graduationDate, d.concentration
                FROM alumni a
                JOIN degree d ON a.alumniID = d.alumniID
                WHERE 1=1";
        if (!empty($_POST['name'])) {
            $name = $conn->real_escape_string($_POST['name']);
            $sql .= " AND (a.fName LIKE '%$name%' OR a.lName LIKE '%$name%')";
        }
        if (!empty($_POST['concentration'])) {
            $concentration = $conn->real_escape_string($_POST['concentration']);
            $sql .= " AND d.concentration = '$concentration'";
        }
        if (!empty($_POST['grad_year'])) {
            $grad_year = $conn->real_escape_string($_POST['grad_year']);
            $sql .= " AND YEAR(d.graduationDate) = '$grad_year'";
        }
        $result = $conn->query($sql);
      
        if ($result->num_rows > 0) {
            echo '<div class="report-content"><h2>Report Results</h2><table><tr><th>Name</th><th>Graduation Year</th><th>Concentration</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $graduationYear = date('Y', strtotime($row['graduationDate']));
                echo "<tr>
                        <td>{$row['fName']} {$row['lName']}</td>
                        <td>{$graduationYear}</td>
                        <td>{$row['concentration']}</td>
                    </tr>";
            }
            echo '</table></div>';
        } else {
            echo '<div class="report-content"><h2>No results found.</h2></div>';
        }

        $conn->close();
        ?>
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