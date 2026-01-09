<?php include __DIR__ . '/../components/header.php'; ?>
<?php $baseUrl = getBaseUrl(); ?>
<style>
    .relative {
        position: relative;
    }

    .dark-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: rgba(0, 0, 0, 0.4); /* Darkening the bottom */
        border-radius: 0 0 10px 10px; /* Optional: round the corners */
        pointer-events: none; /* Ensures the overlay doesn't block interactions */
    }

    h1 {
        font-family: "Inter", serif;
        font-weight: 400;
        font-style: normal;
    }

    .user_profile_icon img {
        width: 100px; /* Tailored size for author images */
        height: 100px;
        border-radius: 20%;
        object-fit: cover;
        border: 2px solid #e0e0e0; /* Add a subtle border */
    }
</style>
<body style="background-color: #F4F2EE;">
<?php include_once __DIR__ . '/../../../config/utilis.php'; ?> 

<div class="relative bg-cover bg-center" style="background-image: url('<?php echo url('public/uploads/images/bg-img.png'); ?>'); height: 300px;">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div> <!-- Dark overlay -->
    <h1 class="text-2xl lg:text-4xl font-bold text-center text-white absolute z-10 bottom-4 left-1/2 transform -translate-x-1/2 tracking-wider bg-emerald-700 px-4 py-2 rounded-md">
        Authors
    </h1>
</div>


<div class="container mx-auto p-6">
    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($authors as $author): ?>
            <li class="bg-white shadow-md rounded-lg p-4 hover:bg-indigo-50 transition duration-200 flex items-center">
                <div class="user_profile_icon">
                    <?php
                    // Get author image filename and determine the file path
                    $authorImageFile = $author['author_img'];
                    $authorImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/authors_images/' . $authorImageFile;

                    // Determine the image source, defaulting to a placeholder if the file doesn't exist or is empty
                    $imageSrc = !empty($authorImageFile) && file_exists($authorImagePath)
                        ? $baseUrl . 'public/uploads/authors_images/' . $authorImageFile
                        : $baseUrl . 'public/uploads/authors_images/placeholder.png';
                    ?>
                    <img 
                        src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" 
                        alt="<?php echo htmlspecialchars($author['author_name'], ENT_QUOTES, 'UTF-8'); ?>" 
                    />
                </div>
                <a 
                    href="<?php echo url('authors/' . urlencode($author['author_name'])); ?>" 
                    class="ml-4 text-lg font-medium text-indigo-700 hover:text-indigo-900"
                >
                    <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($author['author_name'])); ?>  
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>
<?php include __DIR__ . '/../components/footer.php'; ?>
