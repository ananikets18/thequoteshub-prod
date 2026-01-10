<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<title><?= generateKeywords(ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name'])))); ?> - quotes on Quoteshub"</title>-->
    
    
    <!--<?php $baseUrl = getBaseUrl(); ?>-->
    <!--<link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">-->
    
    
    <!-- SEO Meta Tags -->
    <!--<meta name="description" content="Discover a variety of quote categories on Quoteshub. Find inspiration, share your wisdom, and connect with others through quotes on love, life, motivation, and more.">-->
    <!--<meta name="keywords" content="quotes, inspiration, motivation, wisdom, life quotes, love quotes, categories, Quoteshub">-->
    <!--<meta name="author" content="thequoteshub">-->
    <!--<link rel="canonical" href="https://thequoteshub.in/category/<?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name']))); ?>">-->
            <?php
        // Assuming your functions decodeAndCleanText() and decodeCleanAndRemoveTags() already sanitize the category name
        $categoryName = ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name'])));
        $generatedKeywords = generateKeywords($categoryName);
        $primaryKeyword = $generatedKeywords[0]; // Use the first keyword for title tag
        
        ?>
        
        <!-- Title Tag -->
        <title><?= htmlspecialchars($primaryKeyword); ?> - Quotes on Quoteshub</title>
        
        <!-- Base URL -->
        <?php $baseUrl = getBaseUrl(); ?>
        
        <!-- CSS Link -->
        <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">
        
        <!-- SEO Meta Tags -->
        <meta name="description" content="Discover <?= strtolower($categoryName); ?> and other inspiring quotes on Quoteshub. Find wisdom, share insights, and connect through quotes on topics like motivation, love, and life.">
        <meta name="keywords" content="<?= htmlspecialchars(implode(', ', $generatedKeywords)); ?>">
        <meta name="author" content="The Quotes Hub">
        
        <!-- Canonical Link -->
        <link rel="canonical" href="<?= $baseUrl . 'category/' . urlencode(strtolower($categoryName)); ?>">

    
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">
    
    
        <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo getBaseUrl(); ?>category/<?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name']))); ?>">
    <meta property="og:title" content="<?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name']))); ?> - quotes on thequoteshub | Share Your Wisdom: Create, Inspire, and Connect Through Quotes!">
    <meta property="og:description" content="Explore diverse categories of quotes at theQuoteshub and share your insights with the world.">
      
 
    <!-- Twitter -->
    <meta property="twitter:url" content="<?php echo getBaseUrl(); ?>categories/<?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name']))); ?>">
    <meta property="twitter:title" content="<?= ucwords(decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name']))); ?> - quotes on thequoteshub | Share Your Wisdom: Create, Inspire, and Connect Through Quotes!">
    <meta property="twitter:description" content="Find quote categories that resonate with you at Quoteshub and share your own wisdom!">
    
    
    
    <!-- Tailwind CSS CDN -->
    <!-- Tailwind removed - use base layout or local CSS -->
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
<?php include_once __DIR__ . '/../../../config/utilis.php'; ?>



<div class="container mx-auto py-8 px-4 h-lvh">
  <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-center mb-6">📚 Quotes in <span
      class="text-blue-600 capitalize">"<?= decodeCleanAndRemoveTags(decodeAndCleanText($category['category_name'])); ?>"</span></h1>

<div class="mb-4">
  <a href="<?php echo $baseUrl ?>categories" class="inline-block text-gray-500 hover:text-blue-500 hover:underline font-semibold text-sm md:text-md lg:text-lg">
     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="inline-block w-5 h-5 md:w-6 md:h-6 mr-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m-6-6 6 6-6 6" />
      </svg> Back to Categories
  </a>
</div>

  <?php if (!empty($quotes)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($quotes as $quote): ?>
        <a href="<?php echo $baseUrl ?>quote/<?php echo $quote['id'] ?>">
          <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
            <blockquote class="text-md md:text-lg italic mb-4">
              "<?= decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?>" </blockquote>
            <p class="text-xs md:text-sm text-gray-600 mb-2">- <span
                class="font-semibold capitalize"><?= decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?></span></p>
            <p class="text-xs text-gray-400"><?= date('F j, Y', strtotime($quote['created_at'])); ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-center text-gray-500 text-lg mt-6">😕 No quotes found in this category.</p>
  <?php endif; ?>
</div>

<br>
<hr>
<br>

<?php include __DIR__ . '/../components/footer.php'; ?>
