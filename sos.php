<?php
require_once 'vendor/autoload.php'; // Composer's autoloader for Twilio and PHPMailer

use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "save_her_db";

// Twilio credentials
$account_sid = 'AC008ae6058ca67794f18ffb08c575311f';
$auth_token = 'c35a903628b5b8b2179b62b0124372f8';
$twilio_phone_number = '+17162654456';

// Gmail SMTP credentials for PHPMailer
$smtp_email = 'rahulkalita341@gmail.com'; // Your Gmail email
$smtp_password = 'ksru dccn ribe hwbg'; // Gmail App-specific password

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send SMS using Twilio
function sendSMS($to, $message) {
    global $account_sid, $auth_token, $twilio_phone_number;

    $client = new Client($account_sid, $auth_token);

    try {
        $client->messages->create(
            $to, // To phone number
            [
                'from' => $twilio_phone_number, // From Twilio number
                'body' => $message
            ]
        );
        echo "SMS sent to $to successfully!".'<br>';
    } catch (Exception $e) {
        echo "Failed to send SMS to $to: " . $e->getMessage() . "<br>";
    }
}

// Function to send Email using PHPMailer
function sendEmail($to, $message) {
    global $smtp_email, $smtp_password;

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Gmail SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_email;       // Your Gmail email address
        $mail->Password   = $smtp_password;    // Gmail app-specific password
        $mail->SMTPSecure = 'tls';             // Enable TLS encryption
        $mail->Port       = 587;               // Port to connect to

        // Recipients
        $mail->setFrom($smtp_email, 'Save Her App');
        $mail->addAddress($to);                // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'SOS Alert!';
        $mail->Body    = $message;

        $mail->send();
        echo "Email sent to $to successfully!<br>";
    } catch (Exception $e) {
        echo "Failed to send email to $to: " . $mail->ErrorInfo . "<br>";
    }
}

// SOS logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $message = "SOS! I am in danger. My location: https://www.google.com/maps?q=$latitude,$longitude";

    // Fetch contacts from the database
    $sql = "SELECT phone, email FROM contacts";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $phone = $row['phone'];
            $email = $row['email'];

            // Send SMS to the contact
            sendSMS($phone, $message);

            // Send Email to the contact
            sendEmail($email, $message);
        }
    } else {
        echo "No contacts found!";
    }
}

$conn->close();
?>
