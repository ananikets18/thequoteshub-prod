<?php
$baseUrl = getBaseUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Contributors</title>
    
    <!-- Tailwind CSS (Local Build - Production Optimized) -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/tailwind.min.css">
    
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
</head>

<body style="background-color: #f4f2ee;">
<?php include_once  __DIR__ . '/../../../config/utilis.php'; ?>
    <!-- Back to Home Link with Tailwind CSS -->
    <nav class="w-full bg-gray-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <a href="<?php echo $baseUrl; ?>" class="text-gray-700 font-bold text-sm md:text-lg hover:text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        class="inline-block w-5 h-5 md:w-6 md:h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m-6-6 6 6-6 6" />
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </nav>

    <div class="flex flex-col items-center justify-center min-h-screen px-4 mt-14">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
            Top Contributors
        </h1>
        <p class="text-md text-center lg:text-lg text-gray-600 mb-6">
            Here are the users with the most quotes created:
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 max-w-4xl mx-auto">
    <?php if (!empty($topUsers)): ?>
        <?php foreach ($topUsers as $user): ?>
            <?php
            // Retrieve user image filename
            $userImageFile = $user['user_img'];
            $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;

            // Determine the image source based on whether the file exists or if it's blank
            $imageSrc = !empty($userImageFile) && file_exists($userImagePath) ? 
                $baseUrl . 'public/uploads/users/' . $userImageFile : 
                $baseUrl . 'public/uploads/authors_images/placeholder.png';
            ?>
            <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-4 flex flex-col items-center text-center">
                <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" 
                     alt="<?php echo htmlspecialchars(decodeCleanAndRemoveTags(decodeAndCleanText($user['name'])), ENT_QUOTES, 'UTF-8'); ?>" 
                     class="w-16 h-16 rounded-full mb-3 object-cover border">
                <a href="<?php echo $baseUrl . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>" 
                   class="text-blue-500 hover:underline font-semibold">
                    <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
                <p class="text-gray-700 mt-2 font-medium"><?php echo UCwords(htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8')); ?></p>
                <p class="text-gray-500 text-sm">Quotes Created: 
                    <span class="font-semibold"><?php echo htmlspecialchars($user['quote_count'], ENT_QUOTES, 'UTF-8'); ?></span>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full text-center text-gray-700">
            No contributors found.
        </div>
    <?php endif; ?>
</div>

    </div>
    <br /><br /><br /><br />
<!-- Footer -->
<footer class="bg-white text-gray-700 py-6 border-t border-gray-200">
  <div class="container mx-auto px-4">
    <!-- Footer Links -->
    <div class="flex flex-wrap justify-center mb-6">
      <a href="<?php echo $baseUrl; ?>about" class="mx-4 text-xs md:text-sm hover:text-emerald-600">About Us</a>
              <a href="<?php echo $baseUrl; ?>guidelines" class="mx-4 text-sm hover:text-emerald-600">Guidelines</a>
      <a href="<?php echo $baseUrl; ?>terms" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Terms of Service</a>
      <a href="<?php echo $baseUrl; ?>contact" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Contact Us</a>
      <a href="<?php echo $baseUrl; ?>disclaimer" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Disclaimer</a>
      <a href="<?php echo $baseUrl; ?>privacy" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Privacy Policy</a>
      <a href="<?php echo $baseUrl; ?>blogs" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Blog</a>
      <a href="<?php echo $baseUrl; ?>feedback" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Feedback</a>
    </div>

    <!-- Logo and Copyright -->
    <div class="flex md:flex-row flex-col items-center justify-center space-x-4">
      <a class="flex items-center justify-center space-x-4" href="<?php echo getBaseUrl(); ?>"><img
          src="<?php echo $baseUrl; ?>public/uploads/images/logo.svg" alt="Website Logo" class="mr-4" />
        <p class="text-xs text-gray-500">&copy; <span><?php echo date("Y"); ?></span>  Quoteshub</p>
      </a>
      <p class="text-xs text-gray-500 ml-4">
        Crafted with
        <span class="hover:text-emerald-600">&hearts;</span> for the people
        on the internet
      </p>
    </div>
  </div>
</footer>
</body>
</html>
