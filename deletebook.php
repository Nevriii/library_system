<?php

$conn = new mysqli('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $bookID = $_GET['id'];

    $delete = "DELETE FROM book WHERE BOOKID = $bookID";

    if ($conn->query($delete) === TRUE) {
        header("Location: Library-Menu.php");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();

?>
