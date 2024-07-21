<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .newsletter {
            max-width: 800px;
            width: 100%;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .newsletter img {
            width: 350px; 
            height: auto; 
            display: block;
            margin: 10px auto; 
        }
        footer {
            margin-top: 20px;
        }
        footer ul {
            list-style: none;
            padding: 0;
        }
        footer ul li {
            margin-bottom: 10px;
        }
        .navigation-buttons a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .navigation-buttons a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="newsletter">
        <h1>KSU Alumni Newsletter</h1>
        <p><?php echo nl2br(htmlspecialchars($_GET['message'])); ?></p>
        <img src="images/ColesLogo.png" alt="KSU and Coles College Logo">
        <footer>
            <div class="navigation-buttons">
                <a href="NewsletterPage.php">Back to Newsletter Page</a>
                <a href="KSUwebsite.html">Go Home</a>
            </div>
        </footer>
    </div>
</body>
</html>