<?php

namespace App\Core;

// Include ViewHelpers for all views
require_once __DIR__ . '/../helpers/ViewHelpers.php';

class View {
    protected $viewPath;
    protected $layout = 'layouts/base';
    protected $data = [];
    protected $sections = [];
    protected $currentSection;

    public function __construct() {
        // Set default view path
        $this->viewPath = __DIR__ . '/../views/';
    }

    /**
     * Set the layout to use
     * 
     * @param string $layout Layout name (relative to views directory)
     * @return self
     */
    public function setLayout($layout) {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Assign data to the view
     * 
     * @param string|array $key Variable name or array of variables
     * @param mixed $value Value (optional if $key is array)
     * @return self
     */
    public function assign($key, $value = null) {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Start a new section
     * 
     * @param string $name Section name
     */
    public function startSection($name) {
        $this->currentSection = $name;
        ob_start();
    }

    /**
     * End the current section
     */
    public function endSection() {
        if (empty($this->currentSection)) {
            throw new \Exception("No section started");
        }
        
        $content = ob_get_clean();
        $this->sections[$this->currentSection] = $content;
        $this->currentSection = null;
    }

    /**
     * Get section content (for use in layouts)
     * 
     * @param string $name Section name
     * @param string $default Default content
     * @return string
     */
    public function section($name, $default = '') {
        return $this->sections[$name] ?? $default;
    }

    /**
     * Render a view file
     * 
     * @param string $view View name (relative to views directory)
     * @param array $data Data to pass to view
     * @return string Rendered content
     */
    public function render($view, $data = []) {
        $this->assign($data);
        
        // Extract data to local scope
        extract($this->data);
        
        // Start buffering content
        ob_start();
        
        $viewFile = $this->viewPath . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: $viewFile");
        }
        
        include $viewFile;
        
        // Capture view content
        $content = ob_get_clean();
        
        // If layout is set, render layout with content
        if ($this->layout) {
            $this->data['content'] = $content;
            extract($this->data);
            
            $layoutFile = $this->viewPath . $this->layout . '.php';
            if (!file_exists($layoutFile)) {
                throw new \Exception("Layout file not found: $layoutFile");
            }
            
            ob_start();
            include $layoutFile;
            return ob_get_clean();
        }
        
        return $content;
    }

    /**
     * Helper to escape HTML output
     * 
     * @param string $string
     * @return string
     */
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    // Static helper for quick rendering
    public static function make($view, $data = []) {
        $instance = new self();
        return $instance->render($view, $data);
    }
}
