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
    header('Location: NewsletterDisplay.php?name=' . urlencode($name) . '&message=' . urlencode($message));
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
      
     body {
            font-family: Arial, sans-serif;
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
            height: 85px;
            width: auto; 
            position: absolute; 
            top: 10px; 
            right: 20px; 
        }
      
      header h1 {
            font-size: 2.5rem; 
        }
        toc-menu {
            list-style-type: none;
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 0;
            margin: 10px 0;
        }
        toc-menu li a {
            text-decoration: none;
            color: white;
            font-size: 1.5rem; 
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
.recipient-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center; 
    gap: 10px; 
}

.recipient-item {
    margin: 0;
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
      .newsletter-form {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center horizontally */
    max-width: 600px; /* Set a maximum width for the form */
    margin: 0 auto; /* Center horizontally within the page */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
}

/* Style the form elements */
.newsletter-form div {
    margin-bottom: 15px; /* Space between form groups */
    width: 100%;
}

.newsletter-form label {
    display: block;
    margin-bottom: 5px;
}

.newsletter-form input[type="text"],
.newsletter-form input[type="email"],
.newsletter-form textarea,
.newsletter-form select {
    width: 100%;
    padding: 10px; 
    font-size: 1rem; 
}

.newsletter-form textarea {
    resize: vertical;
}

.newsletter-form button {
    padding: 10px 20px;
    background-color: #ffc107;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.newsletter-form button:hover {
    background-color: #ffca2c;
    color: white;
}
    </style>
</head>
<body>
    <header>
        <h1>Newsletter Creation Page</h1>
      <img src="Images/ColesLogo.png" alt="Coles Logo" class="logo">
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