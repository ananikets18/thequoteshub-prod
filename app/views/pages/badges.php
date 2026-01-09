<?php
$baseUrl = getBaseUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discover the achievement badges for users on The Quotes Hub. Earn badges by publishing quotes and becoming a debut writer.">
    <meta name="keywords" content="The Quotes Hub, quotes, achievements, badges, publishing, writers, debut writer, 10 quotes, 50 quotes">
    <meta name="author" content="The Quotes Hub">
    <meta property="og:title" content="Achievement Badges | The Quotes Hub">
    <meta property="og:description" content="Celebrate your achievements by earning badges for publishing quotes and being an active member of The Quotes Hub.">
    <meta property="og:url" content="<?php echo getBaseUrl(); ?>/badges">
    <meta property="og:type" content="website">
    <title>Achievement Badges | The Quotes Hub</title>
    <link rel="canonical" href="<?php echo getBaseUrl(); ?>badges">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    <!-- Tailwind removed - use base layout or local CSS -->
</head>
<body class="antialiased" style="background-color: #dcdad6;">
    
    <!-- Header Section -->
    <header class="p-6 bg-white shadow-md mb-8">
        <div class="container mx-auto">
            <p class="text-gray-700 text-lg md:text-2xl font-bold text-center">Achievement Badges for You</p>
        </div>
    </header>

    <!-- Back to Home Link -->
    <nav class="py-4 mb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="<?php echo $baseUrl; ?>" class="text-gray-700 font-semibold text-sm md:text-lg hover:text-emerald-600 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m-6-6 6 6-6 6" />
                </svg>
                Back to Home
            </a>
        </div>
    </nav>

    <!-- Badges Section -->
    <main class="container mx-auto py-10">
        <!--<h2 class="text-xl md:text-2xl font-semibold text-gray-800 text-center mb-10">Your Badges</h2>-->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 px-4 md:px-0">
            
            <!-- Badge: Debut Writer -->
            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:scale-105 transition-transform">
                <div class="flex justify-center mb-4">
                    <img src="<?php echo $baseUrl; ?>public/uploads/badges/debut_writer.png" alt="Debut Writer Badge" class="w-24 h-24 rounded-full shadow-md">
                </div>
                <h3 class="text-xl font-bold text-center text-green-600">Debut Writer</h3>
                <p class="text-gray-700 text-center mt-2 text-sm md:text-base">Congratulations! You've made your debut. This badge is awarded to users who publish their very first quote on The Quotes Hub.</p>
            </div>

            <!-- Badge: 10 Quotes Published -->
            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:scale-105 transition-transform">
                <div class="flex justify-center mb-4">
                    <img src="<?php echo $baseUrl; ?>public/uploads/badges/10_quotes.png" alt="10 Quotes Badge" class="w-24 h-24 rounded-full shadow-md">
                </div>
                <h3 class="text-xl font-bold text-center text-blue-600">10 Quotes Published</h3>
                <p class="text-gray-700 text-center mt-2 text-sm md:text-base">You've published 10 quotes. Keep going! This badge recognizes your commitment to sharing inspiring content.</p>
            </div>
            
            <!-- Badge: 50 Quotes Published -->
            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:scale-105 transition-transform">
                <div class="flex justify-center mb-4">
                    <img src="<?php echo $baseUrl; ?>public/uploads/badges/50_quotes.png" alt="50 Quotes Badge" class="w-24 h-24 rounded-full shadow-md">
                </div>
                <h3 class="text-xl font-bold text-center text-red-600">50 Quotes Published</h3>
                <p class="text-gray-700 text-center mt-2 text-sm md:text-base">Amazing! You've published 50 quotes. This prestigious badge celebrates your dedication and creativity in contributing to The Quotes Hub.</p>
            </div>

        </div>
    </main>
    
    <br>    <br>
    <br>    <br>    <!-- Footer Section -->
    <footer class="bg-white text-gray-700 py-6 border-t border-gray-200 mt-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center mb-4">
                <a href="<?php echo $baseUrl; ?>about" class="mx-4 text-xs md:text-sm hover:text-emerald-600">About Us</a>
                <a href="<?php echo $baseUrl; ?>guidelines" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Guidelines</a>
                <a href="<?php echo $baseUrl; ?>terms" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Terms of Service</a>
                <a href="<?php echo $baseUrl; ?>contact" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Contact Us</a>
                <a href="<?php echo $baseUrl; ?>disclaimer" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Disclaimer</a>
                <a href="<?php echo $baseUrl; ?>privacy" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Privacy Policy</a>
                <a href="<?php echo $baseUrl; ?>blogs" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Blog</a>
                <a href="<?php echo $baseUrl; ?>feedback" class="mx-4 text-xs md:text-sm hover:text-emerald-600">Feedback</a>
            </div>
            <div class="flex md:flex-row flex-col items-center justify-center space-x-4">
                <a class="flex items-center justify-center space-x-4" href="<?php echo getBaseUrl(); ?>">
                    <img src="<?php echo $baseUrl; ?>/public/uploads/images/logo_clean.svg" alt="Website Logo" class="mr-4" />
                    <p class="text-xs text-gray-500">&copy; <span><?php echo date("Y"); ?></span> Quoteshub</p>
                </a>
                <p class="text-xs text-gray-500 ml-4">
                    Crafted with <span class="hover:text-emerald-600">&hearts;</span> for the people on the internet
                </p>
            </div>
        </div>
    </footer>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
