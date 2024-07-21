<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      .newsletter img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 10px 0;
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
    </style>
  </head>
  <body>
    <div class="newsletter">
      <h1>
        Newsletter from
        <?php echo htmlspecialchars($_GET['name']); ?>
      </h1>
      <p><?php echo nl2br(htmlspecialchars($_GET['message'])); ?></p>
      <img src="images/ColesLogo.png" alt="KSU and Coles College Logo" />
      <footer>
        <h2>Recipients:</h2>
        <ul>
          <?php
        $recipients = explode(',', urldecode($_GET['recipients']));
        foreach ($recipients as $recipient):
            if (!empty($recipient)):
        ?>
          <li><?php echo htmlspecialchars($recipient); ?></li>
          <?php endif; endforeach; ?>
        </ul>
        <div class="navigation-buttons">
          <a href="NewsletterPage.php">Back to Newsletter Page</a>
          <a href="KSUwebsite.html">Go Home</a>
        </div>
      </footer>
    </div>
  </body>
</html>
