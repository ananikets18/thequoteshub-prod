<?php
/**
 * Get the base URL from environment configuration
 * @return string Base URL with trailing slash
 */
function getBaseUrl() {
    // Load environment if not already loaded
    if (!defined('APP_URL')) {
        require_once __DIR__ . '/env.php';
    }
    return rtrim(APP_URL, '/') . '/';
}

/**
 * Generate a URL for internal links
 * @param string $path Path without leading slash (e.g., 'authors', 'quote/123')
 * @return string Full URL with base path
 */
function url($path = '') {
    $path = ltrim($path, '/');
    return getBaseUrl() . $path;
}

function sanitizeInput($data)
{
  return htmlspecialchars(stripslashes(trim($data)));
}

function validateEmail($email)
{
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function generateCsrfToken()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function validateCsrfToken($token)
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


function sanitize_category($category)
{
  // Step 1: Trim whitespace from both sides
  $category = trim($category);

  // Step 2: Remove unwanted special characters
  // Allow letters, numbers, and spaces only
  $category = preg_replace("/[^a-zA-Z0-9\s]/", "", $category);

  // Step 4: Validate the length of the category name
  if (
    strlen($category) < 3 || strlen($category) > 50
  ) {
    // Handle error: Category name must be between 3 and 50 characters
    // $alert_message = "Category name must be between 3 and 50 characters";
    return false;
  }
  return $category;
}


function formatDateTime($datetime)
{
  try {
    // Create a DateTime object from the string
    $date = new DateTime($datetime);

    // Format the date to the desired format
    // return $date->format('Y-m-d H:i:s'); // This will now be in IST

    return $date->format('d M \a\t h:i A');
    
    
  } catch (Exception $e) {
    // Handle any potential errors (e.g., invalid date format)
    return 'Invalid date';
  }
}


function convertUtcToIst($utcTime) {
    try {
        // Create a DateTime object from the UTC time
        $dateTime = new DateTime($utcTime, new DateTimeZone('UTC'));

        // Set the timezone to IST
        $dateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));

        // Format the date using the formatDateTime function
        return formatDateTime($dateTime->format('Y-m-d H:i:s'));
        
    } catch (Exception $e) {
        // Handle any potential errors (e.g., invalid date format)
        return 'Invalid date';
    }
}



function convertUtcToIstHuman($utcDateTime) {
    // Convert UTC to IST
    $date = new DateTime($utcDateTime, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Asia/Kolkata'));

    // Get current IST time for comparison
    $now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));

    // Calculate the difference between the two dates
    $diff = $now->diff($date);

    // Create human-readable time difference format
    if ($diff->y > 0) {
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    } elseif ($diff->m > 0) {
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    } elseif ($diff->d > 0) {
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    } elseif ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    } elseif ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'just now';
    }
}



function decodeHtmlEntities($text)
{
  try {
    // Decode HTML entities
    return html_entity_decode($text, ENT_QUOTES, 'UTF-8');
  } catch (Exception $e) {
    // Handle any potential errors (e.g., invalid input)
    return 'Decoding error';
  }
}


function decodeAndCleanText($text) {
    try {
        // Decode HTML entities to get plain text
        $decodedText = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        
        // Remove extra whitespace: trim leading/trailing whitespace and reduce multiple spaces to a single space
        $cleanedText = preg_replace('/\s+/', ' ', trim($decodedText));
        
        return $cleanedText;
    } catch (Exception $e) {
        // Handle any potential errors
        return 'Error processing text';
    }
}



function decodeCleanAndRemoveTags($text) {
    try {
        // Decode HTML entities
        $decodedText = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        
        // Remove HTML tags
        $noTagsText = strip_tags($decodedText);
        
        // Remove extra whitespace: trim leading/trailing whitespace and reduce multiple spaces to a single space
        $cleanedText = preg_replace('/\s+/', ' ', trim($noTagsText));
        
        return $cleanedText;
    } catch (Exception $e) {
        // Handle any potential errors
        return 'Error processing text';
    }
}

    function limitWords($text, $limit = 15) {
        // Split the text into an array of words
        $words = explode(' ', $text);
        
        // Limit to the specified number of words and reassemble
        if (count($words) > $limit) {
            $words = array_slice($words, 0, $limit);
        }
        
        // Join the limited words back into a string
        return implode(' ', $words);
    }

    function generateKeywords($title) {
        // Define additional keyword arrays for SEO purposes, excluding "Best"
        $prefixes = ["Top", "Inspirational", "Famous", "Short", "Life-changing","Best"];
        $suffixes = ["for a Positive Life", "to Make You Smile", "for Motivation", "by Famous People", "for Happiness", "and Sayings"];
    
        // Initialize keywords array
        $keywords = [];
    
        // Add variations by combining prefixes and suffixes with the title
        foreach ($prefixes as $prefix) {
            $keywords[] = "$prefix $title";
        }
    
        foreach ($suffixes as $suffix) {
            $keywords[] = "$title $suffix";
        }
    
        // Add some combinations of both prefixes and suffixes
        foreach ($prefixes as $prefix) {
            foreach ($suffixes as $suffix) {
                $keywords[] = "$prefix $title $suffix";
            }
        }
    
        // Add some generic and related keywords
        $genericKeywords = ["Quotes on Happiness", "Quotes about Joy", "Happiness and Life Quotes", "Positive Quotes", "Uplifting Quotes", "Feel Good Quotes"];
        $keywords = array_merge($keywords, $genericKeywords);
    
        // Remove duplicates and return the keywords
        return array_unique($keywords);
    }




function formatDate($date)
{
  // Create a DateTime object from the given date string
  $dateTime = new DateTime($date);

  // Format the date to "M d" where "M" is the abbreviated month and "d" is the day
  return $dateTime->format('M j');
}