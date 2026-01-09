<?php
/**
 * Hero Section - Redesigned
 * Compact, impactful hero with Quote of the Day
 */
?>

<!-- Hero Section -->
<section class="hero-section relative w-full overflow-hidden">
  <!-- Gradient Background -->
  <div class="hero-gradient absolute inset-0"></div>
  
  <!-- Animated Particles (Subtle) -->
  <div class="particles-container absolute inset-0">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>
  
  <!-- Hero Content -->
  <div class="hero-content relative z-10 container mx-auto px-4 py-12 md:py-16 lg:py-20">
    
    <!-- Quote of the Day Badge -->
    <div class="flex justify-center mb-8 animate-fade-in-down">
      <div class="hero-badge bg-white/20 backdrop-blur-md border border-white/30 px-6 py-2.5 rounded-full inline-flex items-center shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
        <span class="text-2xl mr-2 animate-pulse">✨</span>
        <span class="text-white font-semibold text-sm md:text-base tracking-wide">Quote of the Day</span>
      </div>
    </div>
    
    <!-- Main Quote Display -->
    <div class="quote-display-wrapper max-w-4xl mx-auto text-center">
      <!-- Quote Container -->
      <div id="quote-rotator" class="quote-rotator min-h-[180px] md:min-h-[220px] flex items-center justify-center">
        
        <?php if (isset($quoteOfTheDay) && $quoteOfTheDay): ?>
        
        <!-- Primary Quote (Quote of the Day) -->
        <div class="quote-item active animate-fade-in-up" data-quote-id="<?php echo $quoteOfTheDay['id']; ?>">
          <blockquote class="hero-quote text-white text-xl md:text-3xl lg:text-4xl font-bold leading-tight mb-6 drop-shadow-2xl">
            <span class="quote-mark text-emerald-200 opacity-70">"</span>
            <span class="quote-text px-2" data-text="<?php echo htmlspecialchars(decodeCleanAndRemoveTags(decodeAndCleanText($quoteOfTheDay['quote_text'])), ENT_QUOTES, 'UTF-8'); ?>">
              <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quoteOfTheDay['quote_text'])); ?>
            </span>
            <span class="quote-mark text-emerald-200 opacity-70">"</span>
          </blockquote>
          
          <div class="hero-author text-white/95 text-base md:text-lg lg:text-xl font-medium mb-6 italic">
            — <?php echo decodeCleanAndRemoveTags(decodeAndCleanText($quoteOfTheDay['author_name'])); ?>
          </div>
          
          <a href="<?php echo $baseUrl; ?>quote/<?php echo urlencode($quoteOfTheDay['id']); ?>" 
             class="inline-flex items-center gap-2 text-white/90 hover:text-white text-sm md:text-base font-medium px-6 py-2.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 hover:border-white/30 transition-all duration-300 hover:scale-105">
            <span>View Details</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
        
        <?php else: ?>
        
        <!-- Fallback if no quote -->
        <div class="quote-item active text-white text-center">
          <p class="text-2xl md:text-3xl font-bold mb-4">Welcome to QuotesHub</p>
          <p class="text-lg md:text-xl opacity-90">Discover, Share, and Get Inspired</p>
        </div>
        
        <?php endif; ?>
        
      </div>
    </div>
    
    <!-- Call to Action Buttons -->
    <div class="hero-cta flex flex-wrap justify-center gap-3 md:gap-4 mt-10 md:mt-12 animate-fade-in-up animation-delay-200">
      <?php if (!$isLoggedIn): ?>
        <a href="<?php echo $baseUrl; ?>register" 
           class="cta-button cta-primary bg-white text-emerald-700 px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-sm md:text-base shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center gap-2">
          <span>Join Community</span>
          <span class="text-lg md:text-xl">🚀</span>
        </a>
        <a href="#discover-section" 
           onclick="event.preventDefault(); document.getElementById('discover-section').scrollIntoView({behavior: 'smooth', block: 'start'}); return false;"
           class="cta-button cta-secondary bg-white/10 backdrop-blur-md border-2 border-white/30 text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-sm md:text-base hover:bg-white/20 hover:border-white/50 transition-all duration-300 cursor-pointer">
          Explore Quotes
        </a>
      <?php else: ?>
        <a href="<?php echo $baseUrl; ?>create-quote" 
           class="cta-button cta-primary bg-white text-emerald-700 px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-sm md:text-base shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center gap-2">
          <span>Create Quote</span>
          <span class="text-lg md:text-xl">✍️</span>
        </a>
        <a href="<?php echo $baseUrl; ?>dashboard" 
           class="cta-button cta-secondary bg-white/10 backdrop-blur-md border-2 border-white/30 text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-sm md:text-base hover:bg-white/20 hover:border-white/50 transition-all duration-300">
          My Dashboard
        </a>
      <?php endif; ?>
    </div>
    
    <!-- Scroll Down Indicator -->
    <div class="scroll-indicator absolute bottom-4 md:bottom-6 left-1/2 -translate-x-1/2 animate-bounce cursor-pointer" onclick="document.getElementById('discover-section').scrollIntoView({behavior: 'smooth'})">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8 text-white/70 hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
      </svg>
    </div>
    
  </div>
</section>
