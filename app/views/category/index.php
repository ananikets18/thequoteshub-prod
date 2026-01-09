<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Quoteshub |  Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟 "</title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="Discover a variety of quote categories on Quoteshub. Find inspiration, share your wisdom, and connect with others through quotes on love, life, motivation, and more.">
    <meta name="keywords" content="quotes, inspiration, motivation, wisdom, life quotes, love quotes, categories, Quoteshub">
    <meta name="author" content="thequoteshub">
    <link rel="canonical" href="https://thequoteshub.in/categories">
  
    <?php $baseUrl = getBaseUrl(); ?>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
    
    
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://thequoteshub.in/categories">
    <meta property="og:title" content="Categories - Quoteshub | Share Your Wisdom: Create, Inspire, and Connect Through Quotes!">
    <meta property="og:description" content="Explore diverse categories of quotes at Quoteshub and share your insights with the world.">
      
 
    <!-- Twitter -->
    <meta property="twitter:url" content="https://thequoteshub.in/categories">
    <meta property="twitter:title" content="Categories - Quoteshub | Share Your Wisdom: Create, Inspire, and Connect Through Quotes!">
    <meta property="twitter:description" content="Find quote categories that resonate with you at Quoteshub and share your own wisdom!">

  
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    
    
       <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
        </script>
  </head>
  
  <body style="background-color: #f4f2ee;">
<?php include_once  __DIR__ . '/../../../config/utilis.php'; ?>
<?php
$baseUrl = getBaseUrl(); // Replace with your actual base URL
?>
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
<?php include_once  __DIR__ . '/../../../config/utilis.php'; ?>
<?php
$baseUrl = 'https://thequoteshub.in/'; // Replace with your actual base URL
?>
<div class="container mx-auto px-4 py-8 h-lvh">
  <h1 class="text-xl md:text-2xl lg:text-3xl font-extrabold text-gray-800 my-6 text-center">
    📚 Explore Categories
  </h1>
  <br>
  <hr><br>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Category Card Start -->
    <?php if (!empty($categories)): ?>
    <?php foreach ($categories as $category): ?>
    <div
      class="category-card bg-white shadow-md rounded-lg p-6 flex flex-col justify-between transform hover:scale-105 transition-transform duration-200">
      <div>
        <!-- Category Name with Emoji -->
        <h2 class="text-md md:text-xl lg:text-2xl font-semibold text-gray-800 capitalize flex items-center">
          <span>🔖</span>
          <a href="<?php echo $baseUrl ?>category/<?php echo (urlencode(html_entity_decode($category['category_name']))); ?>"
            class="ml-2 hover:text-green-700 transition-colors duration-200 capitalize">
            <?= decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name'])); ?>
          </a>
        </h2>

        <!-- Quote Count -->
        <p class="text-sm md:text-md  text-gray-600 mt-2">
          <span class="font-bold"><?= $category['quote_count']; ?></span> Quotes available 💬
        </p>
      </div>

      <!-- View Quotes Button with Emoji -->
      <div class="mt-4 text-right">
        <a href="<?php echo $baseUrl ?>category/<?php echo urlencode($category['category_name']); ?>"
          class="inline-block bg-green-600 text-white text-xs md:text-md px-4 py-2 rounded-md font-medium hover:bg-green-700 transition duration-150">
          View Quotes 🚀
        </a>
      </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p class="col-span-3 text-center text-gray-500">No categories available.</p>
    <?php endif; ?>
    <!-- Category Card End -->
  </div>

</div>



<br>
<hr>
<br>

<br /><br /><br /><br />


<?php include __DIR__ . '/../components/footer.php'; ?>
