<?php
session_start();

if (isset($_SESSION['ID']) && isset($_SESSION['email'])) {

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'test');
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    $id = $_SESSION['ID'];

    // Prepare and execute the SQL query to delete the row
    $stmt = $conn->prepare("DELETE FROM test WHERE ID = ?");
    $stmt->bind_param("i", $id); // Assuming ID is an integer, adjust accordingly if it's a different type

    if ($stmt->execute()) {
        header("Location: Login.php");
    } else {
        // Deletion failed
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    // User is not logged in, redirect to index.php
    header("Location: index.php");
    exit();
}
?>
