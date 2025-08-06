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
<html style="font-size: 16px;" lang="en"><head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Edit</title>
    <link rel="stylesheet" href="nicepage.css" media="screen">
<link rel="stylesheet" href="Edit.css" media="screen">
    
    <meta name="generator" content="Nicepage 6.0.3, nicepage.com">
    <meta name="referrer" content="origin">
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
    <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amita:400,700">
    
    
    
    
    <meta name="theme-color" content="#478ac9">
    <meta property="og:title" content="Edit">
    <meta property="og:type" content="website">
  <meta data-intl-tel-input-cdn-path="intlTelInput/"></head>
  <body data-path-to-root="./" data-include-products="true" class="u-body u-xl-mode" data-lang="en">
    <section class="u-clearfix u-custom-color-4 u-section-1" id="sec-1aaa">
      <div class="u-clearfix u-sheet u-sheet-1">
        <p class="u-custom-font u-text u-text-default u-text-1">Edit Book</p>
        <div class="u-form u-form-1">
          <form action="updatebook.php" method="post"  class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" source="email" name="form" style="padding: 10px;">
          
          <input type="hidden" name="id" value="<?php echo $book['BOOKID']; ?>">
<div class="u-form-group u-form-name">
  <label for="name-d685" class="u-label">Title</label>
  <input type="text" placeholder="Enter Title" id="title" name="title" class="u-input u-input-rectangle" required="" value="<?php echo $book['title']; ?>">
</div>
<div class="u-form-group">
  <label for="email-d685" class="u-label">Author</label>
  <input type="text" placeholder="Enter Title" id="author" name="author" class="u-input u-input-rectangle" required="required" value="<?php echo $book['author']; ?>">
</div>
<div class="u-form-group u-form-group-3">
  <label for="text-6b14" class="u-label">Genre</label>
  <input type="text" placeholder="Enter Genre" id="genre" name="genre" class="u-input u-input-rectangle" required="required" value="<?php echo $book['genre']; ?>">
</div>
<div class="u-form-group u-form-number u-form-number-layout-number u-form-group-4">
  <label for="number-a806" class="u-label">Year Published</label>
  <div class="u-input-row" data-value="">
    <input min="100" max="10000" step="1" type="number" placeholder="" id="year" name="number" class="u-input u-input-rectangle u-number u-text-black" value="<?php echo $book['year']; ?>">
  </div>
</div>
<div class="u-form-group u-form-group-5">
  <label for="text-ae7d" class="u-label">Language</label>
  <input type="text" placeholder="Enter Language" id="language" name="language" class="u-input u-input-rectangle" required="required" value="<?php echo $book['language']; ?>">
</div>
          
<button type="submit" name="update" class="btn btn-primary">Update Book</button>
          </form>
        </div>
        <a href="Library-Menu.php" class="u-border-2 u-border-black u-btn u-button-style u-hover-black u-none u-text-hover-white u-btn-2">Back</a>
      </div>
    </section>
    
  
</body></html>