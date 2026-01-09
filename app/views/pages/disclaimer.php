<?php include __DIR__ . '/../components/header.php'; ?>
<?php
$baseUrl = 'https://thequoteshub.in/'; // Replace with your actual base URL
?>
<!-- Header Section -->
<header class="bg-white shadow-lg py-6">
  <div class="container mx-auto px-6">
    <h1 class="text-4xl font-bold text-center text-gray-900">Disclaimer</h1>
    <p class="text-center text-lg text-gray-600 mt-2">Understand the terms of use for TheQuotesHub.</p>
  </div>
</header>

<!-- Branding Image Section -->
<section class="py-12 bg-gray-100">
  <div class="container mx-auto px-6 text-center">
    <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="TheQuotesHub Branding"
      class="mx-auto rounded-lg shadow-md">
    <p class="mt-4 text-lg text-gray-600">
      TheQuotesHub, part of Project X, is dedicated to sharing thoughtful content. Please read our disclaimer below.
    </p>
  </div>
</section>

<!-- Disclaimer Content -->
<section class="py-12">
  <div class="container mx-auto px-6">
    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">1. General Information</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        The content published on TheQuotesHub is for informational purposes only. While we strive to ensure the accuracy
        of the information, we make no warranties or representations about the completeness, reliability, or accuracy of
        the content.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">2. No Professional Advice</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        The content shared on TheQuotesHub does not constitute professional advice of any kind. Any reliance on the
        information provided is at your own risk. We encourage users to seek professional assistance when necessary.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">3. External Links</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        Our website may contain links to third-party websites or services. These external sites are not operated by
        TheQuotesHub, and we are not responsible for their content or privacy practices. Please review the terms and
        privacy policies of any third-party sites you visit.
      </p>
      <div class="mt-6">
        <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="External Links"
          class="mx-auto rounded-lg shadow-md">
      </div>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">4. Limitation of Liability</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        TheQuotesHub will not be held liable for any damages, including direct or indirect losses, that arise from the
        use or inability to use our platform or any content provided on the site. Users assume full responsibility for
        their actions based on the information available on the platform.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">5. Updates and Modifications</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        We reserve the right to modify or update this disclaimer at any time. Any changes will be posted on this page,
        and your continued use of the site will constitute acceptance of the revised terms.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">6. Contact Information</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        If you have any questions or concerns regarding this disclaimer or any other aspect of TheQuotesHub, feel free
        to contact us at: <strong>support@thequoteshub.in</strong>.
      </p>
    </div>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-white py-6">
  <div class="container mx-auto px-6 text-center">
    <a href="<?php echo getBaseUrl(); ?>" class="text-gray-500 text-sm">© <span><?php echo date("Y"); ?></span>  TheQuotesHub. All Rights Reserved.</a>
  </div>
</footer>

<?php include __DIR__ . '/../components/footer.php'; ?>