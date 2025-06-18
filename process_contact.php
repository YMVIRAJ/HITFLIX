<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/config.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: landing.php');
    exit;
}

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// Get current user
$currentUser = getCurrentUser();

// Function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Initialize variables
$errors = [];
$success = false;

// Validate and sanitize form inputs
$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

// Validation
if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!isValidEmail($email)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
} elseif (strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters long';
}

// Basic spam protection - check for suspicious content
$spam_keywords = ['viagra', 'casino', 'lottery', 'winner', 'congratulations', 'urgent', 'click here', 'make money'];
$message_lower = strtolower($message);
foreach ($spam_keywords as $keyword) {
    if (strpos($message_lower, $keyword) !== false) {
        $errors[] = 'Your message appears to contain spam content. Please revise and try again.';
        break;
    }
}

// Check for too many links in message (basic spam detection)
if (substr_count($message, 'http') > 2) {
    $errors[] = 'Please limit the number of links in your message.';
}

// Rate limiting - check if user has sent too many messages recently
$rate_limit_key = 'contact_form_' . $currentUser['id'];
$last_submission = $_SESSION[$rate_limit_key] ?? 0;
$time_since_last = time() - $last_submission;

if ($time_since_last < 300) { // 5 minutes rate limit
    $errors[] = 'Please wait a few minutes before sending another message.';
}

// If no errors, process the form
if (empty($errors)) {
    try {
        // Here you would typically save to database and/or send email
        // For this example, we'll simulate the process
        
        // Update rate limiting
        $_SESSION[$rate_limit_key] = time();
        
        // Prepare email data (in a real application, you'd send this via email)
        $email_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'user_id' => $currentUser['id'],
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'newsletter_signup' => $newsletter,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];
        
        // In a real application, you might:
        // 1. Save to database
        // 2. Send email to support team
        // 3. Send confirmation email to user
        // 4. Log the contact request
        
        // For demonstration, we'll just log to a file (in production, use proper logging)
        $log_entry = json_encode($email_data) . "\n";
        
        // Create logs directory if it doesn't exist
        if (!file_exists('logs')) {
            mkdir('logs', 0755, true);
        }
        
        // Log the contact request (in production, use proper database storage)
        file_put_contents('logs/contact_requests.log', $log_entry, FILE_APPEND | LOCK_EX);
        
        // Send email notification (in production, use proper email service)
        $to = 'hitflixprime@gmail.com'; // In production, get from environment variable
        $email_subject = 'HITFLIX Contact Form: ' . $subject;
        $email_message = "
New contact form submission from HITFLIX:

Name: {$name}
Email: {$email}
Subject: {$subject}
Newsletter Signup: " . ($newsletter ? 'Yes' : 'No') . "
User ID: {$currentUser['id']}
Timestamp: {$email_data['timestamp']}

Message:
{$message}

--
This message was sent from the HITFLIX contact form.
        ";
        
        $headers = "From: hitflixprime@gmail.com\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // In production, use a proper email service like PHPMailer, Sendgrid, etc.
        // mail($to, $email_subject, $email_message, $headers);
        
        // Set success message
        $_SESSION['contact_success'] = 'Thank you for your message! We\'ll get back to you within 24 hours.';
        
    } catch (Exception $e) {
        // Log error and show user-friendly message
        error_log('Contact form error: ' . $e->getMessage());
        $errors[] = 'Sorry, there was an error processing your request. Please try again later.';
    }
}

// If there were errors, store them in session and redirect back
if (!empty($errors)) {
    $_SESSION['contact_error'] = implode(' ', $errors);
    header('Location: contact.php');
    exit;
}

// Success - redirect back to contact page
header('Location: contact.php');
exit;
?>
