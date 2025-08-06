<?php


$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $bookId = $_GET['id'];

    $result = $conn->query("SELECT * FROM book WHERE BOOKID = $bookId");

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "Book not found.";
        exit;
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $bookId = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];
    $language = $_POST['language'];

    // Update the book details in the database
    $updateQuery = "UPDATE book SET title='$title', author='$author', genre='$genre', year='$year', language='$language' WHERE BOOKID=$bookId";

    if ($conn->query($updateQuery) === TRUE) {
        echo "Book updated successfully!";
    } else {
        echo "Error updating book: " . $conn->error;
    }

    // Fetch the updated book details for display
    $result = $conn->query("SELECT * FROM book WHERE BOOKID = $bookId");
    $book = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>
<body>

<div class="container">
    <div class="row col-md-6">
        <h2>Edit Book</h2>
        <form method="post" action="editbook.php">
            <input type="hidden" name="id" value="<?php echo $book['BOOKID']; ?>">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" value="<?php echo $book['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" name="author" value="<?php echo $book['author']; ?>" required>
            </div>
            <div class="form-group">
                <label for="genre">Genre:</label>
                <input type="text" class="form-control" name="genre" value="<?php echo $book['genre']; ?>" required>
            </div>
            <div class="form-group">
                <label for="year">Year Published:</label>
                <input type="text" class="form-control" name="year" value="<?php echo $book['year']; ?>" required>
            </div>
            <div class="form-group">
                <label for="language">Language:</label>
                <input type="text" class="form-control" name="language" value="<?php echo $book['language']; ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Book</button>
        </form>
    </div>
</div>

</body>
</html>
