 <?php include __DIR__ . '/../components/header.php'; ?>
 <?php
  $baseUrl = 'https://thequoteshub.in/'; // Replace with your actual base URL
  ?>

 <!-- Header Section -->
 <header class="bg-white shadow-lg py-6">
   <div class="container mx-auto px-6">
     <h1 class="text-4xl font-bold text-center text-gray-900">Terms and Conditions</h1>
     <p class="text-center text-lg text-gray-600 mt-2">Welcome to Quoteshub Please read our terms carefully.</p>
   </div>
 </header>

 <!-- Branding Image Section -->
 <section class="py-12 bg-gray-100">
   <div class="container mx-auto px-6 text-center">
     <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="Quoteshub Branding"
       class="mx-auto rounded-lg shadow-md">
     <p class="mt-4 text-lg text-gray-600">
       Quoteshub is a unique platform designed to empower creative minds through quotes and ideas.
     </p>
   </div>
 </section>

 <!-- Terms and Conditions Content -->
 <section class="py-12">
   <div class="container mx-auto px-6">
     <div class="mb-8">
       <h2 class="text-2xl font-semibold text-gray-800">1. Acceptance of Terms</h2>
       <p class="mt-4 text-gray-600 leading-relaxed">
         By accessing and using our website, you agree to comply with and be bound by the following terms and
         conditions. If you disagree with any part of the terms, you may not use the website.
       </p>
     </div>

     <div class="mb-8">
       <h2 class="text-2xl font-semibold text-gray-800">2. Intellectual Property Rights</h2>
       <p class="mt-4 text-gray-600 leading-relaxed">
         All content, including logos, images, and text, provided on this website is owned by or licensed to Project X.
         You may not use any material from the website without explicit permission from the respective owner.
       </p>
       <div class="mt-6">
         <img src="<?php echo $baseUrl; ?>public/uploads/images/logo_clean.svg" alt="Quoteshub Logos"
           class="mx-auto rounded-lg shadow-md">
       </div>
     </div>

     <div class="mb-8">
       <h2 class="text-2xl font-semibold text-gray-800">3. User Responsibilities</h2>
       <p class="mt-4 text-gray-600 leading-relaxed">
         As a user of Project X, you are responsible for any content you post or share. Ensure that your contributions
         are respectful, do not violate intellectual property rights, and do not contain prohibited or inappropriate
         content.
       </p>
     </div>

     <div class="mb-8">
       <h2 class="text-2xl font-semibold text-gray-800">4. Limitation of Liability</h2>
       <p class="mt-4 text-gray-600 leading-relaxed">
         Project X will not be liable for any damages that may occur as a result of using our platform. This includes,
         but is not limited to, any direct, indirect, or incidental damages that arise from the use of our services or
         content.
       </p>
     </div>

     <div class="mb-8">
       <h2 class="text-2xl font-semibold text-gray-800">5. Modifications to Terms</h2>
       <p class="mt-4 text-gray-600 leading-relaxed">
         We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting.
         Your continued use of the site signifies your acceptance of any updated terms.
       </p>
     </div>

     <div class="mb-8">
       <h2 class="text-2xl font-semibold text-gray-800">6. Termination of Use</h2>
       <p class="mt-4 text-gray-600 leading-relaxed">
         Project X reserves the right to terminate your access to the website at any time without notice if you violate
         any terms or conditions.
       </p>
     </div>
   </div>
 </section>

 <!-- Footer Section -->
 <footer class="bg-white py-6">
   <div class="container mx-auto px-6 text-center">
     <a href="<?php echo getBaseUrl(); ?>" class="text-gray-500 text-sm">© <span><?php echo date("Y"); ?></span>  theQuoteshub. All Rights Reserved.</a>
   </div>
 </footer>

 <?php include __DIR__ . '/../components/footer.php'; ?>