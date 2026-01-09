<?php
/**
 * Legacy Constants File
 * 
 * This file is kept for backward compatibility
 * New code should use config.php instead
 * 
 * @deprecated Use config/config.php instead
 */

// Load new configuration
require_once __DIR__ . '/config.php';

// Legacy constants for backward compatibility
define('CSSPATH', CSS_PATH);
define('JSPATH', JS_PATH);
define('IMAGESPATH', IMAGE_PATH);