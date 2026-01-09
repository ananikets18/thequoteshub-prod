<?php
/**
 * Quotes Index - Main Content
 * This file contains the actual page content without layout wrapper
 */
?>

<!-- Main Content Area -->
<div id="discover-section" class="container mx-auto p-4 z-0 md:pt-10">
  <div class="flex flex-wrap gap-4">
    
    <!-- Left Sidebar -->
    <?php include __DIR__ . '/../partials/sidebar-left.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 p-1 md:px-1.5">
      <div class="w-full max-w-4xl mx-auto px-2 pt-0">
        
        <!-- Quote of the Day Card -->
        <?php if (isset($quoteOfTheDay) && $quoteOfTheDay): ?>
        <a href="<?php echo $baseUrl; ?>quote/<?php echo urlencode($quoteOfTheDay['id']); ?>" 
           class="block quote-of-day-card mb-6 p-4 md:p-6 border-2 border-pink-400 bg-gradient-to-br from-pink-50 to-white rounded-lg shadow-md hover:shadow-xl hover:scale-[1.02] transition-all duration-300 cursor-pointer">
          <div class="flex items-center justify-center mb-4">
            <div class="bg-pink-500 text-white px-4 py-2 rounded-full inline-flex items-center shadow-md">
              <span class="text-xl mr-2">✨</span>
              <span class="font-semibold text-sm">Quote of the Day</span>
            </div>
          </div>
          
          <blockquote class="text-center">
            <p class="text-gray-800 text-base md:text-lg font-medium leading-relaxed mb-3">
              "<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quoteOfTheDay['quote_text'])); ?>"
            </p>
            <footer class="text-gray-600 text-sm md:text-base italic">
              — <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quoteOfTheDay['author_name'])); ?>
            </footer>
          </blockquote>
        </a>
        <?php endif; ?>
        
        <!-- Tabs -->
        <?php include __DIR__ . '/../partials/tabs.php'; ?>

        <!-- Quote Cards -->
        <?php if (!empty($Latestquotes)): ?>
          <div class="quote_cards_wrapper space-y-6 mt-6">
            <?php foreach ($Latestquotes as $quote): ?>
              <?php include __DIR__ . '/../partials/quote-card.php'; ?>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php include __DIR__ . '/../partials/pagination.php'; ?>

        <?php else: ?>
          <div class="text-center text-gray-500 py-4">
            <span class="mr-2">😢</span> No quotes available.
          </div>
        <?php endif; ?>
        
      </div>
    </main>

    <!-- Right Sidebar -->
    <?php include __DIR__ . '/../partials/sidebar-right.php'; ?>

  </div>
</div>

<!-- Modals -->
<?php include __DIR__ . '/../partials/modals.php'; ?>

<!-- Go to Top Button -->
<button title="Go To Top" id="goTopBtn" class="fixed bottom-5 right-5 bg-blue-500 text-white rounded-full w-8 h-8 md:w-10 md:h-10 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none">
  <span class="text-lg md:text-xl">↑</span>
</button>
