<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_form_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    // Validate email and message
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email address.";
        exit;
    }

    if (empty($message)) {
        http_response_code(400);
        echo "Please enter a message.";
        exit;
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO submissions (email, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo "Thank you! Your message has been saved.";
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong, and we couldn't save your message.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // Not a POST request
    http_response_code(403);
    echo "There was a problem with your submission. Please try again.";
}
?>
