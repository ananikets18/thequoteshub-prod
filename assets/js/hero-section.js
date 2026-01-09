/**
 * Hero Section JavaScript
 * Handles quote rotation, animations, and interactions
 */

(function() {
  'use strict';
  
  // Configuration
  const CONFIG = {
    rotationInterval: 10000, // 10 seconds per quote
    animationDuration: 800,
    enableAutoRotation: true
  };
  
  // State
  let currentQuoteIndex = 0;
  let quotes = [];
  let rotationTimer = null;
  let isTransitioning = false;
  
  /**
   * Initialize the hero section
   */
  function initHeroSection() {
    console.log('Initializing Hero Section...');
    
    // Get all quote elements
    const quoteElements = document.querySelectorAll('.quote-item');
    if (quoteElements.length === 0) {
      console.warn('No quotes found in hero section');
      return;
    }
    
    // Extract quote data
    quoteElements.forEach((element, index) => {
      const quoteText = element.querySelector('.quote-text')?.dataset.text || '';
      const authorName = element.querySelector('.hero-author')?.textContent.trim().replace('— ', '') || '';
      const quoteId = element.dataset.quoteId || '';
      
      quotes.push({
        text: quoteText,
        author: authorName,
        id: quoteId,
        element: element
      });
    });
    
    console.log(`Loaded ${quotes.length} quote(s)`);
    
    // Start auto-rotation if enabled and multiple quotes exist
    if (CONFIG.enableAutoRotation && quotes.length > 1) {
      startAutoRotation();
    }
    
    // Add search functionality
    initSearch();
    
    // Add smooth scroll for scroll indicator
    initScrollIndicator();
    
    // Add particle interactions
    initParticleInteractions();
  }
  
  /**
   * Fetch additional quotes for rotation
   */
  async function fetchRotationQuotes() {
    try {
      // You can implement an API endpoint to fetch random quotes
      // For now, we'll use the single quote of the day
      console.log('Using Quote of the Day only');
    } catch (error) {
      console.error('Error fetching quotes:', error);
    }
  }
  
  /**
   * Start automatic quote rotation
   */
  function startAutoRotation() {
    if (rotationTimer) {
      clearInterval(rotationTimer);
    }
    
    rotationTimer = setInterval(() => {
      rotateToNextQuote();
    }, CONFIG.rotationInterval);
    
    console.log('Auto-rotation started');
  }
  
  /**
   * Stop automatic quote rotation
   */
  function stopAutoRotation() {
    if (rotationTimer) {
      clearInterval(rotationTimer);
      rotationTimer = null;
      console.log('Auto-rotation stopped');
    }
  }
  
  /**
   * Rotate to next quote
   */
  function rotateToNextQuote() {
    if (isTransitioning || quotes.length <= 1) return;
    
    isTransitioning = true;
    const currentQuote = quotes[currentQuoteIndex];
    const nextIndex = (currentQuoteIndex + 1) % quotes.length;
    const nextQuote = quotes[nextIndex];
    
    // Fade out current quote
    currentQuote.element.classList.remove('active');
    
    // Wait for fade out animation
    setTimeout(() => {
      // Update index
      currentQuoteIndex = nextIndex;
      
      // Fade in next quote
      nextQuote.element.classList.add('active');
      
      // Reset transition flag
      setTimeout(() => {
        isTransitioning = false;
      }, CONFIG.animationDuration);
    }, CONFIG.animationDuration);
  }
  
  /**
   * Initialize search functionality
   */
  function initSearch() {
    const searchInput = document.querySelector('.hero-search input');
    if (!searchInput) return;
    
    // Add focus effects
    searchInput.addEventListener('focus', () => {
      searchInput.parentElement.parentElement.classList.add('search-focused');
    });
    
    searchInput.addEventListener('blur', () => {
      searchInput.parentElement.parentElement.classList.remove('search-focused');
    });
    
    // Optional: Add autocomplete or suggestions
    // This can be implemented later with an AJAX call
  }
  
  /**
   * Initialize scroll indicator
   */
  function initScrollIndicator() {
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (!scrollIndicator) return;
    
    scrollIndicator.addEventListener('click', () => {
      const mainContent = document.querySelector('.container.mx-auto.p-4');
      if (mainContent) {
        mainContent.scrollIntoView({ 
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
    
    // Hide scroll indicator when user scrolls
    let lastScrollTop = 0;
    window.addEventListener('scroll', () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      if (scrollTop > 100 && scrollIndicator) {
        scrollIndicator.style.opacity = '0';
      } else if (scrollIndicator) {
        scrollIndicator.style.opacity = '1';
      }
      
      lastScrollTop = scrollTop;
    });
  }
  
  /**
   * Add interactive effects to particles
   */
  function initParticleInteractions() {
    const particles = document.querySelectorAll('.particle');
    if (particles.length === 0) return;
    
    // Add mouse interaction (parallax effect)
    document.querySelector('.hero-section')?.addEventListener('mousemove', (e) => {
      const { clientX, clientY } = e;
      const centerX = window.innerWidth / 2;
      const centerY = window.innerHeight / 2;
      
      particles.forEach((particle, index) => {
        const speed = (index + 1) * 0.5;
        const x = (clientX - centerX) / centerX * speed;
        const y = (clientY - centerY) / centerY * speed;
        
        particle.style.transform = `translate(${x}px, ${y}px)`;
      });
    });
  }
  
  /**
   * Cleanup on page unload
   */
  function cleanup() {
    stopAutoRotation();
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroSection);
  } else {
    initHeroSection();
  }
  
  // Cleanup on page unload
  window.addEventListener('beforeunload', cleanup);
  
  // Expose API for external control
  window.HeroSection = {
    rotateNext: rotateToNextQuote,
    startRotation: startAutoRotation,
    stopRotation: stopAutoRotation,
    getQuotes: () => quotes,
    getCurrentQuote: () => quotes[currentQuoteIndex]
  };
  
})();
