<?php
// Database Setup Script
// This script helps you import the library_database.sql file

// Database connection configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'test';

// Function to execute SQL file
function executeSQLFile($conn, $sqlFile) {
    if (!file_exists($sqlFile)) {
        return "SQL file not found: $sqlFile";
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $success = 0;
    $errors = 0;
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                if ($conn->query($statement) === TRUE) {
                    $success++;
                } else {
                    $errors++;
                    echo "Error executing statement: " . $conn->error . "<br>";
                }
            } catch (Exception $e) {
                $errors++;
                echo "Exception: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    return "SQL execution completed: $success statements successful, $errors errors encountered.";
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['setup_database'])) {
    try {
        // Create connection without specifying database first
        $conn = new mysqli($host, $username, $password);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Execute the SQL file
        $message = executeSQLFile($conn, 'library_database.sql');
        
        $conn->close();
        
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Check if database exists and get table info
$tableInfo = '';
try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if (!$conn->connect_error) {
        $result = $conn->query("SHOW TABLES");
        $tables = [];
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        
        if (!empty($tables)) {
            $tableInfo = "Found " . count($tables) . " tables: " . implode(', ', $tables);
        } else {
            $tableInfo = "Database exists but no tables found.";
        }
        
        // Get book count if table exists
        $bookResult = $conn->query("SELECT COUNT(*) as count FROM book");
        if ($bookResult) {
            $bookCount = $bookResult->fetch_assoc()['count'];
            $tableInfo .= " | Books in database: $bookCount";
        }
        
        $conn->close();
    }
} catch (Exception $e) {
    $tableInfo = "Database not accessible: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        .btn {
            background-color: #478ac9;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
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
        .info {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .steps {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .steps ol {
            margin: 0;
            padding-left: 20px;
        }
        .steps li {
            margin-bottom: 10px;
        }
        .file-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Library Database Setup</h1>
        
        <div class="info">
            <strong>Current Status:</strong> <?php echo $tableInfo ?: 'Database not accessible'; ?>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : ''; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="container">
            <h2>🔧 Database Setup Options</h2>
            
            <div class="warning">
                <strong>⚠️ Important:</strong> This will create/recreate the database structure. 
                If you have existing data, make sure to backup first!
            </div>
            
            <div class="steps">
                <h3>Setup Steps:</h3>
                <ol>
                    <li>Ensure MySQL/XAMPP is running</li>
                    <li>Verify database credentials (localhost, root, no password)</li>
                    <li>Click "Setup Database" to import the SQL file</li>
                    <li>Check the results and verify tables are created</li>
                </ol>
            </div>
            
            <form method="post">
                <button type="submit" name="setup_database" class="btn" onclick="return confirm('This will recreate the database structure. Continue?')">
                    🚀 Setup Database
                </button>
            </form>
        </div>
        
        <div class="container">
            <h2>📁 SQL File Information</h2>
            
            <div class="file-info">
                <strong>File:</strong> library_database.sql<br>
                <strong>Size:</strong> <?php echo file_exists('library_database.sql') ? number_format(filesize('library_database.sql')) . ' bytes' : 'File not found'; ?><br>
                <strong>Last Modified:</strong> <?php echo file_exists('library_database.sql') ? date('Y-m-d H:i:s', filemtime('library_database.sql')) : 'N/A'; ?>
            </div>
            
            <h3>What this SQL file includes:</h3>
            <ul>
                <li>✅ Complete database structure</li>
                <li>✅ Book table with all required fields</li>
                <li>✅ User registration table</li>
                <li>✅ Sample book data (15 books)</li>
                <li>✅ Sample user data (5 users)</li>
                <li>✅ Categories table</li>
                <li>✅ Book status tracking</li>
                <li>✅ Database views and stored procedures</li>
                <li>✅ Triggers for data integrity</li>
            </ul>
        </div>
        
        <div class="container">
            <h2>🔗 Alternative Setup Methods</h2>
            
            <h3>Method 1: Using phpMyAdmin</h3>
            <ol>
                <li>Open phpMyAdmin (usually at http://localhost/phpmyadmin)</li>
                <li>Create a new database named "test"</li>
                <li>Go to the "Import" tab</li>
                <li>Select the "library_database.sql" file</li>
                <li>Click "Go" to import</li>
            </ol>
            
            <h3>Method 2: Using MySQL Command Line</h3>
            <div class="file-info">
                mysql -u root -p test &lt; library_database.sql
            </div>
            
            <h3>Method 3: Using MySQL Workbench</h3>
            <ol>
                <li>Open MySQL Workbench</li>
                <li>Connect to your MySQL server</li>
                <li>Open the "library_database.sql" file</li>
                <li>Execute the script</li>
            </ol>
        </div>
        
        <div class="container">
            <h2>🔗 Navigation</h2>
            <a href="import_books.php" class="btn">📚 Import Books Tool</a>
            <a href="Library-Menu.php" class="btn">📖 Library Menu</a>
            <a href="index.php" class="btn">🏠 Homepage</a>
        </div>
    </div>
    
    <script>
        // Auto-refresh page after successful setup
        <?php if ($message && strpos($message, 'successful') !== false): ?>
        setTimeout(function() {
            location.reload();
        }, 3000);
        <?php endif; ?>
    </script>
</body>
</html> 