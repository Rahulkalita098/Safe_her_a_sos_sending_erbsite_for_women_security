<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "save_her_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling the form submission to add a contact
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert the new contact into the contacts table
    $sql = "INSERT INTO contacts (name, phone, email) VALUES ('$name', '$phone', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "New contact added successfully!";
        
        // Output JavaScript to open the Twilio URL in a new tab
        echo '<script type="text/javascript">
                window.open("https://console.twilio.com/us1/develop/phone-numbers/manage/verified", "_blank");
              </script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
