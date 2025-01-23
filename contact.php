<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptchaSecret = '6Lc1hMEqAAAAACDbboh38BmK9q7cBo3fvmnXjgrs';
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse,
    ];

    // Use cURL to send the request
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    // Check the success status of reCAPTCHA
    if ($result['success'] && $result['score'] >= 0.5) { // Adjust the threshold as needed
        // Process the form
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        $to = "your-email@example.com";
        $subject = "New Contact Form Submission";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            echo "Message sent successfully!";
        } else {
            echo "Failed to send the message. Please try again.";
        }
    } else {
        echo "reCAPTCHA validation failed. Please try again.";
    }
}
?>
