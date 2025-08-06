<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $title = $_POST['title'];
    $language = $_POST['language'];
    $year = $_POST['year'];
    

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("UPDATE book SET author=?, genre=?, title=?, language=?, year=? WHERE BOOKID=?");
    $stmt->bind_param("sssssi", $author, $genre, $title, $language, $year, $id);

    if ($stmt->execute()) {
        header("Location: Library-Menu.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect or handle the case when the form is not submitted via POST
    echo "error";
    exit();
}

$conn->close();
?>
