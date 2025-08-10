<?php
/**
 * Sanitization Helper Functions
 * Replaces deprecated FILTER_SANITIZE_STRING
 */

/**
 * Sanitize POST data by removing HTML tags and encoding special characters
 * @param array $data The POST data to sanitize
 * @return array The sanitized data
 */
function sanitizePostData($data) {
    // Verificar se $data é um array válido
    if (!is_array($data) || empty($data)) {
        return [];
    }
    
    $sanitized = [];
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            // Remove HTML tags and encode special characters
            $sanitized[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
        } else {
            $sanitized[$key] = $value;
        }
    }
    return $sanitized;
}

/**
 * Sanitize a single string value
 * @param string $value The string to sanitize
 * @return string The sanitized string
 */
function sanitizeString($value) {
    if (!is_string($value)) {
        return '';
    }
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}
