<?php include __DIR__ . '/../components/header.php'; ?>
  <?php $baseUrl = getBaseUrl(); ?>

 <div class="max-w-6xl mx-auto p-6">
        <!-- Header -->
        <h1 class="text-3xl font-semibold text-center text-indigo-600 mb-6">Authors Needing Updates</h1>

        <?php if (empty($authors)): ?>
            <p class="text-lg text-gray-700 text-center">No authors need updates.</p>
        <?php else: ?>
            <!-- Table -->
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto text-gray-800">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium">Author Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($authors as $author): ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-6 py-4 text-sm font-medium"><?php echo htmlspecialchars($author['author_name']); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="<?php echo url('create-author/<?php echo urlencode($author['author_name']); ?>'); ?>" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">Update Info</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <?php include __DIR__ . '/../components/footer.php'; ?>