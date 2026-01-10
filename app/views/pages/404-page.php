<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 Not Found - TheQuotesHub</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <?php $baseUrl = getBaseUrl(); ?>
  <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">

  <link rel="stylesheet" href="./style.css" />
     <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
        </script>
  <style>
    <?php // Define the base URL of your project (adjust this if needed)
    $baseUrl = getBaseUrl(); // Replace with your actual base URL

    ?>body {
      margin: 0;
      padding: 0;
      font-family: "Nunito", sans-serif;
      background: url("<?php echo $baseUrl; ?>public/uploads/images/error-page.jpg") no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      text-align: center;
    }

    .container {
      background-color: rgba(0, 0, 0, 0.5);
      padding: 40px;
      border-radius: 10px;
    }

    h1 {
      font-size: 3em;
      margin-bottom: 20px;
    }

    p {
      font-size: 1.2em;
      margin-bottom: 30px;
    }

    a {
      display: inline-flex;
      align-items: center;
      text-decoration: none;
      font-size: 1.2em;
      color: #fff;
      background-color: #007bff;
      padding: 10px 20px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    a:hover {
      background-color: #0056b3;
    }

    a i {
      margin-right: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>404 Not Found</h1>
    <p>Oops! The page you are looking for does not exist.</p>
    <a href="<?php echo getBaseUrl(); ?>">
      <i class="fas fa-home"></i> Back to Home
    </a>
  </div>
</body>

</html>