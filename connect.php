<?php
$firstn = $_POST['firstname'];
$lastn = $_POST['lastname'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$pass = $_POST['password'];
$number = $_POST['number'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$stmt = $conn->prepare("INSERT INTO test(firstname, lastname, gender, email, password, number) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $firstn, $lastn, $gender, $email, $pass, $number);

if ($stmt->execute()) {
    header ("Location: Login.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
