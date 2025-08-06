<?php

$author = $_POST['author'];
$genre = $_POST['genre'];
$title = $_POST['title'];
$language = $_POST['language'];
$year = $_POST['year'];

$targetDir = "upload/";
$targetFile = basename($_FILES["picture"]["name"]);

if ($_FILES["picture"]["name"] !== "") {
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], "upload/" . $targetFile)) {
        header("Location: Library-Menu.php");
    } else {
        echo "Sorry, there was an error uploading your file.";
        exit();
    }
} else {
    $targetFile = "default.jpg";
}

$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO book(author, genre, title, language, year, picture) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $author, $genre, $title, $language, $year, $targetFile);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
