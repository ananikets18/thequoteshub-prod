<?php include __DIR__ . '/../components/header.php'; ?>
  <?php include __DIR__ . '/../components/navbar-component.php'; ?>
<?php
require_once __DIR__ . '/../../../config/utilis.php';
$baseUrl = getBaseUrl() . '/admin';
?>
 
  
<div class="container mx-auto px-4 py-10">
  <h1 class="text-2xl md:text-3xl font-bold mb-4">All Quotes</h1>
  
  <!-- Search Form -->
  <form action="<?php echo url('admin/allquotes'); ?>" method="GET" class="mb-6">
    <div class="flex items-center gap-2">
      <input type="text" 
             name="search" 
             placeholder="Search quotes, authors, users or categories..." 
             value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
             class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Search
      </button>
      <?php if (isset($_GET['search']) && $_GET['search'] != ''): ?>
        <a href="<?php echo url('admin/allquotes'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
            Clear
        </a>
      <?php endif; ?>
    </div>
  </form>
  
  <!-- Desktop Table View (hidden on mobile) -->
  <div class="hidden md:block overflow-x-auto mt-6">
    <table class="min-w-full bg-white border border-gray-200">
      <thead class="bg-gray-100 text-left text-xs font-semibold uppercase text-gray-600">
        <tr>
          <th class="py-2 px-4 border-b">ID</th>
          <th class="py-2 px-4 border-b">Content</th>
          <th class="py-2 px-4 border-b">Category</th>
          <th class="py-2 px-4 border-b">User</th>
          <th class="py-2 px-4 border-b">Actions</th>
        </tr>
      </thead>
      <tbody class="text-sm text-gray-700">
        <?php foreach ($quotes as $quote): ?>
        <tr>
        <td class="py-2 px-4 border-b">
            <?= $quote['id'] ?>
            <?php if ($quote['is_edited_user']): ?>
                <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-1 rounded-full ml-2">Edited by User</span>
            <?php endif; ?>
            <?php if ($quote['is_edited_admin']): ?>
                <span class="bg-yellow-200 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full ml-2">Edited by Admin</span>
            <?php endif; ?>
        </td>
          <td class="py-2 px-4 border-b">
            <?php 
              $quoteText = htmlspecialchars($quote['quote_text']);
              echo strlen($quoteText) > 100 ? substr($quoteText, 0, 100) . '...' : $quoteText;
            ?>
          </td>
          <td class="py-2 px-4 border-b"><?= $quote['category_name'] ?></td>
          <td class="py-2 px-4 border-b"><?= $quote['user_name'] ?></td>
          <td class="py-2 px-4 border-b flex space-x-2">
            <a href="<?php echo url('admin/editquote/' . $quote['id']); ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-xs md:text-sm">Edit</a>
            <a href="<?php echo url('admin/deletequote/' . $quote['id']); ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs md:text-sm" onclick="return confirm('Are you sure you want to delete this quote?');">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile Card View (visible only on mobile) -->
  <div class="md:hidden mt-6 space-y-4">
    <?php foreach ($quotes as $quote): ?>
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
      <!-- Quote ID and Badges -->
      <div class="flex items-center justify-between mb-3">
        <span class="text-sm font-semibold text-gray-600">ID: <?= $quote['id'] ?></span>
        <div class="flex gap-1">
          <?php if ($quote['is_edited_user']): ?>
            <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">User</span>
          <?php endif; ?>
          <?php if ($quote['is_edited_admin']): ?>
            <span class="bg-yellow-200 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">Admin</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Quote Content -->
      <div class="mb-3">
        <p class="text-sm text-gray-800 leading-relaxed">
          <?php 
            $quoteText = htmlspecialchars($quote['quote_text']);
            echo strlen($quoteText) > 150 ? substr($quoteText, 0, 150) . '...' : $quoteText;
          ?>
        </p>
      </div>

      <!-- Category and User -->
      <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
        <div>
          <span class="text-gray-500">Category:</span>
          <p class="font-medium text-gray-700"><?= $quote['category_name'] ?></p>
        </div>
        <div>
          <span class="text-gray-500">User:</span>
          <p class="font-medium text-gray-700"><?= $quote['user_name'] ?></p>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex gap-2">
        <a href="<?php echo url('admin/editquote/' . $quote['id']); ?>" 
           class="flex-1 bg-yellow-500 text-white text-center px-4 py-2 rounded hover:bg-yellow-600 text-sm font-medium">
          Edit
        </a>
        <a href="<?php echo url('admin/deletequote/' . $quote['id']); ?>" 
           class="flex-1 bg-red-500 text-white text-center px-4 py-2 rounded hover:bg-red-600 text-sm font-medium"
           onclick="return confirm('Are you sure you want to delete this quote?');">
          Delete
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>


  <!-- Pagination -->
  <?php 
    $searchParams = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
  ?>
  <?php if ($totalPages > 1): ?>
  <div class="mt-6 flex justify-center items-center space-x-2">
    <!-- Previous Button -->
    <?php if ($page > 1): ?>
      <a href="<?php echo url('admin/allquotes?page=' . ($page - 1) . $searchParams); ?>" 
         class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
        ← Previous
      </a>
    <?php else: ?>
      <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded cursor-not-allowed">
        ← Previous
      </span>
    <?php endif; ?>

    <!-- Page Numbers -->
    <div class="flex space-x-1">
      <?php
      $startPage = max(1, $page - 2);
      $endPage = min($totalPages, $page + 2);
      
      if ($startPage > 1): ?>
        <a href="<?php echo url('admin/allquotes?page=1' . $searchParams); ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">1</a>
        <?php if ($startPage > 2): ?>
          <span class="px-3 py-2">...</span>
        <?php endif; ?>
      <?php endif; ?>

      <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
        <?php if ($i == $page): ?>
          <span class="px-3 py-2 bg-blue-500 text-white rounded font-bold"><?php echo $i; ?></span>
        <?php else: ?>
          <a href="<?php echo url('admin/allquotes?page=' . $i . $searchParams); ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300"><?php echo $i; ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($endPage < $totalPages): ?>
        <?php if ($endPage < $totalPages - 1): ?>
          <span class="px-3 py-2">...</span>
        <?php endif; ?>
        <a href="<?php echo url('admin/allquotes?page=' . $totalPages . $searchParams); ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300"><?php echo $totalPages; ?></a>
      <?php endif; ?>
    </div>

    <!-- Next Button -->
    <?php if ($page < $totalPages): ?>
      <a href="<?php echo url('admin/allquotes?page=' . ($page + 1) . $searchParams); ?>" 
         class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
        Next →
      </a>
    <?php else: ?>
      <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded cursor-not-allowed">
        Next →
      </span>
    <?php endif; ?>
  </div>

  <!-- Pagination Info -->
  <div class="mt-4 text-center text-gray-600 text-sm">
    Showing page <?php echo $page; ?> of <?php echo $totalPages; ?> 
    (<?php echo $totalQuotes; ?> total quotes, <?php echo $limit; ?> per page)
  </div>
  <?php endif; ?>
</div>


<?php include __DIR__ . '/../components/footer.php'; ?>

