<?php include __DIR__ . '/../components/header.php'; ?>
<?php
$baseUrl = getBaseUrl(); // Replace with your actual base URL
?>
<!-- Header Section -->
<header class="bg-white shadow-lg py-6">
  <div class="container mx-auto px-6">
    <h1 class="text-4xl font-bold text-center text-gray-900">About Us</h1>
    <p class="text-center text-lg text-gray-600 mt-2">Get to know more about the journey of Project X</p>
  </div>
</header>

<!-- Our Mission Section -->
<section class="py-12 bg-gray-100">
  <div class="container mx-auto px-6">
    <div class="text-center">
      <h2 class="text-3xl font-semibold text-gray-800">Our Mission</h2>
      <p class="mt-4 text-lg leading-relaxed text-gray-600">
        At Project X, we are dedicated to creating a platform where thoughts, inspiration, and creativity thrive. Our
        mission is to bring people together through meaningful quotes, insightful blogs, and social interactions. We
        believe in fostering a community that connects and uplifts each other in the digital age.
      </p>
    </div>
  </div>
</section>

<!-- Project X Journey -->
<section class="py-12">
  <div class="container mx-auto px-6">
    <div class="text-center">
      <h2 class="text-3xl font-semibold text-gray-800">The Project X Journey</h2>
      <p class="mt-4 text-lg text-gray-600 leading-relaxed">
        Project X started with the vision of making social media more inspiring, creative, and meaningful. Our team is
        passionate about designing a platform where users can share and discover thought-provoking quotes, engage in
        blogs, and find a sense of belonging.
      </p>
    </div>

    <div class="flex flex-col lg:flex-row lg:gap-12 lg:mt-8 mt-6">
      <div class="lg:w-1/2">
        <img src="<?php echo $baseUrl; ?>public/uploads/images/team.png" alt="Our journey"
          class="rounded-lg shadow-md responsive">
      </div>
      <div class="lg:w-1/2 mt-6 lg:mt-0">
        <h3 class="text-xl font-semibold text-gray-700">Our Team</h3>
        <p class="mt-4 text-gray-600 leading-relaxed">
          We are a small team of developers, designers, and dreamers working relentlessly to create a space that
          enhances how people share and consume knowledge. With a background in social media development, we focus on
          delivering a seamless, creative, and visually appealing user experience.
        </p>
        <p class="mt-4 text-gray-600 leading-relaxed">
          Whether it's about the next breakthrough in user engagement or how we can further enhance the platform's
          UI/UX, we’re constantly evolving. Project X is more than just a website—it's a journey.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Our Vision Section -->
<section class="py-12 bg-gray-100">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-3xl font-semibold text-gray-800">Our Vision</h2>
    <p class="mt-4 text-lg text-gray-600">
      Our vision is simple: To create a space where creativity and meaningful conversation can thrive. We envision
      Project X to be the go-to platform for thought leaders, creatives, and everyday users to engage with impactful
      content.
    </p>
  </div>
</section>

<!-- Join Us Section -->
<section class="py-12">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-3xl font-semibold text-gray-800">Join Us on Social Media</h2>
    <p class="mt-4 text-lg text-gray-600">Follow us and become part of the Project X community.</p>

    <div class="mt-8 flex justify-center space-x-6">
      <a href="#" class="text-gray-600 hover:text-blue-500"><i class="fab fa-facebook fa-2x"></i></a>
      <a href="#" class="text-gray-600 hover:text-blue-400"><i class="fab fa-twitter fa-2x"></i></a>
      <a href="#" class="text-gray-600 hover:text-pink-500"><i class="fab fa-instagram fa-2x"></i></a>
      <a href="#" class="text-gray-600 hover:text-red-600"><i class="fab fa-youtube fa-2x"></i></a>
    </div>
  </div>
</section>

<!-- Footer Section -->
<footer class="bg-white py-6">
  <div class="container mx-auto px-6 text-center">
    <p class="text-gray-500 text-sm">© <span><?php echo date("Y"); ?></span> Project X Team. All Rights Reserved.</p>
  </div>
</footer>
<?php include __DIR__ . '/../components/footer.php'; ?>