</footer>

<?php
$baseUrl = getBaseUrl(); // Replace with your actual base URL
?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>public/assets/js/script.js" defer></script>
<script>
   $(document).ready(function() {
      // Show/hide dropdown on click for both mobile and desktop
      $('#profileButton, #desktopProfileButton').click(function(e) {
        e.preventDefault();
        $('#profileDropdown, #desktopProfileDropdown').toggleClass('hidden');
      });

      // Hide dropdown when clicking outside
      $(document).click(function(event) {
        if (!$(event.target).closest(
            '#profileDropdown, #desktopProfileDropdown, #profileButton, #desktopProfileButton').length) {
          $('#profileDropdown, #desktopProfileDropdown').addClass('hidden');
        }
      });
    });

</script>
</body>
</html>
