
<!-- Navbar -->
<nav class="bg-blue-600 text-white shadow-lg">
  <div class="container mx-auto px-4 py-3">
    <div class="flex justify-between items-center">
      <!-- Logo -->
      <a href="<?php echo url('admin/dashboard'); ?>" class="text-lg md:text-xl font-bold">
        Admin Dashboard
      </a>

      <!-- Mobile Menu Button -->
      <button id="mobile-menu-btn" class="md:hidden focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Desktop Nav Links -->
      <div class="hidden md:flex md:items-center md:space-x-6">
        <a href="<?php echo url('admin/analytics'); ?>" class="hover:underline transition">Analytics</a>
        <a href="<?php echo url('blogs'); ?>" class="hover:underline transition">Blogs</a>
        <a href="<?php echo url('admin/allquotes'); ?>" class="hover:underline transition">All Quotes</a>
        <a href="<?php echo url('admin/create-blog'); ?>" class="hover:underline transition">Create Blog</a>
        <a href="<?php echo url('admin/logout'); ?>" class="hover:underline transition">Logout</a>
      </div>
    </div>

    <!-- Mobile Nav Links (Hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden mt-4 pb-2 space-y-2">
      <a href="<?php echo url('admin/analytics'); ?>" class="block py-2 px-4 hover:bg-blue-700 rounded transition">Analytics</a>
      <a href="<?php echo url('blogs'); ?>" class="block py-2 px-4 hover:bg-blue-700 rounded transition">Blogs</a>
      <a href="<?php echo url('admin/allquotes'); ?>" class="block py-2 px-4 hover:bg-blue-700 rounded transition">All Quotes</a>
      <a href="<?php echo url('admin/create-blog'); ?>" class="block py-2 px-4 hover:bg-blue-700 rounded transition">Create Blog</a>
      <a href="<?php echo url('admin/logout'); ?>" class="block py-2 px-4 hover:bg-blue-700 rounded transition">Logout</a>
    </div>
  </div>
</nav>

<script>
// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
  const menuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const menuIcon = document.getElementById('menu-icon');
  const closeIcon = document.getElementById('close-icon');

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
      menuIcon.classList.toggle('hidden');
      closeIcon.classList.toggle('hidden');
    });
  }
});
</script>