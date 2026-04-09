<?php
/**
 * Utility functions for NAW management system
 * Contains reusable functions for validation and sanitization
 */

require_once 'config.php';

/**
 * Sanitize user input to prevent XSS attacks
 * 
 * @param string $data Raw input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email address format
 * 
 * @param string $email Email to validate
 * @return bool True if valid, false otherwise
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Dutch postal code format (1234 AB)
 * 
 * @param string $postalCode Postal code to validate
 * @return bool True if valid, false otherwise
 */
function validatePostalCode($postalCode) {
    $pattern = '/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/';
    return preg_match($pattern, $postalCode) === 1;
}

/**
 * Display Bootstrap alert message
 * 
 * @param string $message Message to display
 * @param string $type Alert type (success, danger, warning, info)
 * @return string HTML alert element
 */
function showAlert($message, $type = 'info') {
    return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

/**
 * Redirect to specified page with optional message
 * 
 * @param string $page Page to redirect to
 * @param string $message Optional message to display
 * @param string $type Message type
 */
function redirect($page, $message = '', $type = 'info') {
    if (!empty($message)) {
        session_start();
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    header("Location: {$page}");
    exit();
}
?>