<!-- Quote of the Day Container -->
<div class="relative border border-2 border-rose-400 rounded-lg p-6 bg-white quote_of_the_day_container glow-effect">
  <!-- Badge with Emoji -->
  <div class="absolute -top-3 left-4 px-2.5 bg-rose-600 text-white text-xs md:text-sm font-semibold py-1.5 rounded-full flex items-center shadow-md shadow-pink-200">
    <span class="mr-2">🌟</span> Quote of the Day
  </div>

  <!-- Blockquote -->
  <a href="<?php echo $baseUrl; ?>quote/<?php echo urlencode($quoteOfTheDay['id']); ?>">
    <blockquote class="text-md italic text-gray-700 mt-4 mb-4 md:text-xl">
      "<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quoteOfTheDay['quote_text'])); ?>"
    </blockquote>
    <!-- Footer -->
    <footer class="text-gray-600 text-xs md:text-sm text-right capitalize">
      — <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quoteOfTheDay['author_name'])); ?>
    </footer>
  </a>
</div>
