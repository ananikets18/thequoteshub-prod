<?php include __DIR__ . '/components/header.php'; ?>
<?php include_once  __DIR__ . '../../../../config/utilis.php'; ?>
<?php include __DIR__ . '/components/navbar-component.php'; ?>
<div class="max-w-7xl mx-auto py-10 h-lvh px-4">
    <h1 class="text-3xl font-semibold text-gray-800 mb-5">Analytics</h1>
    <div class="bg-white shadow rounded-lg p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- User Count Overview 1 -->
        <div class="bg-blue-100 border border-blue-300 rounded-lg p-6 text-center">
            <p class="text-gray-600 text-lg font-medium">Total Users</p>
            <h2 class="text-3xl font-bold text-blue-600">
                <?php echo $totalUsers; ?>
            </h2>
        </div>
        <!-- User Count Overview 2 -->
        <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-6 text-center">
            <p class="text-gray-700 text-lg font-medium">Total Quotes</p>
            <h2 class="text-3xl font-bold text-yellow-600">
                     <?php echo number_format($quotesT); ?>
            </h2>
        </div>
    </div>
    
    <!-- Search Form -->
    <div class="mt-8 mb-4">
        <form action="<?php echo url('admin/analytics'); ?>" method="GET">
            <div class="flex items-center gap-2">
                <input type="text" 
                       name="search" 
                       placeholder="Search users by name, username or email..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm font-medium">
                    Search
                </button>
                <?php if (isset($_GET['search']) && $_GET['search'] != ''): ?>
                    <a href="<?php echo url('admin/analytics'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 shadow-sm font-medium">
                        Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
       <div class="hidden md:block overflow-x-auto mt-6">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User Name</th>
                        <th class="px-4 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date Created</th>
                        <th class="px-4 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Quotes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <?php foreach ($usersWithCount as $index => $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($user['id']); ?></td> 
                        <td class="px-4 py-3 whitespace-nowrap">
                            <a href="<?= getBaseUrl(); ?><?= htmlspecialchars($user['username']); ?>" class="text-blue-600 hover:text-blue-900 font-medium">
                                <?= htmlspecialchars(decodeCleanAndRemoveTags(decodeAndCleanText($user['name']))); ?>
                            </a>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($user['email']); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"> <?= htmlspecialchars(convertUtcToIst($user['created_at'])); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 font-semibold"><?= htmlspecialchars($user['total_quotes']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- User List Cards (Mobile) -->
        <div class="md:hidden mt-6 space-y-4">
            <?php foreach ($usersWithCount as $user): ?>
            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                     <span class="text-xs font-bold text-gray-500">#<?= htmlspecialchars($user['id']); ?></span>
                     <span class="text-xs text-gray-400"><?= htmlspecialchars(convertUtcToIst($user['created_at'])); ?></span>
                </div>
                <div class="mb-2">
                    <a href="<?= getBaseUrl(); ?><?= htmlspecialchars($user['username']); ?>" class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                        <?= htmlspecialchars(decodeCleanAndRemoveTags(decodeAndCleanText($user['name']))); ?>
                    </a>
                    <div class="text-sm text-gray-600 truncate"><?= htmlspecialchars($user['email']); ?></div>
                </div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-600">Total Quotes:</span>
                    <span class="text-sm font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($user['total_quotes']); ?></span>
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
            <a href="<?php echo url('admin/analytics?page=' . ($page - 1) . $searchParams); ?>" 
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
                <a href="<?php echo url('admin/analytics?page=1' . $searchParams); ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">1</a>
                <?php if ($startPage > 2): ?>
                <span class="px-3 py-2">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <?php if ($i == $page): ?>
                <span class="px-3 py-2 bg-blue-500 text-white rounded font-bold"><?php echo $i; ?></span>
                <?php else: ?>
                <a href="<?php echo url('admin/analytics?page=' . $i . $searchParams); ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($endPage < $totalPages): ?>
                <?php if ($endPage < $totalPages - 1): ?>
                <span class="px-3 py-2">...</span>
                <?php endif; ?>
                <a href="<?php echo url('admin/analytics?page=' . $totalPages . $searchParams); ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300"><?php echo $totalPages; ?></a>
            <?php endif; ?>
            </div>

            <!-- Next Button -->
            <?php if ($page < $totalPages): ?>
            <a href="<?php echo url('admin/analytics?page=' . ($page + 1) . $searchParams); ?>" 
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
            (<?php echo $totalUsersQuotesCount; ?> total users, <?php echo $limit; ?> per page)
        </div>
        <?php endif; ?>

        <br>        <br>
        <br>
        
        </div>


