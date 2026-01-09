<?php
include __DIR__ . '/../components/header.php';
include_once  __DIR__ . '/../../../config/utilis.php';

$baseUrl = getBaseUrl(); // Replace with your actual base URL

?>



    <!-- Author Details Section -->
    <section class="container mx-auto px-6 py-8">
          <!-- Back to Authors Section -->
       <section class="text-left py-6">
        <a href="<?php echo url('authors'); ?>" class="text-emerald-600 font-semibold hover:text-emerald-800 transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"></path>
            </svg>
            Back to Authors
        </a>
    </section>

     <div class="flex flex-col lg:flex-row items-center justify-between bg-white shadow-xl rounded-xl p-8 space-y-6 lg:space-y-0 lg:space-x-8">
    <!-- Author Image Section -->
    <div class="lg:w-1/3 mb-6 lg:mb-0">
        <?php
        // Fallback image URL if the author's image is not available
        $authorImageFile = $authorDetails && $authorDetails['author_img'] ? htmlspecialchars($authorDetails['author_img']) : 'placeholder.png';
        $authorImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/authors_images/' . $authorImageFile;
    
        // Determine the image source based on whether the file exists or if it's blank
        $imageSrc = !empty($authorImageFile) && file_exists($authorImagePath) ? $baseUrl . 'public/uploads/authors_images/' . $authorImageFile : $baseUrl . 'public/uploads/authors_images/placeholder.png';
        ?>
        <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($authorName); ?>'s Image" class="rounded-md shadow-xl w-46 h-46 object-cover mx-auto lg:mx-0 mb-4 lg:mb-0">
    </div>

    <!-- Author Info Section -->
    <div class="lg:w-2/3">
        <?php if ($authorDetails): ?>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2 capitalize"><?php echo htmlspecialchars($authorName); ?></h2>
            <p class="text-lg text-gray-600 leading-relaxed mb-4">
                <strong>Description:</strong> <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($authorDetails['description'])); ?>
            </p>
            <p class="text-lg font-medium text-indigo-600">
                <strong>Profession:</strong> <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($authorDetails['profession'])); ?>
            </p>
        <?php else: ?>
            <p class="text-sm lg:text-lg text-red-500 font-medium">Author details are currently not available at this moment.</p>
        <?php endif; ?>
    </div>
</div>

    </section>

    <!-- Quotes Section -->
    <section class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-indigo-700 mb-6">Quotes</h2>
        <?php if ($quotes && count($quotes) > 0): ?>
         <ul class="space-y-6">
        <?php foreach ($quotes as $quote): ?>
            <li class="bg-indigo-50 border-l-4 border-indigo-500 p-4 text-lg text-gray-800 shadow-lg rounded-lg mb-4">
    <blockquote class="italic">
            <a href="<?php echo url('quote/' . $quote['id']); ?>" class="block">
                <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?>
            </a>
        </blockquote>
    </li>

        <?php endforeach; ?>
    </ul>

        <?php else: ?>
            <p class="text-lg text-gray-600">No quotes available for this author.</p>
        <?php endif; ?>
    </section>


</body>
</html>
<?php include __DIR__ . '/../components/footer.php'; ?>
