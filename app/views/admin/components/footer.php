<footer class="bg-white text-gray-700 py-6 border-t  border-gray-400">
  <div class="container mx-auto px-4">
    <!-- Footer Links -->
    <div class="flex flex-wrap justify-center mb-6">
      <a href="<?php echo url('about'); ?>" class="mx-4 text-sm hover:text-emerald-600">About Us</a>
      <a href="<?php echo url('guidelines'); ?>" class="mx-4 text-sm hover:text-emerald-600">Guidelines</a>
      <a href="<?php echo url('terms'); ?>" class="mx-4 text-sm hover:text-emerald-600">Terms of Service</a>
      <a href="<?php echo url('contact'); ?>" class="mx-4 text-sm hover:text-emerald-600">Contact Us</a>
      <a href="<?php echo url('disclaimer'); ?>" class="mx-4 text-sm hover:text-emerald-600">Disclaimer</a>
      <a href="<?php echo url('privacy'); ?>" class="mx-4 text-sm hover:text-emerald-600">Privacy Policy</a>
      <a href="<?php echo url('blog'); ?>" class="mx-4 text-sm hover:text-emerald-600">Blog</a>
      <a href="<?php echo url('feedback'); ?>" class="mx-4 text-sm hover:text-emerald-600">Feedback</a>
    </div>

    <!-- Logo and Copyright -->
    <div class="flex md:flex-row flex-col items-center justify-center space-x-4">
      <a class="flex items-center justify-center space-x-4" href="<?php echo getBaseUrl(); ?>">
          <?php $baseUrlN = getBaseUrl(); ?>
          <img src="<?php echo $baseUrlN; ?>public/uploads/images/logo_clean.svg"
          alt="Website Logo" class="mr-4" />
        <p class="text-xs text-gray-500">&copy; <span><?php echo date("Y"); ?></span> Quoteshub</p>
      </a>
      <p class="text-xs text-gray-500 ml-4">
        Crafted with
        <span class="hover:text-emerald-600">&hearts;</span> for the people
        on the internet
      </p>
    </div>
  </div>
</footer>

<?php include __DIR__ . '/toast.php'; ?>

</body>

</html>