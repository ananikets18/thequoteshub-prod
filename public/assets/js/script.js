// Share Modal
$(document).ready(function () {
  const quoteText = "This is the quote text to be shared"; // Dynamically set this for each quote
  const quoteUrl = window.location.href; // Get the current page URL
  const encodedQuote = encodeURIComponent(quoteText);
  const encodedUrl = encodeURIComponent(quoteUrl);

  // Facebook Share
  $("#facebookShare").attr(
    "href",
    `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`
  );

  // Twitter Share
  $("#twitterShare").attr(
    "href",
    `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedQuote}`
  );

  // LinkedIn Share
  $("#linkedinShare").attr(
    "href",
    `https://www.linkedin.com/shareArticle?mini=true&url=${encodedUrl}&title=${encodedQuote}`
  );

  // Open modal when a share button is clicked
  $(".shareButton").on("click", function () {
    $(".shareModal").removeClass("hidden").addClass("flex");
  });

  // Close modal when close button or Escape key is pressed
  $(".closeModal").on("click", function () {
    $(".shareModal").removeClass("flex").addClass("hidden");
  });

  $(document).on("keydown", function (e) {
    if (e.key === "Escape") {
      $(".shareModal").removeClass("flex").addClass("hidden");
    }
  });
});

