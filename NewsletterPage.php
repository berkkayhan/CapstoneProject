<?php
$host = 'db5016077830.hosting-data.io';
$database = 'dbs13095086';
$user = 'dbu3394684';
$password = 'Heroes123789!';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT CONCAT(fName, ' ', lName) AS fullName FROM alumni";
$result = $conn->query($sql);

$recipients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipients[] = $row['fullName'];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $selectedRecipients = $_POST['recipients'] ?? [];

    if (in_array('all', $selectedRecipients)) {
        $sql = "SELECT CONCAT(fName, ' ', lName) AS fullName FROM alumni";
        $result = $conn->query($sql);

        $allRecipients = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $allRecipients[] = $row['fullName'];
            }
        }
        $selectedRecipients = $allRecipients;
    }

    $recipientsString = urlencode(implode(',', $selectedRecipients));
    header('Location: NewsletterDisplay.php?name=' . urlencode($name) . '&message=' . urlencode($message) . '&recipients=' . $recipientsString);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Creation Page</title>
    <link rel="stylesheet" href="css/stylesss.css">
    <style>
        .recipient-container {
            display: flex;
            flex-wrap: wrap;
        }
        .recipient-item {
            margin-right: 5px;
            margin-bottom: 5px;
            padding: 5px 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .recipient-item span {
            margin-right: 5px;
        }
        .recipient-item button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 3px 8px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Newsletter Creation Page</h1>
        <nav>
            <ul class="toc-menu">
                <li><a href="ReportsPage.html">Reports</a></li>
                <li><a href="AlumniDirectory.php">Alumni Directory</a></li>
                <li><a href="KSUwebsite.html">Home</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="newsletter-form">
            <form id="newsletterForm" action="NewsletterPage.php" method="post">
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" required></textarea>
    </div>
    <div>
        <label for="recipients">Add Recipients:</label>
        <select id="recipientSelect" name="recipients[]" multiple>
            <option value="" selected disabled>Select recipient</option>
            <option value="all">ALL Alumni</option>
            <?php foreach ($recipients as $recipient): ?>
                <option value="<?php echo htmlspecialchars($recipient); ?>"><?php echo htmlspecialchars($recipient); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" id="addRecipientBtn">Add</button>
    </div>
    <div class="recipient-container" id="recipientContainer">
    </div>
    <button type="submit" id="sendNewsletterBtn">Send Newsletter</button>
</form>
        </section>
    </main>

    <footer>
        <div class="footer-links">
            <a href="https://www.kennesaw.edu/" class="footer-link">Kennesaw State University</a>
            <a href="https://www.kennesaw.edu/coles/academics/information-systems-security/index.php" class="footer-link">Information Systems Security at Coles College</a>
            <a href="https://www.kennesaw.edu/coles/index.php" class="footer-link">Coles College of Business</a>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addRecipientBtn = document.getElementById('addRecipientBtn');
            const recipientSelect = document.getElementById('recipientSelect');
            const recipientContainer = document.getElementById('recipientContainer');

            addRecipientBtn.addEventListener('click', function() {
                const selectedOptions = recipientSelect.selectedOptions;

                for (let option of selectedOptions) {
                    const recipientName = option.value;
                    const recipientItem = document.createElement('div');
                    recipientItem.classList.add('recipient-item');
                    recipientItem.innerHTML = `
                        <span>${recipientName}</span>
                        <button type="button" class="remove-recipient-btn">X</button>
                    `;
                    recipientContainer.appendChild(recipientItem);

                    const removeBtn = recipientItem.querySelector('.remove-recipient-btn');
                    removeBtn.addEventListener('click', function() {
                        recipientContainer.removeChild(recipientItem);
                    });
                }
            });
        });
    </script>
</body>
</html>