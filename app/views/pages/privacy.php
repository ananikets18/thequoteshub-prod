<?php include __DIR__ . '/../components/header.php'; ?>
<?php
$baseUrl = 'https://thequoteshub.in/'; // Replace with your actual base URL
?>
<!-- Header Section -->
<header class="bg-white shadow-lg py-6">
  <div class="container mx-auto px-6">
    <h1 class="text-4xl font-bold text-center text-gray-900">Privacy Policy</h1>
    <p class="text-center text-lg text-gray-600 mt-2">Your privacy is important to us. Learn how we protect your data.
    </p>
  </div>
</header>

<!-- Branding Image Section -->
<section class="py-12 bg-gray-100">
  <div class="container mx-auto px-6 text-center">
    <img src="<?php echo $baseUrl; ?>public/uploads/images/logo.svg" alt="Privacy Protection"
      class="mx-auto rounded-lg shadow-md">
    <p class="mt-4 text-lg text-gray-600">
      At TheQuotesHub, we are committed to safeguarding your privacy and ensuring transparency.
    </p>
  </div>
</section>

<!-- Privacy Policy Content -->
<section class="py-12">
  <div class="container mx-auto px-6">
    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">1. Information We Collect</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        We collect personal information that you provide when you sign up, post quotes, or interact with our platform.
        This may include your name, email address, and any other details you voluntarily share.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">2. How We Use Your Information</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        TheQuotesHub uses the collected information to improve your experience on our site. This includes personalizing
        content, responding to inquiries, and sending occasional updates or promotional materials.
      </p>
      <p class="mt-4 text-gray-600 leading-relaxed">
        We do not sell or share your personal data with third parties without your consent, except where required by
        law.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">3. Cookies and Tracking</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        We use cookies to enhance your browsing experience. Cookies help us understand user preferences and deliver
        content that’s relevant to you. You can disable cookies in your browser settings, but doing so may limit your
        ability to use some features of the site.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">4. Data Security</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        We take appropriate measures to protect your personal data from unauthorized access, alteration, or disclosure.
        However, no method of transmission over the internet is 100% secure. We strive to protect your information but
        cannot guarantee its absolute security.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">5. Third-Party Services</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        TheQuotesHub may use third-party services, such as analytics tools, that collect, monitor, and analyze data to
        help us improve our services. These third-party service providers have their own privacy policies, and we
        encourage you to review them.
      </p>
      <div class="mt-6">
        <img src="<?php echo $baseUrl; ?>public/uploads/images/logo.svg" alt="Third-Party Services"
          class="mx-auto rounded-lg shadow-md">
      </div>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">6. Changes to This Policy</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        TheQuotesHub reserves the right to update this Privacy Policy at any time. Any changes will be posted on this
        page, and you will be notified of significant updates. Your continued use of the website after such
        modifications indicates your acceptance of the revised terms.
      </p>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">7. Contact Us</h2>
      <p class="mt-4 text-gray-600 leading-relaxed">
        If you have any questions or concerns about our Privacy Policy or how your information is handled, please reach
        out to us at: <strong>privacy@thequoteshub.in</strong>.
      </p>
    </div>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-white py-6">
  <div class="container mx-auto px-6 text-center">
    <p class="text-gray-500 text-sm">© <span><?php echo date("Y"); ?></span>  TheQuotesHub. All Rights Reserved.</p>
  </div>
</footer>
<?php include __DIR__ . '/../components/footer.php'; ?>