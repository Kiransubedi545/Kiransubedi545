<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate reCAPTCHA
    $recaptchaSecret = 'YOUR_RECAPTCHA_SECRET_KEY';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify";

    $response = file_get_contents("$recaptchaUrl?secret=$recaptchaSecret&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        die("reCAPTCHA verification failed. Please try again.");
    }

    // Sanitize and collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Email details
    $to = "your-email@example.com"; // Replace with your email
    $subject = "New Contact Form Submission";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send the message. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
?>
