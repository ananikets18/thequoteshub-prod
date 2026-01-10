<?php include __DIR__ . '/../components/header.php'; ?>
<?php
$baseUrl = getBaseUrl();
?>
<!-- Header Section -->
<header class="bg-white shadow-lg py-6">
  <div class="container mx-auto px-6">
    <h1 class="text-4xl font-bold text-center text-gray-900">About Us</h1>
    <p class="text-center text-lg text-gray-600 mt-2">Learn more about TheQuotesHub and our mission.</p>
  </div>
</header>

<!-- Branding Image Section -->
<section class="py-12 bg-gray-100">
  <div class="container mx-auto px-6 text-center">
    <img src="<?php echo $baseUrl; ?>public/uploads/images/logo.svg" alt="TheQuotesHub Logo"
      class="mx-auto rounded-lg shadow-md" style="max-width: 300px;">
    <p class="mt-4 text-lg text-gray-600">
      TheQuotesHub is a platform dedicated to sharing wisdom, inspiration, and thoughtful quotes from around the world.
    </p>
  </div>
</section>

<!-- About Content -->
<section class="py-12">
  <div class="container mx-auto px-6">
    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">Our Mission</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        At TheQuotesHub, we believe in the power of words to inspire, motivate, and transform lives. Our mission is to
        create a community where people can discover, share, and connect through meaningful quotes.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">What We Offer</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        TheQuotesHub provides a platform for users to:
      </p>
      <ul class="mt-4 list-disc list-inside text-gray-600 leading-relaxed">
        <li>Discover inspiring quotes from various authors and categories</li>
        <li>Share your own favorite quotes with the community</li>
        <li>Connect with like-minded individuals who appreciate wisdom</li>
        <li>Save and organize your favorite quotes for easy access</li>
        <li>Explore quotes by category, author, or popularity</li>
      </ul>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">Our Community</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        TheQuotesHub is more than just a collection of quotes—it's a vibrant community of quote enthusiasts, writers,
        and thinkers. We encourage respectful dialogue, creative expression, and the sharing of wisdom that can
        positively impact lives.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">Join Us</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        Whether you're looking for daily inspiration, want to share your favorite quotes, or simply enjoy reading
        thought-provoking content, TheQuotesHub welcomes you. Create an account today and become part of our growing
        community!
      </p>
      <div class="mt-6 text-center">
        <a href="<?php echo $baseUrl; ?>register"
          class="inline-block px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition">
          Join TheQuotesHub
        </a>
      </div>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">Contact Us</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        Have questions or feedback? We'd love to hear from you! Reach out to us through our 
        <a href="<?php echo $baseUrl; ?>contact" class="text-emerald-600 hover:text-emerald-700 font-medium">contact page</a>
        or send us an email.
      </p>
    </div>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-white py-6">
  <div class="container mx-auto px-6 text-center">
    <p class="text-gray-500 text-sm">© <span><?php echo date("Y"); ?></span> TheQuotesHub. All Rights Reserved.</p>
  </div>
</footer>

<?php include __DIR__ . '/../components/footer.php'; ?>
