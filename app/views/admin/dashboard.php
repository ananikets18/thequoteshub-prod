<?php include __DIR__ . '/components/header.php'; ?>
<?php include __DIR__ . '/components/navbar-component.php'; ?>
<!-- Dashboard Content -->
<div class="container mx-auto px-4 md:px-6 mt-8 mb-32 h-lvh">
  <!-- Welcome Message -->

  <div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center bg-white rounded-lg">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome back, <span class="text-blue-600"><?php echo decodeCleanAndRemoveTags(decodeAndCleanText(($adminUsername))); ?></span>! 👋</h2>
            <p class="text-gray-600 text-lg">Here's what's happening on your platform today.</p>
        </div>
        <div class="mt-4 md:mt-0">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>
                System Active
            </span>
        </div>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Total Users</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo number_format($totalUsers); ?></h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-full">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?php echo url('admin/analytics'); ?>" class="text-sm text-blue-600 font-medium hover:underline flex items-center">
                View Analytics &rarr;
            </a>
        </div>
    </div>

    <!-- Total Quotes -->
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Total Quotes</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo number_format($totalQuotes); ?></h3>
            </div>
            <div class="p-3 bg-yellow-50 rounded-full">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?php echo url('admin/allquotes'); ?>" class="text-sm text-yellow-600 font-medium hover:underline flex items-center">
                Manage Quotes &rarr;
            </a>
        </div>
    </div>

    <!-- Your Blogs -->
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Your Blogs</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo number_format($totalBlogs); ?></h3>
            </div>
            <div class="p-3 bg-green-50 rounded-full">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?php echo url('blogs'); ?>" class="text-sm text-green-600 font-medium hover:underline flex items-center">
                View Blogs &rarr;
            </a>
        </div>
    </div>
  </div>


  <!-- Blogs Table -->
  <!-- Blogs Section -->
  <div class="bg-white p-6 rounded shadow-md">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-xl font-semibold">Manage Blogs</h3>
      <a href="<?php echo url('admin/create-blog'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition md:hidden">
        + New
      </a>
    </div>

    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden md:block overflow-x-auto">
      <table class="min-w-full bg-white">
        <thead>
          <tr class="w-full bg-blue-500 text-white">
            <th class="text-left py-3 px-4 w-20">ID</th>
            <th class="text-left py-3 px-4">Title</th>
            <th class="text-center py-3 px-4 w-48">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php foreach ($blogs as $blog): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="py-3 px-4"><?php echo htmlspecialchars($blog['id']); ?></td>
              <td class="py-3 px-4 font-medium text-gray-900">
                <?php echo decodeHtmlEntities(htmlspecialchars(substr($blog['title'], 0, 100))); ?>
                <?php if (strlen($blog['title']) > 100) echo '...'; ?>
              </td>
              <td class="text-center py-3 px-4">
                <div class="flex items-center justify-center space-x-3">
                    <a href="<?php echo url('view/' . $blog['id']); ?>" target="_blank"
                       class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center">
                       <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                       View
                    </a>
                    <a href="<?php echo url('admin/edit-blog/' . $blog['id']); ?>"
                       class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                       <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                       Edit
                    </a>
                    <a href="<?php echo url('admin/delete-blog/' . $blog['id']); ?>"
                       onclick="return confirm('Are you sure you want to delete this blog?');"
                       class="text-red-600 hover:text-red-800 font-medium text-sm flex items-center">
                       <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                       Delete
                    </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Mobile Cards (Hidden on Desktop) -->
    <div class="md:hidden space-y-4">
      <?php foreach ($blogs as $blog): ?>
      <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
        <div class="flex justify-between items-start mb-2">
            <h4 class="font-semibold text-gray-800 leading-tight pr-4">
                <?php echo decodeHtmlEntities(htmlspecialchars($blog['title'])); ?>
            </h4>
            <span class="text-xs font-mono bg-gray-200 text-gray-600 px-2 py-1 rounded">#<?php echo htmlspecialchars($blog['id']); ?></span>
        </div>
        
        <div class="flex gap-2 mt-3 pt-3 border-t border-gray-200">
            <a href="<?php echo url('view/' . $blog['id']); ?>" target="_blank"
               class="flex-1 text-center py-2 bg-green-100 text-green-700 rounded hover:bg-green-200 text-sm font-medium">
               View
            </a>
            <a href="<?php echo url('admin/edit-blog/' . $blog['id']); ?>" 
               class="flex-1 text-center py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm font-medium">
               Edit
            </a>
            <a href="<?php echo url('admin/delete-blog/' . $blog['id']); ?>" 
               onclick="return confirm('Are you sure you want to delete this blog?');"
               class="flex-1 text-center py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm font-medium">
               Delete
            </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<?php include __DIR__ . '/components/footer.php'; ?>