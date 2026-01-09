<?php
/**
 * Quotes Index Page
 * Acts as the View Controller using the new Template Engine
 */

require_once __DIR__ . '/../../core/View.php';
use App\Core\View;

// Capture all variables currently in scope (passed from Controller)
$data = get_defined_vars();

// Configure page-specific data
$data['pageTitle'] = 'QuotesHub - Share Your Wisdom: Create, Inspire, and Connect Through Quotes! 💬🌟';
$data['pageDescription'] = 'Discover inspiring quotes, share your wisdom, and connect with fellow quote enthusiasts';
$data['pageKeywords'] = 'quotes, inspiration, wisdom, share quotes, quote of the day';
$data['additionalCSS'] = ['public/assets/css/quotes-index.css'];
$data['additionalJS'] = ['public/assets/js/quotes-index.js'];
$data['useModules'] = true;

// Render the view using the Template Engine
try {
    echo View::make('quotes/content/quotes-index-content', $data);
} catch (Exception $e) {
    echo "Error rendering view: " . $e->getMessage();
}
