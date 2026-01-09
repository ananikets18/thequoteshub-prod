<!-- Tabs for Discover and Following -->
<div class="mb-4 flex space-x-4">
  <a href="/" class="px-4 py-2 rounded-lg 
      <?php echo (empty($_GET['tab']) || $_GET['tab'] === 'discover') ? 'bg-emerald-600 text-gray-100' : 'bg-gray-700 text-gray-100'; ?> 
      hover:bg-emerald-700">
    Discover
  </a>
  
  <?php if ($isLoggedIn): ?>
    <a href="?tab=following&page=1" class="px-4 py-2 rounded-lg 
        <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'following') ? 'bg-emerald-600 text-gray-100' : 'bg-gray-700 text-gray-100'; ?> 
        hover:bg-emerald-700">
      Following
    </a>
  <?php endif; ?>
</div>
