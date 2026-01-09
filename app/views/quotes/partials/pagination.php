<!-- Pagination Controls -->
<div class="pagination-wrapper mt-6 flex flex-wrap justify-center w-full">
  <?php if ($page > 1): ?>
    <a href="?tab=<?php echo $tab; ?>&page=<?php echo $page - 1; ?>" 
       class="my-2 mx-2 px-4 py-2 text-sm bg-gray-200 text-gray-600 rounded hover:bg-gray-300">
      &larr;
    </a>
  <?php endif; ?>
  
  <!-- Display first page -->
  <?php if ($page > 3): ?>
    <a href="?tab=<?php echo $tab; ?>&page=1" 
       class="my-2 mx-2 px-4 py-2 text-sm bg-gray-200 text-gray-600 rounded hover:bg-yellow-300">1</a>
    <?php if ($page > 4): ?>
      <span class="my-2 mx-2 px-4 py-2 text-sm text-gray-600">...</span>
    <?php endif; ?>
  <?php endif; ?>

  <!-- Display middle pages -->
  <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
    <a href="?tab=<?php echo $tab; ?>&page=<?php echo $i; ?>" 
       class="my-2 mx-2 px-4 py-2 text-sm <?php echo ($i == $page) ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-600'; ?> rounded hover:bg-yellow-300">
      <?php echo $i; ?>
    </a>
  <?php endfor; ?>

  <!-- Display last page -->
  <?php if ($page < $totalPages - 2): ?>
    <?php if ($page < $totalPages - 3): ?>
      <span class="my-2 mx-2 px-4 py-2 text-sm text-gray-600">...</span>
    <?php endif; ?>
    <a href="?tab=<?php echo $tab; ?>&page=<?php echo $totalPages; ?>" 
       class="my-2 mx-2 px-4 py-2 text-sm bg-gray-200 text-gray-600 rounded hover:bg-yellow-300">
      <?php echo $totalPages; ?>
    </a>
  <?php endif; ?>

  <?php if ($page < $totalPages): ?>
    <a href="?tab=<?php echo $tab; ?>&page=<?php echo $page + 1; ?>" 
       class="my-2 mx-2 px-4 py-2 text-sm bg-gray-200 text-gray-600 rounded hover:bg-gray-300">
      &rarr;
    </a>
  <?php endif; ?>
</div>
