<?php include_once  __DIR__ . '/../../../config/utilis.php'; ?>
<?php
$baseUrl = getBaseUrl(); // Replace with your actual base URL
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Quote Tool Quoteshub - Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟 </title>
    
    <?php $baseUrl = getBaseUrl(); ?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseUrl; ?>public/uploads/images/favicon/favicon-16x16.png">

    <!--<link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/style.css">-->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>public/assets/css/content_create.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
       <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WFQ8T199Z6"></script>
    <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-WFQ8T199Z6');
    </script>
    
    <style>
          .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border-left-color: #09f;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    </style>
  </head>
<body class="custom-body">
<nav class="custom-nav">
  <div class="custom-container">
    <div class="custom-flex">
      <a href="<?php echo $baseUrl; ?>" class="custom-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
          class="custom-icon">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m-6-6 6 6-6 6" />
        </svg>
        Back to Home
      </a>
    </div>
  </div>
</nav>

  
  <div class="quote-card-wrapper">
    <div class="white-container" id="white_container">
      <?php if ($quote): ?>
        <section class="quote-card" id="quote-card">
           <div class="quote-card-header">
            <div class="user-info">
              <?php
                $userImageFile = $quote['user__img'];
                $userImagePath = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/users/' . $userImageFile;
                $imageSrc = !empty($userImageFile) && file_exists($userImagePath)
                  ? $baseUrl . 'public/uploads/users/' . $userImageFile
                  : $baseUrl . 'public/uploads/authors_images/placeholder.png';
              ?>
              <div class="user-image">
                <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8'); ?>" alt="User" />
              </div>
              <p class="user-name">
                <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['user_name'])); ?>
              </p>
            </div>
            <!--<span class="created-time">-->
            <!--  Created on <?php echo convertUtcToIst($quote['created_at']); ?>-->
            <!--</span>-->
          </div>
          <div class="quote-content">
            <blockquote class="quote-text" id="quoteText">
              " <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['quote_text'])); ?> "
            </blockquote>
            <p class="author-name" id="authorName">— <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['author_name'])); ?></p>
          </div>
          <div class="flex space-between"> 
          <div class="category">
            <p class="category-item"># <span class="category-name"><?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quote['category_name'])); ?></span></p>
          </div>
          <div class="interaction-counts">
            <p class="like-count" title="Likes">
                <svg  fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                 <span><?php echo htmlspecialchars($quote['like_count'], ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
            
             <p class="save-count"  title="Saves"> 
             
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                      clip-rule="evenodd" />
                  </svg>
             <span><?php echo htmlspecialchars($quote['save_count'], ENT_QUOTES, 'UTF-8'); ?></span></p>
             
             
         </div>
          </div>
        </section>
      <?php else: ?>
        <h1 class="no-quote">Quote not found.</h1>
      <?php endif; ?>
    </div>
  </div>
  
  <!-- Color Picker -->
<section class="toolbar">
  <div>
    <div class="gradient-options">
      <div class="tool_color_picker">
        <input type="color" id="bgColorPicker" value="#ffffff" title="Pick a background color" />
      </div>
      <div 
        class="gradient" 
        data-gradient="linear-gradient(45deg, #ff9a9e, #fad0c4)" 
        style="background: linear-gradient(45deg, #ff9a9e, #fad0c4);" 
        title="Gradient: Pink to Light Peach">
      </div>
      <div 
        class="gradient" 
        data-gradient="linear-gradient(90deg, #a18cd1, #fbc2eb)" 
        style="background: linear-gradient(90deg, #a18cd1, #fbc2eb);" 
        title="Gradient: Purple to Light Pink">
      </div>
      <div 
        class="gradient" 
        data-gradient="linear-gradient(120deg, #fddb92, #d1fdff)" 
        style="background: linear-gradient(120deg, #fddb92, #d1fdff);" 
        title="Gradient: Yellow to Light Blue">
      </div>
      <div 
        class="gradient" 
        data-gradient="linear-gradient(150deg, #ffecd2, #fcb69f)" 
        style="background: linear-gradient(150deg, #ffecd2, #fcb69f);" 
        title="Gradient: Cream to Peach">
      </div>
      <div 
        class="gradient" 
        data-gradient="linear-gradient(210deg, #a1c4fd, #c2e9fb)" 
        style="background: linear-gradient(210deg, #a1c4fd, #c2e9fb);" 
        title="Gradient: Blue to Light Blue">
      </div>
    </div>
  </div>
  <div class="aspect-ratio-dropdown">
    <select id="dimension-select" class="styled-select" title="Select aspect ratio">
      <option value="1080x1920" title="Instagram Portrait Post">Instagram Portrait Story</option>
      <option value="1920x1080" title="Instagram Landscape Post">Instagram Landscape Post</option>
    </select>
  </div>
</section>



  <!-- Download Button -->
  <div class="download-button-wrapper">
    <div class="download-button-container">
      <button class="download-button" onclick="downloadQuote()">Download Quote Card</button>
    </div>
  </div>
  
  <div id="loader" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
    <div class="spinner"></div> <!-- Add a spinner animation -->
    <p>Generating your quote...</p>
</div>


<?php
$baseUrl = getBaseUrl(); // Replace with your actual base URL
?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>public/assets/js/html2canvas.min.js" defer></script>
  <script>
document.addEventListener('DOMContentLoaded', () => {
    const dimensionSelector = document.getElementById('dimension-select'); // Dimension selector for custom dimensions
    const whiteContainer = document.getElementById('white_container');
    const colorPicker = document.getElementById('bgColorPicker');
    const gradientOptions = document.querySelectorAll('.gradient');

    // Store the original dimensions of white_container
    const originalWidth = whiteContainer.offsetWidth;
    const originalHeight = whiteContainer.offsetHeight;

    // Listen for changes in dimension selection
    dimensionSelector.addEventListener('change', (e) => {
        const selectedDimension = e.target.value; 
        const [width, height] = selectedDimension.split("x").map(Number);

        // Apply the new dimensions directly to the white container
        whiteContainer.style.width = `${width}px`;
        whiteContainer.style.height = `${height}px`;

        // Ensure no scaling is applied
        whiteContainer.style.transform = `scale(1)`; // Reset scale to avoid compounded scaling
    });

    // Color picker for background
    colorPicker.addEventListener('input', (event) => {
        whiteContainer.style.background = event.target.value;
    });

    // Gradient options for background
    gradientOptions.forEach((gradient) => {
        gradient.addEventListener('click', () => {
            const selectedGradient = gradient.getAttribute('data-gradient');
            whiteContainer.style.background = selectedGradient;
        });
    });

    // Update downloadQuote to use the selected dimensions for download
window.downloadQuote = function () {
    const loader = document.getElementById('loader'); // Loader element
    const selectedDimension = dimensionSelector.value; // Get selected dimension
    const [width, height] = selectedDimension.split("x").map(Number);

    // Temporarily apply the selected dimensions for download
    const tempWidth = whiteContainer.style.width;
    const tempHeight = whiteContainer.style.height;
    whiteContainer.style.width = `${width}px`;
    whiteContainer.style.height = `${height}px`;

    // Show the loader
    loader.style.display = 'block';

    // Generate the image using html2canvas
    html2canvas(whiteContainer, { scale: 2, useCORS: true }).then((canvas) => {
        const link = document.createElement('a');
        link.download = 'quote.png';
        link.href = canvas.toDataURL('image/png');
        link.click();

        // Reset the dimensions after downloading
        whiteContainer.style.width = tempWidth;
        whiteContainer.style.height = tempHeight;

        // Hide the loader
        loader.style.display = 'none';
    }).catch((error) => {
        console.error('Error generating the image:', error);

        // Hide the loader even if there's an error
        loader.style.display = 'none';
    });
};

});



  </script>
</body>
</html>
