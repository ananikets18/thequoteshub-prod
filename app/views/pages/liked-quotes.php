<?php
include __DIR__ . '/../components/header.php';
include_once  __DIR__ . '/../../../config/utilis.php';

$baseUrl = getBaseUrl(); // Replace with your actual base URL

?>


<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: /login");
  exit();
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
  <h1 class="text-4xl font-bold text-gray-900 mb-6 text-center">Quotes You've Liked ❤️</h1>
  <div id="liked-quotes-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php foreach ($likedQuotes as $quote): ?>
      <a href="<?php echo $baseUrl; ?>quote/<?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['id'])); ?>" class="block bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg shadow-lg transform transition duration-300 hover:scale-105 hover:bg-gray-200">
        <p class="text-xl font-semibold text-gray-800 mb-2"><?= decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?></p>
        <p class="text-sm font-medium text-gray-600">— <?= htmlspecialchars($quote['author_name']); ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
