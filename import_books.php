<?php
// Database Import Script for Library System
// This script allows importing book data from CSV files or manual entry

// Database connection configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create the book table if it doesn't exist
function createBookTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS book (
        BOOKID INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        genre VARCHAR(100) NOT NULL,
        language VARCHAR(50) NOT NULL,
        year INT(4) NOT NULL,
        picture VARCHAR(255) DEFAULT 'default-image.jpg',
        price DECIMAL(10,2) DEFAULT 0.00,
        description TEXT,
        isbn VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Book table created successfully or already exists.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Function to import from CSV file
function importFromCSV($conn, $csvFile) {
    if (!file_exists($csvFile)) {
        return "CSV file not found: $csvFile";
    }
    
    $handle = fopen($csvFile, "r");
    if (!$handle) {
        return "Unable to open CSV file";
    }
    
    $imported = 0;
    $errors = 0;
    $row = 1;
    
    // Skip header row
    $header = fgetcsv($handle);
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        $row++;
        
        // Check if we have enough columns
        if (count($data) < 5) {
            $errors++;
            continue;
        }
        
        // Extract data from CSV columns
        $title = $conn->real_escape_string(trim($data[0]));
        $author = $conn->real_escape_string(trim($data[1]));
        $genre = $conn->real_escape_string(trim($data[2]));
        $language = $conn->real_escape_string(trim($data[3]));
        $year = intval(trim($data[4]));
        $price = isset($data[5]) ? floatval(trim($data[5])) : 0.00;
        $description = isset($data[6]) ? $conn->real_escape_string(trim($data[6])) : '';
        $isbn = isset($data[7]) ? $conn->real_escape_string(trim($data[7])) : '';
        $picture = isset($data[8]) ? $conn->real_escape_string(trim($data[8])) : 'default-image.jpg';
        
        // Validate data
        if (empty($title) || empty($author) || empty($genre) || empty($language) || $year < 1800 || $year > date('Y')) {
            $errors++;
            continue;
        }
        
        // Insert into database
        $sql = "INSERT INTO book (title, author, genre, language, year, price, description, isbn, picture) 
                VALUES ('$title', '$author', '$genre', '$language', $year, $price, '$description', '$isbn', '$picture')";
        
        if ($conn->query($sql) === TRUE) {
            $imported++;
        } else {
            $errors++;
        }
    }
    
    fclose($handle);
    return "Import completed: $imported books imported, $errors errors encountered.";
}

// Function to import single book manually
function importSingleBook($conn, $bookData) {
    $title = $conn->real_escape_string($bookData['title']);
    $author = $conn->real_escape_string($bookData['author']);
    $genre = $conn->real_escape_string($bookData['genre']);
    $language = $conn->real_escape_string($bookData['language']);
    $year = intval($bookData['year']);
    $price = floatval($bookData['price']);
    $description = $conn->real_escape_string($bookData['description']);
    $isbn = $conn->real_escape_string($bookData['isbn']);
    $picture = $conn->real_escape_string($bookData['picture']);
    
    $sql = "INSERT INTO book (title, author, genre, language, year, price, description, isbn, picture) 
            VALUES ('$title', '$author', '$genre', '$language', $year, $price, '$description', '$isbn', '$picture')";
    
    if ($conn->query($sql) === TRUE) {
        return "Book '$title' imported successfully!";
    } else {
        return "Error importing book: " . $conn->error;
    }
}

// Function to display sample CSV format
function displayCSVFormat() {
    return "CSV Format (comma-separated values):<br>
    title,author,genre,language,year,price,description,isbn,picture<br>
    Example:<br>
    \"The Great Gatsby\",\"F. Scott Fitzgerald\",\"Fiction\",\"English\",1925,15.99,\"A story of the Jazz Age\",\"978-0743273565\",\"gatsby.jpg\"<br><br>";
}

// Handle form submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_table':
                createBookTable($conn);
                $message = "Table creation completed.";
                break;
                
            case 'import_csv':
                if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
                    $uploadDir = 'uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $uploadFile = $uploadDir . basename($_FILES['csv_file']['name']);
                    if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $uploadFile)) {
                        $message = importFromCSV($conn, $uploadFile);
                        unlink($uploadFile); // Delete uploaded file
                    } else {
                        $message = "Failed to upload CSV file.";
                    }
                } else {
                    $message = "Please select a valid CSV file.";
                }
                break;
                
            case 'import_single':
                $bookData = [
                    'title' => $_POST['title'] ?? '',
                    'author' => $_POST['author'] ?? '',
                    'genre' => $_POST['genre'] ?? '',
                    'language' => $_POST['language'] ?? '',
                    'year' => $_POST['year'] ?? date('Y'),
                    'price' => $_POST['price'] ?? 0.00,
                    'description' => $_POST['description'] ?? '',
                    'isbn' => $_POST['isbn'] ?? '',
                    'picture' => $_POST['picture'] ?? 'default-image.jpg'
                ];
                $message = importSingleBook($conn, $bookData);
                break;
        }
    }
}

// Get current book count
$result = $conn->query("SELECT COUNT(*) as count FROM book");
$bookCount = $result ? $result->fetch_assoc()['count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Database Import Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            color: #555;
            border-bottom: 2px solid #478ac9;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], input[type="number"], input[type="file"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            background-color: #478ac9;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .btn:hover {
            background-color: #357abd;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .stats {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .csv-format {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre-line;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Library Database Import Tool</h1>
        
        <div class="stats">
            <strong>Current Status:</strong> <?php echo $bookCount; ?> books in database
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : ''; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="grid">
            <!-- Create Table Section -->
            <div class="container">
                <h2>🔧 Database Setup</h2>
                <p>Create the book table if it doesn't exist:</p>
                <form method="post">
                    <input type="hidden" name="action" value="create_table">
                    <button type="submit" class="btn">Create Book Table</button>
                </form>
            </div>
            
            <!-- CSV Import Section -->
            <div class="container">
                <h2>📁 Import from CSV File</h2>
                <p>Upload a CSV file to import multiple books at once:</p>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="import_csv">
                    <div class="form-group">
                        <label for="csv_file">Select CSV File:</label>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
                    </div>
                    <button type="submit" class="btn">Import from CSV</button>
                </form>
                
                <div class="csv-format">
                    <?php echo displayCSVFormat(); ?>
                </div>
            </div>
        </div>
        
        <!-- Manual Import Section -->
        <div class="container">
            <h2>✏️ Manual Book Entry</h2>
            <p>Add a single book manually:</p>
            <form method="post">
                <input type="hidden" name="action" value="import_single">
                
                <div class="grid">
                    <div class="form-group">
                        <label for="title">Book Title *:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="author">Author *:</label>
                        <input type="text" id="author" name="author" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="genre">Genre *:</label>
                        <input type="text" id="genre" name="genre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="language">Language *:</label>
                        <input type="text" id="language" name="language" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="year">Publication Year *:</label>
                        <input type="number" id="year" name="year" min="1800" max="<?php echo date('Y') + 1; ?>" value="<?php echo date('Y'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (PHP):</label>
                        <input type="number" id="price" name="price" min="0" step="0.01" value="0.00">
                    </div>
                    
                    <div class="form-group">
                        <label for="isbn">ISBN:</label>
                        <input type="text" id="isbn" name="isbn">
                    </div>
                    
                    <div class="form-group">
                        <label for="picture">Image Filename:</label>
                        <input type="text" id="picture" name="picture" value="default-image.jpg">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Enter book description..."></textarea>
                </div>
                
                <button type="submit" class="btn">Add Book</button>
            </form>
        </div>
        
        <!-- Navigation -->
        <div class="container">
            <h2>🔗 Navigation</h2>
            <a href="Library-Menu.php" class="btn">View Library Menu</a>
            <a href="index.php" class="btn">Go to Homepage</a>
            <a href="addbook.php" class="btn">Add Book (Original Form)</a>
        </div>
    </div>
    
    <script>
        // Auto-refresh page after successful import
        <?php if ($message && strpos($message, 'successfully') !== false): ?>
        setTimeout(function() {
            location.reload();
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>

<?php
$conn->close();
?> 